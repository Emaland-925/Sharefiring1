<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled courses
        $enrolledCourses = $user->enrolledCourses()->with('creator')->get();
        
        // Get available courses (not enrolled)
        $availableCourses = Course::where('status', 'approved')
            ->where('company_id', $user->company_id)
            ->whereDoesntHave('enrolledUsers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with('creator')
            ->get();
        
        return view('courses.index', compact('enrolledCourses', 'availableCourses'));
    }
    
    public function show($id)
    {
        $course = Course::with(['creator', 'lessons', 'quizzes'])->findOrFail($id);
        
        // Check if user is enrolled
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        return view('courses.show', compact('course', 'enrollment'));
    }
    
    public function create()
    {
        $user = Auth::user();
        $categories = Category::where('company_id', $user->company_id)->get();
        
        return view('courses.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'points_reward' => 'nullable|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create course
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => $user->id,
            'company_id' => $user->company_id,
            'category_id' => $request->category_id,
            'points_reward' => $request->points_reward ?? 10,
            'status' => $user->isCompanyAdmin() ? 'approved' : 'pending',
        ]);
        
        return redirect()->route('courses.edit', $course->id)->with('success', __('messages.course_created'));
    }
    
    public function edit($id)
    {
        $course = Course::with(['lessons', 'quizzes'])->findOrFail($id);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $categories = Category::where('company_id', $user->company_id)->get();
        
        return view('courses.edit', compact('course', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'points_reward' => 'nullable|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update course
        $course->title = $request->title;
        $course->description = $request->description;
        $course->category_id = $request->category_id;
        $course->points_reward = $request->points_reward ?? $course->points_reward;
        $course->save();
        
        return redirect()->route('courses.edit', $course->id)->with('success', __('messages.course_updated'));
    }
    
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        // Delete course
        $course->delete();
        
        return redirect()->route('courses.index')->with('success', __('messages.course_deleted'));
    }
    
    public function enroll($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        
        // Check if already enrolled
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if ($enrollment) {
            return redirect()->route('courses.show', $course->id)->with('info', __('messages.already_enrolled'));
        }
        
        // Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
            'completion_status' => 'in_progress',
        ]);
        
        return redirect()->route('courses.show', $course->id)->with('success', __('messages.enrolled_success'));
    }
    
    public function approve($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        
        // Check if user is admin or company admin
        if (!$user->isAdmin() && !$user->isCompanyAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        // Approve course
        $course->status = 'approved';
        $course->save();
        
        return redirect()->back()->with('success', __('messages.course_approved'));
    }
    
    public function reject($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        
        // Check if user is admin or company admin
        if (!$user->isAdmin() && !$user->isCompanyAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        // Reject course
        $course->status = 'rejected';
        $course->save();
        
        return redirect()->back()->with('success', __('messages.course_rejected'));
    }
    
    public function addLesson($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        return view('courses.add-lesson', compact('course'));
    }
    
    public function storeLesson(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:100000',
            'order_num' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create lesson
        $lesson = new Lesson([
            'title' => $request->title,
            'content' => $request->content,
            'order_num' => $request->order_num,
        ]);
        
        // Handle video upload if provided
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('lesson_videos', 'public');
            $lesson->video_url = $path;
        }
        
        $course->lessons()->save($lesson);
        
        return redirect()->route('courses.edit', $course->id)->with('success', __('messages.lesson_added'));
    }
    
    public function editLesson($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($lessonId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        return view('courses.edit-lesson', compact('course', 'lesson'));
    }
    
    public function updateLesson(Request $request, $courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($lessonId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:100000',
            'order_num' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update lesson
        $lesson->title = $request->title;
        $lesson->content = $request->content;
        $lesson->order_num = $request->order_num;
        
        // Handle video upload if provided
        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($lesson->video_url) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            
            $path = $request->file('video')->store('lesson_videos', 'public');
            $lesson->video_url = $path;
        }
        
        $lesson->save();
        
        return redirect()->route('courses.edit', $course->id)->with('success', __('messages.lesson_updated'));
    }
    
    public function destroyLesson($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($lessonId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        // Delete video if exists
        if ($lesson->video_url) {
            Storage::disk('public')->delete($lesson->video_url);
        }
        
        // Delete lesson
        $lesson->delete();
        
        return redirect()->route('courses.edit', $course->id)->with('success', __('messages.lesson_deleted'));
    }
    
    public function addQuiz($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        return view('courses.add-quiz', compact('course'));
    }
    
    public function storeQuiz(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create quiz
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'passing_score' => $request->passing_score ?? 70,
        ]);
        
        return redirect()->route('quizzes.edit', $quiz->id)->with('success', __('messages.quiz_added'));
    }
}
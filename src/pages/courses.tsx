import React, { useEffect, useState } from 'react';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { CourseCard } from '@/components/course-card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { fine } from '@/lib/fine';
import { cn } from '@/lib/utils';
import { ProtectedRoute } from '@/components/auth/route-components';
import { Loader2, Search, Plus } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';

const CoursesContent = () => {
  const { t, language } = useLanguage();
  const { toast } = useToast();
  const isRtl = language === 'ar';
  const { data: session } = fine.auth.useSession();
  
  const [isLoading, setIsLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  
  const [enrolledCourses, setEnrolledCourses] = useState<Array<{
    id: number;
    title: string;
    description: string;
    progress: number;
    isCompleted: boolean;
  }>>([]);
  
  const [availableCourses, setAvailableCourses] = useState<Array<{
    id: number;
    title: string;
    description: string;
    creator_id: number;
    creator_name?: string;
  }>>([]);
  
  useEffect(() => {
    const fetchCourses = async () => {
      if (!session?.user?.id) return;
      
      try {
        // Fetch enrolled courses
        const enrollments = await fine.table("enrollments")
          .select("course_id, progress, completion_status")
          .eq("user_id", Number(session.user.id));
        
        const enrolledCourseIds = enrollments?.map(e => e.course_id) || [];
        
        // Fetch all courses
        const allCourses = await fine.table("courses")
          .select("id, title, description, creator_id, status")
          .eq("status", "approved");
        
        if (allCourses) {
          // Get creator names
          const creatorIds = [...new Set(allCourses.map(course => course.creator_id))];
          const creators = await fine.table("users")
            .select("id, name")
            .in("id", creatorIds);
          
          const creatorMap = new Map();
          creators?.forEach(creator => {
            creatorMap.set(creator.id, creator.name);
          });
          
          // Process enrolled courses
          if (enrollments && enrollments.length > 0) {
            const enrolledCoursesData = allCourses
              .filter(course => enrolledCourseIds.includes(course.id))
              .map(course => {
                const enrollment = enrollments.find(e => e.course_id === course.id);
                return {
                  id: course.id,
                  title: course.title,
                  description: course.description || '',
                  progress: enrollment?.progress || 0,
                  isCompleted: enrollment?.completion_status === 'completed'
                };
              });
            
            setEnrolledCourses(enrolledCoursesData);
          }
          
          // Process available courses (not enrolled)
          const availableCoursesData = allCourses
            .filter(course => !enrolledCourseIds.includes(course.id))
            .map(course => ({
              id: course.id,
              title: course.title,
              description: course.description || '',
              creator_id: course.creator_id,
              creator_name: creatorMap.get(course.creator_id) || ''
            }));
          
          setAvailableCourses(availableCoursesData);
        }
      } catch (error) {
        console.error("Error fetching courses:", error);
        toast({
          title: t('common.error'),
          description: String(error),
          variant: "destructive",
        });
      } finally {
        setIsLoading(false);
      }
    };
    
    fetchCourses();
  }, [session?.user?.id, toast, t]);
  
  const handleEnrollCourse = async (courseId: number) => {
    if (!session?.user?.id) return;
    
    try {
      // Create enrollment
      await fine.table("enrollments").insert({
        user_id: Number(session.user.id),
        course_id: courseId,
        progress: 0,
        completion_status: 'in_progress'
      });
      
      // Move course from available to enrolled
      const course = availableCourses.find(c => c.id === courseId);
      if (course) {
        setEnrolledCourses([...enrolledCourses, {
          id: course.id,
          title: course.title,
          description: course.description,
          progress: 0,
          isCompleted: false
        }]);
        
        setAvailableCourses(availableCourses.filter(c => c.id !== courseId));
      }
      
      toast({
        title: t('courses.enrollSuccess'),
        description: t('courses.enrollSuccessDesc'),
      });
    } catch (error) {
      console.error("Error enrolling in course:", error);
      toast({
        title: t('common.error'),
        description: String(error),
        variant: "destructive",
      });
    }
  };
  
  const handleContinueCourse = (courseId: number) => {
    // Navigate to course details/lessons
    console.log("Continue course:", courseId);
  };
  
  const filteredEnrolledCourses = enrolledCourses.filter(course => 
    course.title.toLowerCase().includes(searchQuery.toLowerCase())
  );
  
  const filteredAvailableCourses = availableCourses.filter(course => 
    course.title.toLowerCase().includes(searchQuery.toLowerCase())
  );
  
  if (isLoading) {
    return (
      <div className="min-h-screen flex flex-col">
        <Header />
        <main className="flex-grow flex items-center justify-center">
          <div className="text-center">
            <Loader2 className="h-8 w-8 animate-spin mx-auto mb-4 text-orange-500" />
            <p>{t('common.loading')}</p>
          </div>
        </main>
        <Footer />
      </div>
    );
  }
  
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow py-8">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-center mb-8">
            <h1 className="text-3xl font-bold">{t('courses.title')}</h1>
            
            <div className="flex flex-col md:flex-row gap-4 mt-4 md:mt-0 w-full md:w-auto">
              <div className="relative w-full md:w-64">
                <Search className={cn(
                  "absolute top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4",
                  isRtl ? "right-3" : "left-3"
                )} />
                <Input
                  placeholder={t('courses.search')}
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className={cn(
                    "pl-10",
                    isRtl && "pr-10 pl-3"
                  )}
                />
              </div>
              
              <Button className="bg-orange-500 hover:bg-orange-600">
                <Plus className="h-4 w-4 mr-2" />
                {t('courses.create')}
              </Button>
            </div>
          </div>
          
          <Tabs defaultValue="enrolled" className="w-full">
            <TabsList className="mb-6">
              <TabsTrigger value="enrolled">{t('courses.myCourses')}</TabsTrigger>
              <TabsTrigger value="available">{t('courses.availableCourses')}</TabsTrigger>
            </TabsList>
            
            <TabsContent value="enrolled">
              {filteredEnrolledCourses.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  {filteredEnrolledCourses.map((course) => (
                    <CourseCard
                      key={course.id}
                      id={course.id}
                      title={course.title}
                      description={course.description}
                      progress={course.progress}
                      isEnrolled={true}
                      isCompleted={course.isCompleted}
                      onContinue={handleContinueCourse}
                    />
                  ))}
                </div>
              ) : (
                <div className="text-center py-12">
                  <p className="text-gray-500 mb-4">
                    {searchQuery ? t('courses.noSearchResults') : t('courses.noEnrolled')}
                  </p>
                  <Button 
                    variant="outline" 
                    onClick={() => setSearchQuery('')}
                    className="mt-2"
                  >
                    {t('courses.browseAvailable')}
                  </Button>
                </div>
              )}
            </TabsContent>
            
            <TabsContent value="available">
              {filteredAvailableCourses.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  {filteredAvailableCourses.map((course) => (
                    <CourseCard
                      key={course.id}
                      id={course.id}
                      title={course.title}
                      description={course.description}
                      onEnroll={handleEnrollCourse}
                    />
                  ))}
                </div>
              ) : (
                <div className="text-center py-12">
                  <p className="text-gray-500">
                    {searchQuery ? t('courses.noSearchResults') : t('courses.noAvailable')}
                  </p>
                  {searchQuery && (
                    <Button 
                      variant="outline" 
                      onClick={() => setSearchQuery('')}
                      className="mt-4"
                    >
                      {t('courses.clearSearch')}
                    </Button>
                  )}
                </div>
              )}
            </TabsContent>
          </Tabs>
        </div>
      </main>
      
      <Footer />
    </div>
  );
};

const Courses = () => {
  return <ProtectedRoute Component={CoursesContent} />;
};

export default Courses;
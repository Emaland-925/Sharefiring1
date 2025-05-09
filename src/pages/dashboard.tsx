import React, { useEffect, useState } from 'react';
import { Link, Navigate } from 'react-router-dom';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { PointsBadge } from '@/components/points-badge';
import { CourseCard } from '@/components/course-card';
import { LeaderboardItem } from '@/components/leaderboard-item';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { fine } from '@/lib/fine';
import { cn } from '@/lib/utils';
import { ProtectedRoute } from '@/components/auth/route-components';
import { Loader2 } from 'lucide-react';
import type { Schema } from '@/lib/db-types';

const DashboardContent = () => {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  const { data: session } = fine.auth.useSession();
  
  const [isLoading, setIsLoading] = useState(true);
  const [userData, setUserData] = useState<{
    points: number;
    level: number;
    role: string;
  }>({
    points: 0,
    level: 1,
    role: 'employee'
  });
  
  const [enrolledCourses, setEnrolledCourses] = useState<Array<{
    id: number;
    title: string;
    description: string;
    progress: number;
    isCompleted: boolean;
  }>>([]);
  
  const [leaderboard, setLeaderboard] = useState<Array<{
    id: number;
    name: string;
    points: number;
    level: number;
    image?: string;
  }>>([]);
  
  const [challenges, setChallenges] = useState<Array<{
    id: number;
    title: string;
    description: string;
    deadline: string;
  }>>([]);
  
  useEffect(() => {
    const fetchDashboardData = async () => {
      if (!session?.user?.id) return;
      
      try {
        // Fetch user data
        const users = await fine.table("users").select().eq("id", Number(session.user.id));
        if (users && users.length > 0) {
          const user = users[0];
          setUserData({
            points: user.points || 0,
            level: user.level || 1,
            role: user.role || 'employee'
          });
          
          // If user is admin or company, redirect to appropriate dashboard
          if (user.role === 'admin') {
            return;
          }
          
          if (user.role === 'company') {
            return;
          }
        }
        
        // Fetch enrolled courses
        const enrollments = await fine.table("enrollments")
          .select("course_id, progress, completion_status")
          .eq("user_id", Number(session.user.id));
        
        if (enrollments && enrollments.length > 0) {
          const courseIds = enrollments.map(enrollment => enrollment.course_id);
          const courses = await fine.table("courses")
            .select("id, title, description")
            .in("id", courseIds);
          
          if (courses && courses.length > 0) {
            const mappedCourses = courses.map(course => {
              const enrollment = enrollments.find(e => e.course_id === course.id);
              return {
                id: course.id,
                title: course.title,
                description: course.description || '',
                progress: enrollment?.progress || 0,
                isCompleted: enrollment?.completion_status === 'completed'
              };
            });
            
            setEnrolledCourses(mappedCourses);
          }
        }
        
        // Fetch leaderboard
        const topUsers = await fine.table("users")
          .select("id, name, points, level, profile_image")
          .order("points", { ascending: false })
          .limit(5);
        
        if (topUsers) {
          setLeaderboard(topUsers.map(user => ({
            id: user.id,
            name: user.name,
            points: user.points || 0,
            level: user.level || 1,
            image: user.profile_image
          })));
        }
        
        // Fetch challenges
        const activeChallenges = await fine.table("challenges")
          .select("id, title, description, deadline")
          .limit(3);
        
        if (activeChallenges) {
          setChallenges(activeChallenges);
        }
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      } finally {
        setIsLoading(false);
      }
    };
    
    fetchDashboardData();
  }, [session?.user?.id]);
  
  const handleContinueCourse = (courseId: number) => {
    // Navigate to course details/lessons
    console.log("Continue course:", courseId);
  };
  
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
  
  // Redirect based on role
  if (userData.role === 'admin') {
    return <Navigate to="/admin-panel" />;
  }
  
  if (userData.role === 'company') {
    return <Navigate to="/company-dashboard" />;
  }
  
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow py-8">
        <div className="container mx-auto px-4">
          <div className={cn(
            "flex flex-col md:flex-row items-start gap-6 mb-8",
            isRtl ? "md:flex-row-reverse" : ""
          )}>
            <div className="w-full md:w-1/3">
              <h1 className="text-2xl font-bold mb-4">
                {t('dashboard.welcome')}, {session?.user?.name}
              </h1>
              <PointsBadge points={userData.points} level={userData.level} />
            </div>
            
            <div className="w-full md:w-2/3">
              <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                  <CardTitle>{t('dashboard.courses')}</CardTitle>
                  <Link to="/courses">
                    <Button variant="ghost" size="sm">
                      {t('dashboard.viewAll')}
                    </Button>
                  </Link>
                </CardHeader>
                <CardContent>
                  {enrolledCourses.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {enrolledCourses.slice(0, 4).map((course) => (
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
                    <div className="text-center py-8">
                      <p className="text-gray-500 mb-4">{t('courses.noCourses')}</p>
                      <Link to="/courses">
                        <Button className="bg-orange-500 hover:bg-orange-600">
                          {t('courses.browse')}
                        </Button>
                      </Link>
                    </div>
                  )}
                </CardContent>
              </Card>
            </div>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Card>
              <CardHeader className="flex flex-row items-center justify-between">
                <CardTitle>{t('dashboard.leaderboard')}</CardTitle>
                <Link to="/leaderboard">
                  <Button variant="ghost" size="sm">
                    {t('dashboard.viewAll')}
                  </Button>
                </Link>
              </CardHeader>
              <CardContent>
                <div className="space-y-2">
                  {leaderboard.map((user, index) => (
                    <LeaderboardItem
                      key={user.id}
                      rank={index + 1}
                      name={user.name}
                      points={user.points}
                      level={user.level}
                      image={user.image}
                      isCurrentUser={user.id === Number(session?.user?.id)}
                    />
                  ))}
                </div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader className="flex flex-row items-center justify-between">
                <CardTitle>{t('dashboard.challenges')}</CardTitle>
                <Link to="/challenges">
                  <Button variant="ghost" size="sm">
                    {t('dashboard.viewAll')}
                  </Button>
                </Link>
              </CardHeader>
              <CardContent>
                {challenges.length > 0 ? (
                  <div className="space-y-4">
                    {challenges.map((challenge) => (
                      <div 
                        key={challenge.id}
                        className={cn(
                          "p-4 border rounded-lg",
                          isRtl ? "text-right" : "text-left"
                        )}
                      >
                        <h3 className="font-semibold">{challenge.title}</h3>
                        <p className="text-sm text-gray-500 mt-1">{challenge.description}</p>
                        {challenge.deadline && (
                          <div className="mt-2 text-sm">
                            <span className="font-medium">Deadline:</span>{' '}
                            {new Date(challenge.deadline).toLocaleDateString()}
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="text-center py-8">
                    <p className="text-gray-500">{t('challenges.noActive')}</p>
                  </div>
                )}
              </CardContent>
            </Card>
          </div>
        </div>
      </main>
      
      <Footer />
    </div>
  );
};

const Dashboard = () => {
  return <ProtectedRoute Component={DashboardContent} />;
};

export default Dashboard;
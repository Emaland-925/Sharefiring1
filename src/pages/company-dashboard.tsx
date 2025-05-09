import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { CompanyDashboard as CompanyDashboardComponent } from '@/components/company-dashboard';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { fine } from '@/lib/fine';
import { cn } from '@/lib/utils';
import { ProtectedRoute } from '@/components/auth/route-components';
import { Loader2, UserPlus, BookOpen, Gift } from 'lucide-react';
import { LeaderboardItem } from '@/components/leaderboard-item';
import { CourseCard } from '@/components/course-card';

const CompanyDashboardContent = () => {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  const { data: session } = fine.auth.useSession();
  
  const [isLoading, setIsLoading] = useState(true);
  const [companyData, setCompanyData] = useState<{
    id: number;
    name: string;
    description: string;
  }>({
    id: 0,
    name: '',
    description: ''
  });
  
  const [dashboardStats, setDashboardStats] = useState({
    totalEmployees: 0,
    activeCourses: 0,
    completedCourses: 0,
    averageProgress: 0
  });
  
  const [topEmployees, setTopEmployees] = useState<Array<{
    id: number;
    name: string;
    points: number;
    level: number;
    image?: string;
  }>>([]);
  
  const [courses, setCourses] = useState<Array<{
    id: number;
    title: string;
    description: string;
    status: string;
  }>>([]);
  
  useEffect(() => {
    const fetchCompanyData = async () => {
      if (!session?.user?.id) return;
      
      try {
        // Fetch company data
        const companies = await fine.table("companies").select().eq("admin_id", Number(session.user.id));
        
        if (companies && companies.length > 0) {
          const company = companies[0];
          setCompanyData({
            id: company.id,
            name: company.name,
            description: company.description || ''
          });
          
          // Fetch employees
          const employees = await fine.table("users").select().eq("company_id", company.id);
          const employeeCount = employees?.length || 0;
          
          // Fetch courses
          const allCourses = await fine.table("courses").select().eq("company_id", company.id);
          const courseCount = allCourses?.length || 0;
          
          // Fetch enrollments
          const enrollments = await fine.table("enrollments")
            .select("course_id, progress, completion_status")
            .in("course_id", allCourses?.map(c => c.id) || []);
          
          const completedCourses = enrollments?.filter(e => e.completion_status === 'completed').length || 0;
          
          // Calculate average progress
          const totalProgress = enrollments?.reduce((sum, e) => sum + (e.progress || 0), 0) || 0;
          const avgProgress = enrollments?.length ? Math.round(totalProgress / enrollments.length) : 0;
          
          setDashboardStats({
            totalEmployees: employeeCount,
            activeCourses: courseCount,
            completedCourses,
            averageProgress: avgProgress
          });
          
          // Get top employees
          if (employees && employees.length > 0) {
            const sortedEmployees = [...employees].sort((a, b) => (b.points || 0) - (a.points || 0));
            setTopEmployees(sortedEmployees.slice(0, 5).map(employee => ({
              id: employee.id,
              name: employee.name,
              points: employee.points || 0,
              level: employee.level || 1,
              image: employee.profile_image
            })));
          }
          
          // Get courses
          if (allCourses && allCourses.length > 0) {
            setCourses(allCourses.map(course => ({
              id: course.id,
              title: course.title,
              description: course.description || '',
              status: course.status || 'pending'
            })));
          }
        }
      } catch (error) {
        console.error("Error fetching company data:", error);
      } finally {
        setIsLoading(false);
      }
    };
    
    fetchCompanyData();
  }, [session?.user?.id]);
  
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
          <CompanyDashboardComponent
            companyName={companyData.name}
            totalEmployees={dashboardStats.totalEmployees}
            activeCourses={dashboardStats.activeCourses}
            completedCourses={dashboardStats.completedCourses}
            averageProgress={dashboardStats.averageProgress}
          />
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div className="md:col-span-1">
              <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                  <CardTitle>{t('company.dashboard.topPerformers')}</CardTitle>
                  <Link to="/leaderboard">
                    <Button variant="ghost" size="sm">
                      {t('dashboard.viewAll')}
                    </Button>
                  </Link>
                </CardHeader>
                <CardContent>
                  {topEmployees.length > 0 ? (
                    <div className="space-y-2">
                      {topEmployees.map((employee, index) => (
                        <LeaderboardItem
                          key={employee.id}
                          rank={index + 1}
                          name={employee.name}
                          points={employee.points}
                          level={employee.level}
                          image={employee.image}
                        />
                      ))}
                    </div>
                  ) : (
                    <div className="text-center py-4">
                      <p className="text-gray-500">{t('company.dashboard.noData')}</p>
                    </div>
                  )}
                </CardContent>
              </Card>
              
              <div className="mt-6 space-y-4">
                <Button className="w-full bg-orange-500 hover:bg-orange-600">
                  <UserPlus className="mr-2 h-4 w-4" />
                  {t('company.dashboard.addEmployee')}
                </Button>
                
                <Button className="w-full bg-orange-500 hover:bg-orange-600">
                  <BookOpen className="mr-2 h-4 w-4" />
                  {t('company.dashboard.createCourse')}
                </Button>
                
                <Button className="w-full bg-orange-500 hover:bg-orange-600">
                  <Gift className="mr-2 h-4 w-4" />
                  {t('company.dashboard.manageRewards')}
                </Button>
              </div>
            </div>
            
            <div className="md:col-span-2">
              <Tabs defaultValue="courses">
                <TabsList className="mb-6">
                  <TabsTrigger value="courses">{t('courses.title')}</TabsTrigger>
                  <TabsTrigger value="employees">{t('company.dashboard.totalEmployees')}</TabsTrigger>
                </TabsList>
                
                <TabsContent value="courses">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {courses.length > 0 ? (
                      courses.map(course => (
                        <CourseCard
                          key={course.id}
                          id={course.id}
                          title={course.title}
                          description={course.description}
                        />
                      ))
                    ) : (
                      <div className="col-span-2 text-center py-8">
                        <p className="text-gray-500 mb-4">{t('courses.noAvailable')}</p>
                        <Button className="bg-orange-500 hover:bg-orange-600">
                          {t('courses.create')}
                        </Button>
                      </div>
                    )}
                  </div>
                </TabsContent>
                
                <TabsContent value="employees">
                  <Card>
                    <CardHeader>
                      <CardTitle>{t('company.dashboard.totalEmployees')}</CardTitle>
                    </CardHeader>
                    <CardContent className={cn(
                      isRtl ? "text-right" : "text-left"
                    )}>
                      {topEmployees.length > 0 ? (
                        <div className="space-y-2">
                          {topEmployees.map((employee) => (
                            <div 
                              key={employee.id}
                              className="flex items-center justify-between p-3 border rounded-lg"
                            >
                              <div className="flex items-center">
                                <div className="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                  {employee.name.charAt(0).toUpperCase()}
                                </div>
                                <span>{employee.name}</span>
                              </div>
                              <div className="text-sm text-gray-500">
                                {t('dashboard.level')} {employee.level}
                              </div>
                            </div>
                          ))}
                        </div>
                      ) : (
                        <div className="text-center py-4">
                          <p className="text-gray-500">{t('company.dashboard.noData')}</p>
                        </div>
                      )}
                    </CardContent>
                  </Card>
                </TabsContent>
              </Tabs>
            </div>
          </div>
        </div>
      </main>
      
      <Footer />
    </div>
  );
};

const CompanyDashboardPage = () => {
  return <ProtectedRoute Component={CompanyDashboardContent} />;
};

export default CompanyDashboardPage;
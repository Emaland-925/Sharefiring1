import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';
import { BarChart, Users, BookOpen, Award } from 'lucide-react';

interface CompanyDashboardProps {
  companyName: string;
  totalEmployees: number;
  activeCourses: number;
  completedCourses: number;
  averageProgress: number;
}

export function CompanyDashboard({
  companyName,
  totalEmployees,
  activeCourses,
  completedCourses,
  averageProgress
}: CompanyDashboardProps) {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  
  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-bold">{t('company.dashboard.title')}: {companyName}</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('company.dashboard.totalEmployees')}
            </CardTitle>
            <Users className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{totalEmployees}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('company.dashboard.activeCourses')}
            </CardTitle>
            <BookOpen className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{activeCourses}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('company.dashboard.completedCourses')}
            </CardTitle>
            <Award className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{completedCourses}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('company.dashboard.averageProgress')}
            </CardTitle>
            <BarChart className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{averageProgress}%</div>
          </CardContent>
        </Card>
      </div>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card className={cn(
          "col-span-1",
          isRtl ? "text-right" : "text-left"
        )}>
          <CardHeader>
            <CardTitle>{t('company.dashboard.topPerformers')}</CardTitle>
          </CardHeader>
          <CardContent>
            {/* Top performers content would go here */}
            <p className="text-muted-foreground">{t('company.dashboard.noData')}</p>
          </CardContent>
        </Card>
        
        <Card className={cn(
          "col-span-1",
          isRtl ? "text-right" : "text-left"
        )}>
          <CardHeader>
            <CardTitle>{t('company.dashboard.courseCompletion')}</CardTitle>
          </CardHeader>
          <CardContent>
            {/* Course completion chart would go here */}
            <p className="text-muted-foreground">{t('company.dashboard.noData')}</p>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
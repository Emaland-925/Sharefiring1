import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';
import { 
  Users, 
  Building2, 
  BookOpen, 
  Award, 
  Flag,
  Settings
} from 'lucide-react';

interface AdminPanelProps {
  totalUsers: number;
  totalCompanies: number;
  totalCourses: number;
  pendingApprovals: number;
}

export function AdminPanel({
  totalUsers,
  totalCompanies,
  totalCourses,
  pendingApprovals
}: AdminPanelProps) {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  
  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-bold">{t('admin.panel.title')}</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('admin.panel.totalUsers')}
            </CardTitle>
            <Users className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{totalUsers}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('admin.panel.totalCompanies')}
            </CardTitle>
            <Building2 className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{totalCompanies}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('admin.panel.totalCourses')}
            </CardTitle>
            <BookOpen className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{totalCourses}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium">
              {t('admin.panel.pendingApprovals')}
            </CardTitle>
            <Flag className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{pendingApprovals}</div>
          </CardContent>
        </Card>
      </div>
      
      <Tabs defaultValue="users">
        <TabsList>
          <TabsTrigger value="users">
            <Users className="h-4 w-4 mr-2" />
            {t('admin.panel.users')}
          </TabsTrigger>
          <TabsTrigger value="companies">
            <Building2 className="h-4 w-4 mr-2" />
            {t('admin.panel.companies')}
          </TabsTrigger>
          <TabsTrigger value="courses">
            <BookOpen className="h-4 w-4 mr-2" />
            {t('admin.panel.courses')}
          </TabsTrigger>
          <TabsTrigger value="approvals">
            <Flag className="h-4 w-4 mr-2" />
            {t('admin.panel.approvals')}
          </TabsTrigger>
          <TabsTrigger value="settings">
            <Settings className="h-4 w-4 mr-2" />
            {t('admin.panel.settings')}
          </TabsTrigger>
        </TabsList>
        
        <TabsContent value="users" className="mt-6">
          <Card>
            <CardHeader>
              <CardTitle>{t('admin.panel.userManagement')}</CardTitle>
            </CardHeader>
            <CardContent className={cn(
              isRtl ? "text-right" : "text-left"
            )}>
              <p className="text-muted-foreground mb-4">{t('admin.panel.userManagementDesc')}</p>
              <Button className="bg-orange-500 hover:bg-orange-600">
                {t('admin.panel.addUser')}
              </Button>
            </CardContent>
          </Card>
        </TabsContent>
        
        <TabsContent value="companies" className="mt-6">
          <Card>
            <CardHeader>
              <CardTitle>{t('admin.panel.companyManagement')}</CardTitle>
            </CardHeader>
            <CardContent className={cn(
              isRtl ? "text-right" : "text-left"
            )}>
              <p className="text-muted-foreground mb-4">{t('admin.panel.companyManagementDesc')}</p>
              <Button className="bg-orange-500 hover:bg-orange-600">
                {t('admin.panel.addCompany')}
              </Button>
            </CardContent>
          </Card>
        </TabsContent>
        
        <TabsContent value="courses" className="mt-6">
          <Card>
            <CardHeader>
              <CardTitle>{t('admin.panel.courseManagement')}</CardTitle>
            </CardHeader>
            <CardContent className={cn(
              isRtl ? "text-right" : "text-left"
            )}>
              <p className="text-muted-foreground mb-4">{t('admin.panel.courseManagementDesc')}</p>
              <Button className="bg-orange-500 hover:bg-orange-600">
                {t('admin.panel.viewAllCourses')}
              </Button>
            </CardContent>
          </Card>
        </TabsContent>
        
        <TabsContent value="approvals" className="mt-6">
          <Card>
            <CardHeader>
              <CardTitle>{t('admin.panel.pendingApprovals')}</CardTitle>
            </CardHeader>
            <CardContent className={cn(
              isRtl ? "text-right" : "text-left"
            )}>
              <p className="text-muted-foreground mb-4">{t('admin.panel.approvalsDesc')}</p>
              <Button className="bg-orange-500 hover:bg-orange-600">
                {t('admin.panel.reviewApprovals')}
              </Button>
            </CardContent>
          </Card>
        </TabsContent>
        
        <TabsContent value="settings" className="mt-6">
          <Card>
            <CardHeader>
              <CardTitle>{t('admin.panel.systemSettings')}</CardTitle>
            </CardHeader>
            <CardContent className={cn(
              isRtl ? "text-right" : "text-left"
            )}>
              <p className="text-muted-foreground mb-4">{t('admin.panel.settingsDesc')}</p>
              <Button className="bg-orange-500 hover:bg-orange-600">
                {t('admin.panel.configureSettings')}
              </Button>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
    </div>
  );
}
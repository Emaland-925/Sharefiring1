import React, { useEffect, useState } from 'react';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { AdminPanel as AdminPanelComponent } from '@/components/admin-panel';
import { fine } from '@/lib/fine';
import { ProtectedRoute } from '@/components/auth/route-components';
import { Loader2 } from 'lucide-react';

const AdminPanelContent = () => {
  const { t } = useLanguage();
  const { data: session } = fine.auth.useSession();
  
  const [isLoading, setIsLoading] = useState(true);
  const [adminStats, setAdminStats] = useState({
    totalUsers: 0,
    totalCompanies: 0,
    totalCourses: 0,
    pendingApprovals: 0
  });
  
  useEffect(() => {
    const fetchAdminData = async () => {
      if (!session?.user?.id) return;
      
      try {
        // Check if user is admin
        const users = await fine.table("users").select().eq("id", Number(session.user.id));
        
        if (users && users.length > 0 && users[0].role === 'admin') {
          // Fetch total users
          const allUsers = await fine.table("users").select("id");
          const userCount = allUsers?.length || 0;
          
          // Fetch total companies
          const companies = await fine.table("companies").select("id");
          const companyCount = companies?.length || 0;
          
          // Fetch total courses
          const courses = await fine.table("courses").select("id");
          const courseCount = courses?.length || 0;
          
          // Fetch pending approvals
          const pendingCourses = await fine.table("courses").select("id").eq("status", "pending");
          const pendingCount = pendingCourses?.length || 0;
          
          setAdminStats({
            totalUsers: userCount,
            totalCompanies: companyCount,
            totalCourses: courseCount,
            pendingApprovals: pendingCount
          });
        }
      } catch (error) {
        console.error("Error fetching admin data:", error);
      } finally {
        setIsLoading(false);
      }
    };
    
    fetchAdminData();
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
          <AdminPanelComponent
            totalUsers={adminStats.totalUsers}
            totalCompanies={adminStats.totalCompanies}
            totalCourses={adminStats.totalCourses}
            pendingApprovals={adminStats.pendingApprovals}
          />
        </div>
      </main>
      
      <Footer />
    </div>
  );
};

const AdminPanel = () => {
  return <ProtectedRoute Component={AdminPanelContent} />;
};

export default AdminPanel;
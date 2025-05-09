import React, { useEffect, useState } from 'react';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { ProfileCard } from '@/components/profile-card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { fine } from '@/lib/fine';
import { cn } from '@/lib/utils';
import { ProtectedRoute } from '@/components/auth/route-components';
import { Loader2, Save } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';

const ProfileContent = () => {
  const { t, language, setLanguage } = useLanguage();
  const { toast } = useToast();
  const isRtl = language === 'ar';
  const { data: session } = fine.auth.useSession();
  
  const [isLoading, setIsLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);
  
  const [userData, setUserData] = useState({
    name: '',
    email: '',
    points: 0,
    level: 1,
    profile_image: '',
    language_preference: language as 'en' | 'ar',
    role: '',
    company: ''
  });
  
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    language_preference: language as 'en' | 'ar'
  });
  
  useEffect(() => {
    const fetchUserData = async () => {
      if (!session?.user?.id) return;
      
      try {
        const users = await fine.table("users").select().eq("id", Number(session.user.id));
        if (users && users.length > 0) {
          const user = users[0];
          const userLangPref = (user.language_preference || language) as 'en' | 'ar';
          
          let companyName = '';
          if (user.company_id) {
            const companies = await fine.table("companies").select().eq("id", user.company_id);
            if (companies && companies.length > 0) {
              companyName = companies[0].name;
            }
          } else if (user.role === 'company') {
            const companies = await fine.table("companies").select().eq("admin_id", user.id);
            if (companies && companies.length > 0) {
              companyName = companies[0].name;
            }
          }
          
          const userData = {
            name: user.name,
            email: user.email,
            points: user.points || 0,
            level: user.level || 1,
            profile_image: user.profile_image || '',
            language_preference: userLangPref,
            role: user.role || 'employee',
            company: companyName
          };
          
          setUserData(userData);
          setFormData({
            name: userData.name,
            email: userData.email,
            language_preference: userLangPref
          });
          
          // Set language based on user preference
          if (userLangPref && userLangPref !== language) {
            setLanguage(userLangPref);
          }
        }
      } catch (error) {
        console.error("Error fetching user data:", error);
        toast({
          title: t('common.error'),
          description: String(error),
          variant: "destructive",
        });
      } finally {
        setIsLoading(false);
      }
    };
    
    fetchUserData();
  }, [session?.user?.id, language, setLanguage, toast, t]);
  
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };
  
  const handleLanguageChange = (value: string) => {
    if (value === 'en' || value === 'ar') {
      setFormData(prev => ({ ...prev, language_preference: value as 'en' | 'ar' }));
    }
  };
  
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!session?.user?.id) return;
    
    setIsSaving(true);
    
    try {
      await fine.table("users").update({
        name: formData.name,
        language_preference: formData.language_preference
      }).eq("id", Number(session.user.id));
      
      // Update local state
      setUserData(prev => ({
        ...prev,
        name: formData.name,
        language_preference: formData.language_preference
      }));
      
      // Update language if changed
      if (formData.language_preference !== language) {
        setLanguage(formData.language_preference);
      }
      
      toast({
        title: t('profile.saveSuccess'),
        description: t('profile.saveSuccessDesc'),
      });
    } catch (error) {
      console.error("Error updating profile:", error);
      toast({
        title: t('common.error'),
        description: String(error),
        variant: "destructive",
      });
    } finally {
      setIsSaving(false);
    }
  };
  
  const getRoleTranslation = (role: string) => {
    switch (role) {
      case 'admin':
        return t('auth.roleAdmin');
      case 'company':
        return t('auth.roleCompany');
      case 'employee':
      default:
        return t('auth.roleEmployee');
    }
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
  
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow py-8">
        <div className="container mx-auto px-4">
          <h1 className="text-3xl font-bold mb-8">{t('nav.profile')}</h1>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="md:col-span-1">
              <ProfileCard
                name={userData.name}
                email={userData.email}
                points={userData.points}
                level={userData.level}
                image={userData.profile_image}
              />
              
              <Card className="mt-6">
                <CardHeader>
                  <CardTitle>{t('profile.role')}</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div>
                      <Label>{t('profile.role')}</Label>
                      <div className="mt-1 p-2 bg-gray-50 rounded-md">
                        {getRoleTranslation(userData.role)}
                      </div>
                    </div>
                    
                    {userData.company && (
                      <div>
                        <Label>{t('profile.company')}</Label>
                        <div className="mt-1 p-2 bg-gray-50 rounded-md">
                          {userData.company}
                        </div>
                      </div>
                    )}
                  </div>
                </CardContent>
              </Card>
            </div>
            
            <div className="md:col-span-2">
              <Tabs defaultValue="settings">
                <TabsList className="mb-6">
                  <TabsTrigger value="settings">{t('profile.settings')}</TabsTrigger>
                  <TabsTrigger value="achievements">{t('profile.achievements')}</TabsTrigger>
                </TabsList>
                
                <TabsContent value="settings">
                  <Card>
                    <CardHeader>
                      <CardTitle>{t('profile.personalInfo')}</CardTitle>
                    </CardHeader>
                    <CardContent>
                      <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="space-y-2">
                          <Label htmlFor="name">{t('auth.name')}</Label>
                          <Input
                            id="name"
                            name="name"
                            value={formData.name}
                            onChange={handleInputChange}
                          />
                        </div>
                        
                        <div className="space-y-2">
                          <Label htmlFor="email">{t('auth.email')}</Label>
                          <Input
                            id="email"
                            name="email"
                            value={formData.email}
                            disabled
                            className="bg-gray-50"
                          />
                          <p className="text-sm text-gray-500">{t('profile.emailReadonly')}</p>
                        </div>
                        
                        <div className="space-y-2">
                          <Label htmlFor="language">{t('profile.language')}</Label>
                          <Select
                            value={formData.language_preference}
                            onValueChange={handleLanguageChange}
                          >
                            <SelectTrigger id="language">
                              <SelectValue placeholder={t('profile.selectLanguage')} />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem value="en">English</SelectItem>
                              <SelectItem value="ar">العربية</SelectItem>
                            </SelectContent>
                          </Select>
                        </div>
                        
                        <Button 
                          type="submit" 
                          className="bg-orange-500 hover:bg-orange-600"
                          disabled={isSaving}
                        >
                          {isSaving ? (
                            <>
                              <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                              {t('profile.saving')}
                            </>
                          ) : (
                            <>
                              <Save className="mr-2 h-4 w-4" />
                              {t('common.save')}
                            </>
                          )}
                        </Button>
                      </form>
                    </CardContent>
                  </Card>
                </TabsContent>
                
                <TabsContent value="achievements">
                  <Card>
                    <CardHeader>
                      <CardTitle>{t('profile.achievements')}</CardTitle>
                    </CardHeader>
                    <CardContent>
                      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="bg-orange-50 p-4 rounded-lg">
                          <h3 className="font-semibold mb-2">{t('profile.totalPoints')}</h3>
                          <p className="text-3xl font-bold text-orange-500">{userData.points}</p>
                        </div>
                        
                        <div className="bg-blue-50 p-4 rounded-lg">
                          <h3 className="font-semibold mb-2">{t('profile.currentLevel')}</h3>
                          <p className="text-3xl font-bold text-blue-500">{userData.level}</p>
                        </div>
                      </div>
                      
                      <div className="mt-6">
                        <h3 className="font-semibold mb-4">{t('profile.badges')}</h3>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                          {/* Placeholder for badges */}
                          <div className="bg-gray-100 p-4 rounded-lg text-center">
                            <div className="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-2"></div>
                            <p className="text-sm font-medium">Badge 1</p>
                          </div>
                          <div className="bg-gray-100 p-4 rounded-lg text-center">
                            <div className="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-2"></div>
                            <p className="text-sm font-medium">Badge 2</p>
                          </div>
                        </div>
                      </div>
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

const Profile = () => {
  return <ProtectedRoute Component={ProfileContent} />;
};

export default Profile;
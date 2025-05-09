import type React from "react";
import { useState } from "react";
import { useNavigate, Link, Navigate } from "react-router-dom";
import { fine } from "@/lib/fine";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/hooks/use-toast";
import { Loader2 } from "lucide-react";
import { useLanguage } from "@/lib/language-context";
import { Header } from "@/components/layout/header";
import { Footer } from "@/components/layout/footer";
import { cn } from "@/lib/utils";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";

export default function SignupForm() {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
    name: "",
    role: "",
    companyName: "",
    companyDescription: ""
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const navigate = useNavigate();
  const { toast } = useToast();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));

    // Clear error when user types
    if (errors[name]) {
      setErrors((prev) => {
        const newErrors = { ...prev };
        delete newErrors[name];
        return newErrors;
      });
    }
  };

  const handleRoleChange = (value: string) => {
    setFormData((prev) => ({ ...prev, role: value }));
    
    // Clear error when user selects a role
    if (errors.role) {
      setErrors((prev) => {
        const newErrors = { ...prev };
        delete newErrors.role;
        return newErrors;
      });
    }
  };

  const validateForm = () => {
    const newErrors: Record<string, string> = {};

    if (!formData.email) {
      newErrors.email = t('auth.emailRequired');
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = t('auth.emailInvalid');
    }

    if (!formData.password) {
      newErrors.password = t('auth.passwordRequired');
    } else if (formData.password.length < 8) {
      newErrors.password = t('auth.passwordLength');
    }

    if (!formData.name) {
      newErrors.name = t('auth.nameRequired');
    }
    
    if (!formData.role) {
      newErrors.role = t('auth.roleRequired');
    }
    
    if (formData.role === 'company' && !formData.companyName) {
      newErrors.companyName = t('auth.companyNameRequired');
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!validateForm()) return;

    setIsLoading(true);

    try {
      // First, create the user account
      const { data, error } = await fine.auth.signUp.email(
        {
          email: formData.email,
          password: formData.password,
          name: formData.name,
          callbackURL: "/dashboard",
        },
        {
          onRequest: () => {
            setIsLoading(true);
          },
          onSuccess: async () => {
            // If user is a company, create the company record
            if (formData.role === 'company') {
              try {
                // Get the newly created user
                const users = await fine.table("users").select().eq("email", formData.email);
                
                if (users && users.length > 0) {
                  const userId = users[0].id;
                  
                  // Update user role
                  await fine.table("users").update({
                    role: formData.role
                  }).eq("id", userId);
                  
                  // Create company
                  await fine.table("companies").insert({
                    name: formData.companyName,
                    description: formData.companyDescription,
                    admin_id: userId
                  });
                }
              } catch (error) {
                console.error("Error creating company:", error);
              }
            } else {
              // Update user role for non-company users
              try {
                const users = await fine.table("users").select().eq("email", formData.email);
                
                if (users && users.length > 0) {
                  const userId = users[0].id;
                  
                  await fine.table("users").update({
                    role: formData.role
                  }).eq("id", userId);
                }
              } catch (error) {
                console.error("Error updating user role:", error);
              }
            }
            
            toast({
              title: t('auth.signupSuccess'),
              description: t('auth.signupSuccessDesc'),
            });
            navigate("/login");
          },
          onError: (ctx) => {
            toast({
              title: t('common.error'),
              description: ctx.error.message,
              variant: "destructive",
            });
          },
        }
      );

      if (error) {
        throw error;
      }
    } catch (error: any) {
      toast({
        title: t('common.error'),
        description: error.message || t('auth.signupError'),
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  if (!fine) return <Navigate to='/' />;
  const { isPending, data } = fine.auth.useSession();
  if (!isPending && data) return <Navigate to='/dashboard' />;

  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <div className='container mx-auto flex flex-grow items-center justify-center py-10'>
        <Card className={cn(
          'mx-auto w-full max-w-md',
          isRtl ? "text-right" : "text-left"
        )}>
          <CardHeader>
            <CardTitle className='text-2xl'>{t('auth.signup')}</CardTitle>
            <CardDescription>{t('auth.signupDesc')}</CardDescription>
          </CardHeader>
          <form onSubmit={handleSubmit}>
            <CardContent className='space-y-4'>
              <div className='space-y-2'>
                <Label htmlFor='name'>{t('auth.name')}</Label>
                <Input
                  id='name'
                  name='name'
                  placeholder='John Doe'
                  value={formData.name}
                  onChange={handleChange}
                  disabled={isLoading}
                  aria-invalid={!!errors.name}
                  className={cn(isRtl && "text-right")}
                />
                {errors.name && <p className='text-sm text-destructive'>{errors.name}</p>}
              </div>

              <div className='space-y-2'>
                <Label htmlFor='email'>{t('auth.email')}</Label>
                <Input
                  id='email'
                  name='email'
                  type='email'
                  placeholder='john@example.com'
                  value={formData.email}
                  onChange={handleChange}
                  disabled={isLoading}
                  aria-invalid={!!errors.email}
                  className={cn(isRtl && "text-right")}
                />
                {errors.email && <p className='text-sm text-destructive'>{errors.email}</p>}
              </div>

              <div className='space-y-2'>
                <Label htmlFor='password'>{t('auth.password')}</Label>
                <Input
                  id='password'
                  name='password'
                  type='password'
                  value={formData.password}
                  onChange={handleChange}
                  disabled={isLoading}
                  aria-invalid={!!errors.password}
                  className={cn(isRtl && "text-right")}
                />
                {errors.password && <p className='text-sm text-destructive'>{errors.password}</p>}
              </div>
              
              <div className='space-y-2'>
                <Label htmlFor='role'>{t('auth.role')}</Label>
                <Select
                  value={formData.role}
                  onValueChange={handleRoleChange}
                  disabled={isLoading}
                >
                  <SelectTrigger id="role" className={cn(isRtl && "text-right")}>
                    <SelectValue placeholder={t('profile.selectLanguage')} />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="employee">{t('auth.roleEmployee')}</SelectItem>
                    <SelectItem value="company">{t('auth.roleCompany')}</SelectItem>
                    <SelectItem value="admin">{t('auth.roleAdmin')}</SelectItem>
                  </SelectContent>
                </Select>
                {errors.role && <p className='text-sm text-destructive'>{errors.role}</p>}
              </div>
              
              {formData.role === 'company' && (
                <>
                  <div className='space-y-2'>
                    <Label htmlFor='companyName'>{t('auth.companyName')}</Label>
                    <Input
                      id='companyName'
                      name='companyName'
                      value={formData.companyName}
                      onChange={handleChange}
                      disabled={isLoading}
                      aria-invalid={!!errors.companyName}
                      className={cn(isRtl && "text-right")}
                    />
                    {errors.companyName && <p className='text-sm text-destructive'>{errors.companyName}</p>}
                  </div>
                  
                  <div className='space-y-2'>
                    <Label htmlFor='companyDescription'>{t('auth.companyDescription')}</Label>
                    <Textarea
                      id='companyDescription'
                      name='companyDescription'
                      value={formData.companyDescription}
                      onChange={handleChange}
                      disabled={isLoading}
                      className={cn(isRtl && "text-right")}
                      rows={3}
                    />
                  </div>
                </>
              )}
            </CardContent>

            <CardFooter className='flex flex-col space-y-4'>
              <Button type='submit' className='w-full bg-orange-500 hover:bg-orange-600' disabled={isLoading}>
                {isLoading ? (
                  <>
                    <Loader2 className='mr-2 h-4 w-4 animate-spin' />
                    {t('auth.creatingAccount')}
                  </>
                ) : (
                  t('auth.signup')
                )}
              </Button>

              <p className='text-center text-sm text-muted-foreground'>
                {t('auth.haveAccount')}{" "}
                <Link to='/login' className='text-orange-500 underline underline-offset-4 hover:text-orange-600'>
                  {t('auth.login')}
                </Link>
              </p>
            </CardFooter>
          </form>
        </Card>
      </div>
      
      <Footer />
    </div>
  );
}
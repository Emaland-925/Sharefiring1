import type React from "react";
import { useState } from "react";
import { useNavigate, Link, Navigate } from "react-router-dom";
import { fine } from "@/lib/fine";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import { useToast } from "@/hooks/use-toast";
import { Loader2 } from "lucide-react";
import { useLanguage } from "@/lib/language-context";
import { Header } from "@/components/layout/header";
import { Footer } from "@/components/layout/footer";
import { cn } from "@/lib/utils";

export default function LoginForm() {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
    rememberMe: true,
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const navigate = useNavigate();
  const { toast } = useToast();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
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

  const handleCheckboxChange = (checked: boolean) => {
    setFormData((prev) => ({ ...prev, rememberMe: checked }));
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
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!validateForm()) return;

    setIsLoading(true);

    try {
      const { data, error } = await fine.auth.signIn.email(
        {
          email: formData.email,
          password: formData.password,
          callbackURL: "/dashboard",
          rememberMe: formData.rememberMe,
        },
        {
          onRequest: () => {
            setIsLoading(true);
          },
          onSuccess: () => {
            toast({
              title: t('auth.loginSuccess'),
              description: t('auth.loginSuccessDesc'),
            });
            navigate("/dashboard");
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
        description: error.message || t('auth.invalidCredentials'),
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
            <CardTitle className='text-2xl'>{t('auth.login')}</CardTitle>
            <CardDescription>{t('auth.loginDesc')}</CardDescription>
          </CardHeader>
          <form onSubmit={handleSubmit}>
            <CardContent className='space-y-4'>
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
                <div className={cn(
                  'flex items-center justify-between',
                  isRtl && "flex-row-reverse"
                )}>
                  <Label htmlFor='password'>{t('auth.password')}</Label>
                  <Link to='/forgot-password' className='text-sm text-primary underline-offset-4 hover:underline'>
                    {t('auth.forgotPassword')}
                  </Link>
                </div>
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

              <div className={cn(
                'flex items-center space-x-2',
                isRtl && "flex-row-reverse space-x-reverse"
              )}>
                <Checkbox id='rememberMe' checked={formData.rememberMe} onCheckedChange={handleCheckboxChange} />
                <Label htmlFor='rememberMe' className='text-sm font-normal'>
                  {t('auth.rememberMe')}
                </Label>
              </div>
            </CardContent>

            <CardFooter className='flex flex-col space-y-4'>
              <Button type='submit' className='w-full bg-orange-500 hover:bg-orange-600' disabled={isLoading}>
                {isLoading ? (
                  <>
                    <Loader2 className='mr-2 h-4 w-4 animate-spin' />
                    {t('auth.loggingIn')}
                  </>
                ) : (
                  t('auth.login')
                )}
              </Button>

              <p className='text-center text-sm text-muted-foreground'>
                {t('auth.noAccount')}{" "}
                <Link to='/signup' className='text-orange-500 underline underline-offset-4 hover:text-orange-600'>
                  {t('auth.signup')}
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
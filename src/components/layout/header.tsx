import React from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '@/lib/language-context';
import { LanguageSwitcher } from '@/components/language-switcher';
import { Button } from '@/components/ui/button';
import { fine } from '@/lib/fine';
import { 
  Menu, 
  X, 
  ChevronDown,
  User,
  LogOut,
  Settings,
  Building2
} from 'lucide-react';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';

export function Header() {
  const { t, language } = useLanguage();
  const [isMenuOpen, setIsMenuOpen] = React.useState(false);
  const { data: session, isPending } = fine.auth.useSession();
  const [userRole, setUserRole] = React.useState<string | null>(null);
  
  const isRtl = language === 'ar';
  
  React.useEffect(() => {
    const fetchUserRole = async () => {
      if (session?.user?.id) {
        try {
          const users = await fine.table("users").select("role").eq("id", Number(session.user.id));
          if (users && users.length > 0) {
            setUserRole(users[0].role || 'employee');
          }
        } catch (error) {
          console.error("Error fetching user role:", error);
        }
      }
    };
    
    if (!isPending && session) {
      fetchUserRole();
    }
  }, [session, isPending]);
  
  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  return (
    <header className={cn(
      "sticky top-0 z-50 w-full bg-white shadow-sm",
      isRtl ? "text-right" : "text-left"
    )}>
      <div className="container mx-auto px-4 py-4 flex items-center justify-between">
        <div className="flex items-center">
          <Link to="/" className="flex items-center">
            <img 
              src="/logo.svg" 
              alt="ShareFiring" 
              className="h-10 w-auto"
              onError={(e) => {
                // Fallback if logo doesn't exist
                e.currentTarget.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='none' stroke='%23ff5722' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3'%3E%3C/path%3E%3C/svg%3E";
              }}
            />
            <span className="ml-2 text-xl font-bold text-orange-500">ShareFiring</span>
          </Link>
        </div>

        {/* Desktop Navigation */}
        <nav className={cn(
          "hidden md:flex items-center space-x-6",
          isRtl && "space-x-reverse"
        )}>
          <Link to="/" className="text-gray-700 hover:text-orange-500">{t('nav.home')}</Link>
          <Link to="/#why" className="text-gray-700 hover:text-orange-500">{t('nav.why')}</Link>
          <Link to="/#how" className="text-gray-700 hover:text-orange-500">{t('nav.how')}</Link>
          <LanguageSwitcher />
          
          {session ? (
            <div className="flex items-center space-x-4">
              {userRole === 'employee' && (
                <Link to="/dashboard">
                  <Button variant="ghost">{t('nav.dashboard')}</Button>
                </Link>
              )}
              
              {userRole === 'company' && (
                <Link to="/company-dashboard">
                  <Button variant="ghost">{t('nav.companyDashboard')}</Button>
                </Link>
              )}
              
              {userRole === 'admin' && (
                <Link to="/admin-panel">
                  <Button variant="ghost">{t('nav.adminPanel')}</Button>
                </Link>
              )}
              
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="outline" className="flex items-center gap-2">
                    <User size={16} />
                    <span>{session.user.name}</span>
                    <ChevronDown size={16} />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuItem asChild>
                    <Link to="/profile" className="w-full cursor-pointer">
                      {t('nav.profile')}
                    </Link>
                  </DropdownMenuItem>
                  
                  {userRole === 'admin' && (
                    <DropdownMenuItem asChild>
                      <Link to="/admin-panel" className="w-full cursor-pointer flex items-center">
                        <Settings size={16} className="mr-2" />
                        {t('nav.adminPanel')}
                      </Link>
                    </DropdownMenuItem>
                  )}
                  
                  {userRole === 'company' && (
                    <DropdownMenuItem asChild>
                      <Link to="/company-dashboard" className="w-full cursor-pointer flex items-center">
                        <Building2 size={16} className="mr-2" />
                        {t('nav.companyDashboard')}
                      </Link>
                    </DropdownMenuItem>
                  )}
                  
                  <DropdownMenuSeparator />
                  <DropdownMenuItem asChild>
                    <Link to="/logout" className="w-full cursor-pointer flex items-center text-red-500">
                      <LogOut size={16} className="mr-2" />
                      {t('nav.logout')}
                    </Link>
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          ) : (
            <div className="flex items-center space-x-4">
              <Link to="/login">
                <Button variant="ghost">{t('nav.login')}</Button>
              </Link>
              <Link to="/signup">
                <Button variant="default" className="bg-orange-500 hover:bg-orange-600">
                  {t('nav.signup')}
                </Button>
              </Link>
            </div>
          )}
        </nav>

        {/* Mobile menu button */}
        <div className="md:hidden">
          <Button variant="ghost" size="icon" onClick={toggleMenu}>
            {isMenuOpen ? <X /> : <Menu />}
          </Button>
        </div>
      </div>

      {/* Mobile Navigation */}
      {isMenuOpen && (
        <div className={cn(
          "md:hidden bg-white py-4 px-4 shadow-lg",
          isRtl ? "text-right" : "text-left"
        )}>
          <nav className="flex flex-col space-y-4">
            <Link 
              to="/" 
              className="text-gray-700 hover:text-orange-500 py-2"
              onClick={() => setIsMenuOpen(false)}
            >
              {t('nav.home')}
            </Link>
            <Link 
              to="/#why" 
              className="text-gray-700 hover:text-orange-500 py-2"
              onClick={() => setIsMenuOpen(false)}
            >
              {t('nav.why')}
            </Link>
            <Link 
              to="/#how" 
              className="text-gray-700 hover:text-orange-500 py-2"
              onClick={() => setIsMenuOpen(false)}
            >
              {t('nav.how')}
            </Link>
            
            <div className="pt-2 border-t border-gray-200">
              <LanguageSwitcher />
            </div>
            
            {session ? (
              <>
                {userRole === 'employee' && (
                  <Link 
                    to="/dashboard" 
                    className="text-gray-700 hover:text-orange-500 py-2"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {t('nav.dashboard')}
                  </Link>
                )}
                
                {userRole === 'company' && (
                  <Link 
                    to="/company-dashboard" 
                    className="text-gray-700 hover:text-orange-500 py-2"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {t('nav.companyDashboard')}
                  </Link>
                )}
                
                {userRole === 'admin' && (
                  <Link 
                    to="/admin-panel" 
                    className="text-gray-700 hover:text-orange-500 py-2"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {t('nav.adminPanel')}
                  </Link>
                )}
                
                <Link 
                  to="/profile" 
                  className="text-gray-700 hover:text-orange-500 py-2"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t('nav.profile')}
                </Link>
                <Link 
                  to="/logout" 
                  className="text-red-500 hover:text-red-600 py-2"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t('nav.logout')}
                </Link>
              </>
            ) : (
              <>
                <Link 
                  to="/login" 
                  className="text-gray-700 hover:text-orange-500 py-2"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t('nav.login')}
                </Link>
                <Link 
                  to="/signup" 
                  className="bg-orange-500 text-white hover:bg-orange-600 py-2 px-4 rounded text-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t('nav.signup')}
                </Link>
              </>
            )}
          </nav>
        </div>
      )}
    </header>
  );
}
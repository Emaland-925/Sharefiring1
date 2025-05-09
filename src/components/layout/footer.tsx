import React from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';

export function Footer() {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  const currentYear = new Date().getFullYear();
  
  return (
    <footer className={cn(
      "bg-gray-100 py-8",
      isRtl ? "text-right" : "text-left"
    )}>
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
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
              <span className={cn("text-xl font-bold text-orange-500", isRtl ? "mr-2" : "ml-2")}>ShareFiring</span>
            </Link>
            <p className="mt-4 text-gray-600">
              {t('landing.hero.description')}
            </p>
          </div>
          
          <div>
            <h3 className="text-lg font-semibold mb-4">{t('nav.home')}</h3>
            <ul className="space-y-2">
              <li>
                <Link to="/#why" className="text-gray-600 hover:text-orange-500">
                  {t('nav.why')}
                </Link>
              </li>
              <li>
                <Link to="/#how" className="text-gray-600 hover:text-orange-500">
                  {t('nav.how')}
                </Link>
              </li>
              <li>
                <Link to="/contact" className="text-gray-600 hover:text-orange-500">
                  {t('landing.contact.title')}
                </Link>
              </li>
            </ul>
          </div>
          
          <div>
            <h3 className="text-lg font-semibold mb-4">{t('nav.courses')}</h3>
            <ul className="space-y-2">
              <li>
                <Link to="/courses" className="text-gray-600 hover:text-orange-500">
                  {t('courses.availableCourses')}
                </Link>
              </li>
              <li>
                <Link to="/challenges" className="text-gray-600 hover:text-orange-500">
                  {t('nav.challenges')}
                </Link>
              </li>
              <li>
                <Link to="/leaderboard" className="text-gray-600 hover:text-orange-500">
                  {t('nav.leaderboard')}
                </Link>
              </li>
            </ul>
          </div>
          
          <div>
            <h3 className="text-lg font-semibold mb-4">{t('landing.contact.title')}</h3>
            <ul className="space-y-2">
              <li className="text-gray-600">
                <strong>Email:</strong> info@sharefiring.com
              </li>
              <li className="text-gray-600">
                <strong>{t('landing.contact.phone')}:</strong> +123 456 7890
              </li>
            </ul>
          </div>
        </div>
        
        <div className="mt-8 pt-8 border-t border-gray-200 text-center">
          <p className="text-gray-600">
            &copy; {currentYear} ShareFiring. {t('landing.footer.rights')}
          </p>
        </div>
      </div>
    </footer>
  );
}
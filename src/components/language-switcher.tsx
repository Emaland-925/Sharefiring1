import React from 'react';
import { Button } from '@/components/ui/button';
import { useLanguage } from '@/lib/language-context';

export function LanguageSwitcher() {
  const { language, setLanguage } = useLanguage();

  return (
    <Button
      variant="ghost"
      size="sm"
      onClick={() => setLanguage(language === 'en' ? 'ar' : 'en')}
      className="px-2 font-medium"
    >
      {language === 'en' ? 'العربية' : 'English'}
    </Button>
  );
}
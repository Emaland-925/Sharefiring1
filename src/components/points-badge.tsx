import React from 'react';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';
import { Award } from 'lucide-react';

interface PointsBadgeProps {
  points: number;
  level: number;
}

export function PointsBadge({ points, level }: PointsBadgeProps) {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  
  return (
    <div className={cn(
      "flex items-center bg-white rounded-lg shadow-sm p-4",
      isRtl ? "flex-row-reverse text-right" : "flex-row text-left"
    )}>
      <div className={cn(
        "flex items-center justify-center w-12 h-12 rounded-full bg-orange-100",
        isRtl ? "ml-4" : "mr-4"
      )}>
        <Award className="h-6 w-6 text-orange-500" />
      </div>
      <div>
        <p className="text-sm text-gray-500">{t('dashboard.points')}</p>
        <div className="flex items-center">
          <p className="text-2xl font-bold">{points}</p>
          <div className={cn(
            "bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full",
            isRtl ? "mr-2" : "ml-2"
          )}>
            {t('dashboard.level')} {level}
          </div>
        </div>
      </div>
    </div>
  );
}
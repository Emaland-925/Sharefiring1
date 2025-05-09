import React from 'react';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';

interface CourseCardProps {
  id: number;
  title: string;
  description: string;
  progress?: number;
  isEnrolled?: boolean;
  isCompleted?: boolean;
  onEnroll?: (id: number) => void;
  onContinue?: (id: number) => void;
}

export function CourseCard({
  id,
  title,
  description,
  progress = 0,
  isEnrolled = false,
  isCompleted = false,
  onEnroll,
  onContinue
}: CourseCardProps) {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  
  return (
    <Card className={cn(
      "h-full flex flex-col",
      isRtl ? "text-right" : "text-left"
    )}>
      <CardHeader>
        <CardTitle className="text-lg">{title}</CardTitle>
      </CardHeader>
      <CardContent className="flex-grow">
        <p className="text-gray-600 text-sm">{description}</p>
        
        {isEnrolled && !isCompleted && (
          <div className="mt-4">
            <div className="flex justify-between text-sm mb-1">
              <span>{t('dashboard.progress')}</span>
              <span>{progress}%</span>
            </div>
            <Progress value={progress} className="h-2" />
          </div>
        )}
        
        {isCompleted && (
          <div className="mt-4 flex items-center">
            <div className="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
              {t('courses.completed')}
            </div>
          </div>
        )}
      </CardContent>
      <CardFooter>
        {!isEnrolled ? (
          <Button 
            className="w-full bg-orange-500 hover:bg-orange-600"
            onClick={() => onEnroll && onEnroll(id)}
          >
            {t('courses.enroll')}
          </Button>
        ) : !isCompleted ? (
          <Button 
            className="w-full bg-orange-500 hover:bg-orange-600"
            onClick={() => onContinue && onContinue(id)}
          >
            {t('courses.continue')}
          </Button>
        ) : (
          <Button 
            variant="outline" 
            className="w-full"
            onClick={() => onContinue && onContinue(id)}
          >
            {t('common.view')}
          </Button>
        )}
      </CardFooter>
    </Card>
  );
}
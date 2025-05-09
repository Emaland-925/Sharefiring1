import React from 'react';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';

interface ProfileCardProps {
  name: string;
  email: string;
  points: number;
  level: number;
  image?: string;
}

export function ProfileCard({
  name,
  email,
  points,
  level,
  image
}: ProfileCardProps) {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  
  // Get initials for avatar fallback
  const getInitials = (name: string) => {
    return name
      .split(' ')
      .map(part => part[0])
      .join('')
      .toUpperCase()
      .substring(0, 2);
  };
  
  return (
    <Card className={cn(
      isRtl ? "text-right" : "text-left"
    )}>
      <CardHeader className="pb-2">
        <div className="flex items-center">
          <Avatar className="h-16 w-16">
            <AvatarImage src={image} />
            <AvatarFallback>{getInitials(name)}</AvatarFallback>
          </Avatar>
          <div className={cn(isRtl ? "mr-4" : "ml-4")}>
            <h2 className="text-xl font-bold">{name}</h2>
            <p className="text-gray-500">{email}</p>
          </div>
        </div>
      </CardHeader>
      <CardContent>
        <div className="grid grid-cols-2 gap-4 mt-4">
          <div className="bg-orange-50 p-4 rounded-lg text-center">
            <p className="text-gray-500 text-sm">{t('dashboard.points')}</p>
            <p className="text-2xl font-bold text-orange-500">{points}</p>
          </div>
          <div className="bg-blue-50 p-4 rounded-lg text-center">
            <p className="text-gray-500 text-sm">{t('dashboard.level')}</p>
            <p className="text-2xl font-bold text-blue-500">{level}</p>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
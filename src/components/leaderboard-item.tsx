import React from 'react';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';

interface LeaderboardItemProps {
  rank: number;
  name: string;
  points: number;
  level: number;
  image?: string;
  isCurrentUser?: boolean;
}

export function LeaderboardItem({
  rank,
  name,
  points,
  level,
  image,
  isCurrentUser = false
}: LeaderboardItemProps) {
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
  
  // Get medal for top 3 ranks
  const getMedal = (rank: number) => {
    if (rank === 1) return '🥇';
    if (rank === 2) return '🥈';
    if (rank === 3) return '🥉';
    return rank;
  };
  
  return (
    <div className={cn(
      "flex items-center p-3 rounded-lg",
      isCurrentUser ? "bg-orange-50" : "hover:bg-gray-50",
      isRtl ? "flex-row-reverse" : "flex-row"
    )}>
      <div className={cn(
        "flex items-center justify-center w-8 h-8 rounded-full",
        isRtl ? "ml-4" : "mr-4"
      )}>
        {typeof getMedal(rank) === 'string' ? (
          <span className="text-xl">{getMedal(rank)}</span>
        ) : (
          <span className="text-gray-500 font-medium">{rank}</span>
        )}
      </div>
      
      <Avatar className={cn("h-10 w-10", isRtl ? "ml-3" : "mr-3")}>
        <AvatarImage src={image} />
        <AvatarFallback>{getInitials(name)}</AvatarFallback>
      </Avatar>
      
      <div className="flex-grow">
        <p className="font-medium">{name}</p>
      </div>
      
      <div className={cn(
        "flex items-center",
        isRtl ? "mr-auto" : "ml-auto"
      )}>
        <div className={cn(
          "px-2 py-1 rounded bg-orange-100 text-orange-800 text-xs font-medium",
          isRtl ? "ml-2" : "mr-2"
        )}>
          {t('dashboard.level')} {level}
        </div>
        <div className="text-gray-700 font-medium">
          {points} {t('dashboard.points')}
        </div>
      </div>
    </div>
  );
}
import React from 'react';
import { Button } from '@/components/ui/button';
import { useLanguage } from '@/lib/language-context';
import { cn } from '@/lib/utils';

interface FeatureCardProps {
  icon: React.ReactNode;
  title: string;
  description: string;
  buttonText: string;
  onClick?: () => void;
}

export function FeatureCard({ icon, title, description, buttonText, onClick }: FeatureCardProps) {
  const { language } = useLanguage();
  const isRtl = language === 'ar';
  
  return (
    <div className={cn(
      "bg-white p-6 rounded-lg shadow-md flex flex-col items-center text-center",
      isRtl ? "text-right" : "text-left"
    )}>
      <div className="bg-orange-100 p-3 rounded-full mb-4">
        {icon}
      </div>
      <h3 className="text-lg font-semibold mb-2">{title}</h3>
      <p className="text-gray-600 mb-4">{description}</p>
      <Button 
        variant="outline" 
        className="mt-auto text-orange-500 border-orange-500 hover:bg-orange-50"
        onClick={onClick}
      >
        {buttonText}
      </Button>
    </div>
  );
}
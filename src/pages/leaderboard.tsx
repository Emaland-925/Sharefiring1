import React, { useEffect, useState } from 'react';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { LeaderboardItem } from '@/components/leaderboard-item';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { fine } from '@/lib/fine';
import { cn } from '@/lib/utils';
import { ProtectedRoute } from '@/components/auth/route-components';
import { Loader2, Search } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';

const LeaderboardContent = () => {
  const { t, language } = useLanguage();
  const { toast } = useToast();
  const isRtl = language === 'ar';
  const { data: session } = fine.auth.useSession();
  
  const [isLoading, setIsLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  
  const [leaderboard, setLeaderboard] = useState<Array<{
    id: number;
    name: string;
    points: number;
    level: number;
    image?: string;
  }>>([]);
  
  useEffect(() => {
    const fetchLeaderboard = async () => {
      try {
        const users = await fine.table("users")
          .select("id, name, points, level, profile_image")
          .order("points", { ascending: false })
          .limit(50);
        
        if (users) {
          setLeaderboard(users.map(user => ({
            id: user.id,
            name: user.name,
            points: user.points || 0,
            level: user.level || 1,
            image: user.profile_image
          })));
        }
      } catch (error) {
        console.error("Error fetching leaderboard:", error);
        toast({
          title: t('common.error'),
          description: String(error),
          variant: "destructive",
        });
      } finally {
        setIsLoading(false);
      }
    };
    
    fetchLeaderboard();
  }, [toast, t]);
  
  const filteredLeaderboard = leaderboard.filter(user => 
    user.name.toLowerCase().includes(searchQuery.toLowerCase())
  );
  
  if (isLoading) {
    return (
      <div className="min-h-screen flex flex-col">
        <Header />
        <main className="flex-grow flex items-center justify-center">
          <div className="text-center">
            <Loader2 className="h-8 w-8 animate-spin mx-auto mb-4 text-orange-500" />
            <p>{t('common.loading')}</p>
          </div>
        </main>
        <Footer />
      </div>
    );
  }
  
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow py-8">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-center mb-8">
            <h1 className="text-3xl font-bold">{t('nav.leaderboard')}</h1>
            
            <div className="relative w-full md:w-64 mt-4 md:mt-0">
              <Search className={cn(
                "absolute top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4",
                isRtl ? "right-3" : "left-3"
              )} />
              <Input
                placeholder={t('leaderboard.search')}
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className={cn(
                  "pl-10",
                  isRtl && "pr-10 pl-3"
                )}
              />
            </div>
          </div>
          
          <Card>
            <CardHeader>
              <CardTitle>{t('leaderboard.title')}</CardTitle>
            </CardHeader>
            <CardContent>
              {filteredLeaderboard.length > 0 ? (
                <div className="space-y-2">
                  {filteredLeaderboard.map((user, index) => (
                    <LeaderboardItem
                      key={user.id}
                      rank={index + 1}
                      name={user.name}
                      points={user.points}
                      level={user.level}
                      image={user.image}
                      isCurrentUser={user.id === Number(session?.user?.id)}
                    />
                  ))}
                </div>
              ) : (
                <div className="text-center py-8">
                  <p className="text-gray-500">
                    {searchQuery ? t('leaderboard.noSearchResults') : t('leaderboard.noUsers')}
                  </p>
                </div>
              )}
            </CardContent>
          </Card>
        </div>
      </main>
      
      <Footer />
    </div>
  );
};

const Leaderboard = () => {
  return <ProtectedRoute Component={LeaderboardContent} />;
};

export default Leaderboard;
import React from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '@/lib/language-context';
import { Header } from '@/components/layout/header';
import { Footer } from '@/components/layout/footer';
import { Button } from '@/components/ui/button';
import { FeatureCard } from '@/components/feature-card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils';
import { 
  Zap, 
  Award, 
  BarChart3, 
  BookOpen,
  Users,
  TrendingUp
} from 'lucide-react';

const Index = () => {
  const { t, language } = useLanguage();
  const isRtl = language === 'ar';
  
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Handle contact form submission
  };
  
  return (
    <div className={cn(
      "min-h-screen flex flex-col",
      isRtl ? "text-right" : "text-left"
    )}>
      <Header />
      
      <main className="flex-grow">
        {/* Hero Section */}
        <section className="bg-gradient-to-r from-orange-50 to-orange-100 py-16">
          <div className="container mx-auto px-4">
            <div className="flex flex-col md:flex-row items-center">
              <div className={cn(
                "md:w-1/2",
                isRtl ? "md:order-2" : "md:order-1"
              )}>
                <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                  {t('landing.hero.title')}
                </h1>
                <h2 className="text-2xl font-semibold text-orange-500 mb-4">
                  {t('landing.hero.subtitle')}
                </h2>
                <p className="text-lg text-gray-700 mb-8">
                  {t('landing.hero.description')}
                </p>
                <div className="flex flex-wrap gap-4">
                  <Link to="/signup">
                    <Button className="bg-orange-500 hover:bg-orange-600 text-white">
                      {t('nav.signup')}
                    </Button>
                  </Link>
                  <Link to="#why">
                    <Button variant="outline" className="border-orange-500 text-orange-500 hover:bg-orange-50">
                      {t('landing.hero.learnMore')}
                    </Button>
                  </Link>
                </div>
              </div>
              <div className={cn(
                "md:w-1/2 mt-8 md:mt-0",
                isRtl ? "md:order-1" : "md:order-2"
              )}>
                <img 
                  src="/hero-image.svg" 
                  alt="ShareFiring Hero" 
                  className="w-full h-auto"
                  onError={(e) => {
                    // Fallback if image doesn't exist
                    e.currentTarget.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400' fill='none'%3E%3Crect width='600' height='400' fill='%23FFECE5'/%3E%3Ccircle cx='300' cy='200' r='150' fill='%23FF5722' fill-opacity='0.2'/%3E%3Cpath d='M300 100C244.772 100 200 144.772 200 200C200 255.228 244.772 300 300 300C355.228 300 400 255.228 400 200C400 144.772 355.228 100 300 100ZM300 280C255.817 280 220 244.183 220 200C220 155.817 255.817 120 300 120C344.183 120 380 155.817 380 200C380 244.183 344.183 280 300 280Z' fill='%23FF5722'/%3E%3Cpath d='M320 160H280V240H320V160Z' fill='%23FF5722'/%3E%3Cpath d='M360 200H240V220H360V200Z' fill='%23FF5722'/%3E%3C/svg%3E";
                  }}
                />
              </div>
            </div>
          </div>
        </section>
        
        {/* Why Choose Us Section */}
        <section id="why" className="py-16">
          <div className="container mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold mb-4">{t('landing.why.title')}</h2>
              <div className="w-24 h-1 bg-orange-500 mx-auto"></div>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <FeatureCard
                icon={<BookOpen className="h-6 w-6 text-orange-500" />}
                title={t('landing.why.reason1.title')}
                description={t('landing.why.reason1.description')}
                buttonText={t('landing.why.tryNow')}
                onClick={() => {}}
              />
              
              <FeatureCard
                icon={<Users className="h-6 w-6 text-orange-500" />}
                title={t('landing.why.reason2.title')}
                description={t('landing.why.reason2.description')}
                buttonText={t('landing.why.tryNow')}
                onClick={() => {}}
              />
              
              <FeatureCard
                icon={<Award className="h-6 w-6 text-orange-500" />}
                title={t('landing.why.reason3.title')}
                description={t('landing.why.reason3.description')}
                buttonText={t('landing.why.tryNow')}
                onClick={() => {}}
              />
            </div>
          </div>
        </section>
        
        {/* How It Works Section */}
        <section id="how" className="py-16 bg-gray-50">
          <div className="container mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold mb-4">{t('landing.how.title')}</h2>
              <div className="w-24 h-1 bg-orange-500 mx-auto"></div>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="bg-white p-8 rounded-lg shadow-md text-center">
                <div className="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-orange-500 text-2xl font-bold">1</span>
                </div>
                <h3 className="text-xl font-semibold mb-2">{t('landing.how.step1.title')}</h3>
                <p className="text-gray-600">{t('landing.how.step1.description')}</p>
              </div>
              
              <div className="bg-white p-8 rounded-lg shadow-md text-center">
                <div className="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-orange-500 text-2xl font-bold">2</span>
                </div>
                <h3 className="text-xl font-semibold mb-2">{t('landing.how.step2.title')}</h3>
                <p className="text-gray-600">{t('landing.how.step2.description')}</p>
              </div>
              
              <div className="bg-white p-8 rounded-lg shadow-md text-center">
                <div className="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-orange-500 text-2xl font-bold">3</span>
                </div>
                <h3 className="text-xl font-semibold mb-2">{t('landing.how.step3.title')}</h3>
                <p className="text-gray-600">{t('landing.how.step3.description')}</p>
              </div>
            </div>
            
            <div className="text-center mt-12">
              <Link to="/signup">
                <Button className="bg-orange-500 hover:bg-orange-600">
                  {t('landing.how.tryNow')}
                </Button>
              </Link>
            </div>
          </div>
        </section>
        
        {/* Contact Section */}
        <section id="contact" className="py-16 bg-orange-500 text-white">
          <div className="container mx-auto px-4">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold mb-4">{t('landing.contact.title')}</h2>
              <div className="w-24 h-1 bg-white mx-auto"></div>
            </div>
            
            <div className="max-w-lg mx-auto">
              <form onSubmit={handleSubmit}>
                <div className="mb-4">
                  <label htmlFor="name" className="block mb-2">{t('landing.contact.name')}</label>
                  <Input 
                    id="name" 
                    className="bg-white text-gray-900 border-none" 
                    placeholder={t('landing.contact.name')}
                  />
                </div>
                
                <div className="mb-4">
                  <label htmlFor="email" className="block mb-2">{t('landing.contact.email')}</label>
                  <Input 
                    id="email" 
                    type="email" 
                    className="bg-white text-gray-900 border-none" 
                    placeholder={t('landing.contact.email')}
                  />
                </div>
                
                <div className="mb-4">
                  <label htmlFor="phone" className="block mb-2">{t('landing.contact.phone')}</label>
                  <Input 
                    id="phone" 
                    className="bg-white text-gray-900 border-none" 
                    placeholder={t('landing.contact.phone')}
                  />
                </div>
                
                <div className="mb-6">
                  <label htmlFor="message" className="block mb-2">Message</label>
                  <Textarea 
                    id="message" 
                    className="bg-white text-gray-900 border-none" 
                    rows={4}
                  />
                </div>
                
                <Button 
                  type="submit" 
                  className="w-full bg-white text-orange-500 hover:bg-gray-100"
                >
                  {t('landing.contact.submit')}
                </Button>
              </form>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
};

export default Index;
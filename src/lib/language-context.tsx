import React, { createContext, useState, useContext, useEffect } from 'react';

type LanguageContextType = {
  language: 'en' | 'ar';
  setLanguage: (lang: 'en' | 'ar') => void;
  t: (key: string) => string;
};

const translations = {
  en: {
    // Navigation
    'nav.home': 'Home',
    'nav.why': 'Why Us',
    'nav.how': 'How It Works',
    'nav.about': 'About Us',
    'nav.login': 'Login',
    'nav.signup': 'Sign Up',
    'nav.tryFree': 'Try for Free',
    'nav.dashboard': 'Dashboard',
    'nav.courses': 'Courses',
    'nav.challenges': 'Challenges',
    'nav.community': 'Community',
    'nav.leaderboard': 'Leaderboard',
    'nav.profile': 'Profile',
    'nav.logout': 'Logout',
    'nav.adminPanel': 'Admin Panel',
    'nav.companyDashboard': 'Company Dashboard',
    
    // Landing Page
    'landing.hero.title': 'Transform Learning into an Exciting Experience',
    'landing.hero.subtitle': 'What is ShareFiring?',
    'landing.hero.description': 'An advanced system designed to motivate employees by transforming the learning process into an enjoyable and interactive experience. It aims to enhance the knowledge acquisition process.',
    'landing.hero.learnMore': 'Learn More',
    'landing.why.title': 'Why Choose ShareFiring?',
    'landing.why.reason1.title': 'Gamified Training',
    'landing.why.reason1.description': 'Transform traditional training into an engaging system that motivates employees to acquire skills in new ways',
    'landing.why.reason2.title': 'Easy Skills Transfer',
    'landing.why.reason2.description': 'Simple courses created by employees for employees, making knowledge transfer easy and effective',
    'landing.why.reason3.title': 'Innovative Learning Approach',
    'landing.why.reason3.description': 'Interactive challenges make learning fun, with clear goals and rewards',
    'landing.why.tryNow': 'Try Now',
    'landing.how.title': 'How ShareFiring Works',
    'landing.how.step1.title': 'Content Creation',
    'landing.how.step1.description': 'Employees and trainers create and share courses with interactive content',
    'landing.how.step2.title': 'Interactive Learning',
    'landing.how.step2.description': 'Engaging activities with game elements and exciting rewards',
    'landing.how.step3.title': 'Progress Management',
    'landing.how.step3.description': 'Track learning progress, monitor performance, and reward employees',
    'landing.how.tryNow': 'Try Now',
    'landing.contact.title': 'Contact Us',
    'landing.contact.name': 'Name',
    'landing.contact.email': 'Email',
    'landing.contact.phone': 'Phone Number',
    'landing.contact.submit': 'Submit',
    'landing.footer.rights': 'All Rights Reserved',
    
    // Auth
    'auth.email': 'Email',
    'auth.password': 'Password',
    'auth.name': 'Name',
    'auth.login': 'Login',
    'auth.signup': 'Sign Up',
    'auth.forgotPassword': 'Forgot Password?',
    'auth.noAccount': 'Don\'t have an account?',
    'auth.haveAccount': 'Already have an account?',
    'auth.rememberMe': 'Remember me',
    'auth.loginDesc': 'Enter your credentials to access your account',
    'auth.signupDesc': 'Enter your details below to create your account',
    'auth.emailRequired': 'Email is required',
    'auth.emailInvalid': 'Please enter a valid email address',
    'auth.passwordRequired': 'Password is required',
    'auth.passwordLength': 'Password must be at least 8 characters',
    'auth.nameRequired': 'Name is required',
    'auth.loginSuccess': 'Success',
    'auth.loginSuccessDesc': 'You have been signed in successfully.',
    'auth.signupSuccess': 'Account created',
    'auth.signupSuccessDesc': 'Please check your email to verify your account.',
    'auth.invalidCredentials': 'Invalid email or password.',
    'auth.signupError': 'Something went wrong. Please try again.',
    'auth.loggingIn': 'Signing in...',
    'auth.creatingAccount': 'Creating account...',
    'auth.role': 'Role',
    'auth.roleRequired': 'Role is required',
    'auth.roleEmployee': 'Employee',
    'auth.roleCompany': 'Company',
    'auth.roleAdmin': 'Administrator',
    'auth.companyName': 'Company Name',
    'auth.companyNameRequired': 'Company name is required',
    'auth.companyDescription': 'Company Description',
    
    // Dashboard
    'dashboard.welcome': 'Welcome',
    'dashboard.points': 'Points',
    'dashboard.level': 'Level',
    'dashboard.courses': 'My Courses',
    'dashboard.challenges': 'Active Challenges',
    'dashboard.leaderboard': 'Leaderboard',
    'dashboard.viewAll': 'View All',
    'dashboard.progress': 'Progress',
    
    // Company Dashboard
    'company.dashboard.title': 'Company Dashboard',
    'company.dashboard.totalEmployees': 'Total Employees',
    'company.dashboard.activeCourses': 'Active Courses',
    'company.dashboard.completedCourses': 'Completed Courses',
    'company.dashboard.averageProgress': 'Average Progress',
    'company.dashboard.topPerformers': 'Top Performers',
    'company.dashboard.courseCompletion': 'Course Completion',
    'company.dashboard.noData': 'No data available',
    'company.dashboard.addEmployee': 'Add Employee',
    'company.dashboard.createCourse': 'Create Course',
    'company.dashboard.manageRewards': 'Manage Rewards',
    
    // Admin Panel
    'admin.panel.title': 'Admin Panel',
    'admin.panel.totalUsers': 'Total Users',
    'admin.panel.totalCompanies': 'Total Companies',
    'admin.panel.totalCourses': 'Total Courses',
    'admin.panel.pendingApprovals': 'Pending Approvals',
    'admin.panel.users': 'Users',
    'admin.panel.companies': 'Companies',
    'admin.panel.courses': 'Courses',
    'admin.panel.approvals': 'Approvals',
    'admin.panel.settings': 'Settings',
    'admin.panel.userManagement': 'User Management',
    'admin.panel.userManagementDesc': 'Manage all users in the system, including employees, company administrators, and system administrators.',
    'admin.panel.companyManagement': 'Company Management',
    'admin.panel.companyManagementDesc': 'Manage all companies registered in the system.',
    'admin.panel.courseManagement': 'Course Management',
    'admin.panel.courseManagementDesc': 'View and manage all courses across all companies.',
    'admin.panel.approvalsDesc': 'Review and approve pending courses, challenges, and other content.',
    'admin.panel.systemSettings': 'System Settings',
    'admin.panel.settingsDesc': 'Configure system-wide settings and preferences.',
    'admin.panel.addUser': 'Add User',
    'admin.panel.addCompany': 'Add Company',
    'admin.panel.viewAllCourses': 'View All Courses',
    'admin.panel.reviewApprovals': 'Review Approvals',
    'admin.panel.configureSettings': 'Configure Settings',
    
    // Courses
    'courses.title': 'Courses',
    'courses.create': 'Create Course',
    'courses.enroll': 'Enroll',
    'courses.continue': 'Continue',
    'courses.completed': 'Completed',
    'courses.myCourses': 'My Courses',
    'courses.availableCourses': 'Available Courses',
    'courses.search': 'Search courses...',
    'courses.noCourses': 'You haven\'t enrolled in any courses yet.',
    'courses.browse': 'Browse Courses',
    'courses.noEnrolled': 'You haven\'t enrolled in any courses yet.',
    'courses.noAvailable': 'No available courses at the moment.',
    'courses.noSearchResults': 'No courses match your search.',
    'courses.browseAvailable': 'Browse Available Courses',
    'courses.clearSearch': 'Clear Search',
    'courses.enrollSuccess': 'Enrolled Successfully',
    'courses.enrollSuccessDesc': 'You have been enrolled in the course.',
    'courses.pendingApproval': 'Pending Approval',
    'courses.approved': 'Approved',
    'courses.rejected': 'Rejected',
    'courses.courseTitle': 'Course Title',
    'courses.courseDescription': 'Course Description',
    'courses.pointsReward': 'Points Reward',
    'courses.createSuccess': 'Course Created',
    'courses.createSuccessDesc': 'Your course has been submitted for approval.',
    
    // Leaderboard
    'leaderboard.title': 'Top Performers',
    'leaderboard.search': 'Search users...',
    'leaderboard.noUsers': 'No users found.',
    'leaderboard.noSearchResults': 'No users match your search.',
    
    // Profile
    'profile.settings': 'Settings',
    'profile.achievements': 'Achievements',
    'profile.personalInfo': 'Personal Information',
    'profile.language': 'Language',
    'profile.selectLanguage': 'Select language',
    'profile.emailReadonly': 'Email cannot be changed',
    'profile.saveSuccess': 'Profile Updated',
    'profile.saveSuccessDesc': 'Your profile has been updated successfully.',
    'profile.saving': 'Saving...',
    'profile.totalPoints': 'Total Points',
    'profile.currentLevel': 'Current Level',
    'profile.badges': 'Badges',
    'profile.role': 'Role',
    'profile.company': 'Company',
    
    // Challenges
    'challenges.noActive': 'No active challenges at the moment.',
    'challenges.create': 'Create Challenge',
    'challenges.title': 'Challenge Title',
    'challenges.description': 'Challenge Description',
    'challenges.deadline': 'Deadline',
    'challenges.pointsReward': 'Points Reward',
    
    // Common
    'common.loading': 'Loading...',
    'common.error': 'An error occurred',
    'common.save': 'Save',
    'common.cancel': 'Cancel',
    'common.submit': 'Submit',
    'common.delete': 'Delete',
    'common.edit': 'Edit',
    'common.view': 'View',
    'common.approve': 'Approve',
    'common.reject': 'Reject',
    'common.pending': 'Pending',
  },
  ar: {
    // Navigation
    'nav.home': 'الصفحة الرئيسية',
    'nav.why': 'لماذا نحن',
    'nav.how': 'آلية العمل',
    'nav.about': 'عنا',
    'nav.login': 'تسجيل الدخول',
    'nav.signup': 'تسجيل جديد',
    'nav.tryFree': 'اطلب تجربة',
    'nav.dashboard': 'لوحة التحكم',
    'nav.courses': 'الدورات',
    'nav.challenges': 'التحديات',
    'nav.community': 'المجتمع',
    'nav.leaderboard': 'لوحة المتصدرين',
    'nav.profile': 'الملف الشخصي',
    'nav.logout': 'تسجيل الخروج',
    'nav.adminPanel': 'لوحة الإدارة',
    'nav.companyDashboard': 'لوحة تحكم الشركة',
    
    // Landing Page
    'landing.hero.title': 'نحول التعلم الى تجربة ممتعة',
    'landing.hero.subtitle': 'مين Sharefiring؟',
    'landing.hero.description': 'نظام متطور يهدف لتحفيز الموظفين عن طريق تحويل التعلم لتجربة ممتعة وتفاعلية. يهدف لتطوير عملية اكتساب المعرفة.',
    'landing.hero.learnMore': 'اعرف المزيد',
    'landing.why.title': 'لماذا تختار Sharefiring؟',
    'landing.why.reason1.title': 'تطبيق لعبي',
    'landing.why.reason1.description': 'تحويل التدريب التقليدي الى نظام محفز يساعد في العملية التدريبية لتحفيز الموظفين على اكتساب المعرفة',
    'landing.why.reason2.title': 'نقل المهارات بسهولة',
    'landing.why.reason2.description': 'انشاء دورات بواسطة موظفين لموظفين بشكل سهل وفعال',
    'landing.why.reason3.title': 'أسلوب تدريب مبتكر',
    'landing.why.reason3.description': 'تحديات تفاعلية تجعل عملية التعلم سهلة، مهام واضحة ومكافآت',
    'landing.why.tryNow': 'جرب الآن',
    'landing.how.title': 'كيف يعمل Sharefiring',
    'landing.how.step1.title': 'انشاء المحتوى',
    'landing.how.step1.description': 'مراقبة أداء الموظفين وتقديم تغذية راجعة مستمرة',
    'landing.how.step2.title': 'التعليم التفاعلي',
    'landing.how.step2.description': 'أنشطة تفاعلية بعناصر لعبية وتحفيزية مثيرة',
    'landing.how.step3.title': 'إدارة سجلك',
    'landing.how.step3.description': 'اشراف كامل على التقدم ومتابعة أداء الموظفين',
    'landing.how.tryNow': 'جرب الآن',
    'landing.contact.title': 'تواصل معنا',
    'landing.contact.name': 'الاسم',
    'landing.contact.email': 'البريد الإلكتروني',
    'landing.contact.phone': 'رقم الجوال',
    'landing.contact.submit': 'إرسال',
    'landing.footer.rights': 'جميع الحقوق محفوظة',
    
    // Auth
    'auth.email': 'البريد الإلكتروني',
    'auth.password': 'كلمة المرور',
    'auth.name': 'الاسم',
    'auth.login': 'تسجيل الدخول',
    'auth.signup': 'تسجيل جديد',
    'auth.forgotPassword': 'نسيت كلمة المرور؟',
    'auth.noAccount': 'ليس لديك حساب؟',
    'auth.haveAccount': 'لديك حساب بالفعل؟',
    'auth.rememberMe': 'تذكرني',
    'auth.loginDesc': 'أدخل بيانات الاعتماد الخاصة بك للوصول إلى حسابك',
    'auth.signupDesc': 'أدخل التفاصيل الخاصة بك أدناه لإنشاء حسابك',
    'auth.emailRequired': 'البريد الإلكتروني مطلوب',
    'auth.emailInvalid': 'يرجى إدخال عنوان بريد إلكتروني صالح',
    'auth.passwordRequired': 'كلمة المرور مطلوبة',
    'auth.passwordLength': 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
    'auth.nameRequired': 'الاسم مطلوب',
    'auth.loginSuccess': 'تم بنجاح',
    'auth.loginSuccessDesc': 'تم تسجيل دخولك بنجاح.',
    'auth.signupSuccess': 'تم إنشاء الحساب',
    'auth.signupSuccessDesc': 'يرجى التحقق من بريدك الإلكتروني للتحقق من حسابك.',
    'auth.invalidCredentials': 'بريد إلكتروني أو كلمة مرور غير صالحة.',
    'auth.signupError': 'حدث خطأ ما. حاول مرة اخرى.',
    'auth.loggingIn': 'جاري تسجيل الدخول...',
    'auth.creatingAccount': 'جاري إنشاء الحساب...',
    'auth.role': 'الدور',
    'auth.roleRequired': 'الدور مطلوب',
    'auth.roleEmployee': 'موظف',
    'auth.roleCompany': 'شركة',
    'auth.roleAdmin': 'مدير النظام',
    'auth.companyName': 'اسم الشركة',
    'auth.companyNameRequired': 'اسم الشركة مطلوب',
    'auth.companyDescription': 'وصف الشركة',
    
    // Dashboard
    'dashboard.welcome': 'مرحباً',
    'dashboard.points': 'النقاط',
    'dashboard.level': 'المستوى',
    'dashboard.courses': 'دوراتي',
    'dashboard.challenges': 'التحديات النشطة',
    'dashboard.leaderboard': 'المتصدرين',
    'dashboard.viewAll': 'عرض الكل',
    'dashboard.progress': 'التقدم',
    
    // Company Dashboard
    'company.dashboard.title': 'لوحة تحكم الشركة',
    'company.dashboard.totalEmployees': 'إجمالي الموظفين',
    'company.dashboard.activeCourses': 'الدورات النشطة',
    'company.dashboard.completedCourses': 'الدورات المكتملة',
    'company.dashboard.averageProgress': 'متوسط التقدم',
    'company.dashboard.topPerformers': 'أفضل المتصدرين',
    'company.dashboard.courseCompletion': 'إكمال الدورات',
    'company.dashboard.noData': 'لا توجد بيانات متاحة',
    'company.dashboard.addEmployee': 'إضافة موظف',
    'company.dashboard.createCourse': 'إنشاء دورة',
    'company.dashboard.manageRewards': 'إدارة المكافآت',
    
    // Admin Panel
    'admin.panel.title': 'لوحة الإدارة',
    'admin.panel.totalUsers': 'إجمالي المستخدمين',
    'admin.panel.totalCompanies': 'إجمالي الشركات',
    'admin.panel.totalCourses': 'إجمالي الدورات',
    'admin.panel.pendingApprovals': 'الموافقات المعلقة',
    'admin.panel.users': 'المستخدمين',
    'admin.panel.companies': 'الشركات',
    'admin.panel.courses': 'الدورات',
    'admin.panel.approvals': 'الموافقات',
    'admin.panel.settings': 'الإعدادات',
    'admin.panel.userManagement': 'إدارة المستخدمين',
    'admin.panel.userManagementDesc': 'إدارة جميع المستخدمين في النظام، بما في ذلك الموظفين ومديري الشركات ومديري النظام.',
    'admin.panel.companyManagement': 'إدارة الشركات',
    'admin.panel.companyManagementDesc': 'إدارة جميع الشركات المسجلة في النظام.',
    'admin.panel.courseManagement': 'إدارة الدورات',
    'admin.panel.courseManagementDesc': 'عرض وإدارة جميع الدورات عبر جميع الشركات.',
    'admin.panel.approvalsDesc': 'مراجعة والموافقة على الدورات والتحديات والمحتوى الآخر المعلق.',
    'admin.panel.systemSettings': 'إعدادات النظام',
    'admin.panel.settingsDesc': 'تكوين إعدادات وتفضيلات النظام.',
    'admin.panel.addUser': 'إضافة مستخدم',
    'admin.panel.addCompany': 'إضافة شركة',
    'admin.panel.viewAllCourses': 'عرض جميع الدورات',
    'admin.panel.reviewApprovals': 'مراجعة الموافقات',
    'admin.panel.configureSettings': 'تكوين الإعدادات',
    
    // Courses
    'courses.title': 'الدورات',
    'courses.create': 'إنشاء دورة',
    'courses.enroll': 'تسجيل',
    'courses.continue': 'استمرار',
    'courses.completed': 'مكتمل',
    'courses.myCourses': 'دوراتي',
    'courses.availableCourses': 'الدورات المتاحة',
    'courses.search': 'بحث عن دورات...',
    'courses.noCourses': 'لم تسجل في أي دورات بعد.',
    'courses.browse': 'تصفح الدورات',
    'courses.noEnrolled': 'لم تسجل في أي دورات بعد.',
    'courses.noAvailable': 'لا توجد دورات متاحة في الوقت الحالي.',
    'courses.noSearchResults': 'لا توجد دورات تطابق بحثك.',
    'courses.browseAvailable': 'تصفح الدورات المتاحة',
    'courses.clearSearch': 'مسح البحث',
    'courses.enrollSuccess': 'تم التسجيل بنجاح',
    'courses.enrollSuccessDesc': 'تم تسجيلك في الدورة.',
    'courses.pendingApproval': 'في انتظار الموافقة',
    'courses.approved': 'تمت الموافقة',
    'courses.rejected': 'مرفوض',
    'courses.courseTitle': 'عنوان الدورة',
    'courses.courseDescription': 'وصف الدورة',
    'courses.pointsReward': 'مكافأة النقاط',
    'courses.createSuccess': 'تم إنشاء الدورة',
    'courses.createSuccessDesc': 'تم تقديم دورتك للموافقة عليها.',
    
    // Leaderboard
    'leaderboard.title': 'أفضل المتصدرين',
    'leaderboard.search': 'بحث عن مستخدمين...',
    'leaderboard.noUsers': 'لم يتم العثور على مستخدمين.',
    'leaderboard.noSearchResults': 'لا يوجد مستخدمين يطابقون بحثك.',
    
    // Profile
    'profile.settings': 'الإعدادات',
    'profile.achievements': 'الإنجازات',
    'profile.personalInfo': 'المعلومات الشخصية',
    'profile.language': 'اللغة',
    'profile.selectLanguage': 'اختر اللغة',
    'profile.emailReadonly': 'لا يمكن تغيير البريد الإلكتروني',
    'profile.saveSuccess': 'تم تحديث الملف الشخصي',
    'profile.saveSuccessDesc': 'تم تحديث ملفك الشخصي بنجاح.',
    'profile.saving': 'جاري الحفظ...',
    'profile.totalPoints': 'إجمالي النقاط',
    'profile.currentLevel': 'المستوى الحالي',
    'profile.badges': 'الشارات',
    'profile.role': 'الدور',
    'profile.company': 'الشركة',
    
    // Challenges
    'challenges.noActive': 'لا توجد تحديات نشطة في الوقت الحالي.',
    'challenges.create': 'إنشاء تحدي',
    'challenges.title': 'عنوان التحدي',
    'challenges.description': 'وصف التحدي',
    'challenges.deadline': 'الموعد النهائي',
    'challenges.pointsReward': 'مكافأة النقاط',
    
    // Common
    'common.loading': 'جاري التحميل...',
    'common.error': 'حدث خطأ',
    'common.save': 'حفظ',
    'common.cancel': 'إلغاء',
    'common.submit': 'إرسال',
    'common.delete': 'حذف',
    'common.edit': 'تعديل',
    'common.view': 'عرض',
    'common.approve': 'موافقة',
    'common.reject': 'رفض',
    'common.pending': 'معلق',
  }
};

const LanguageContext = createContext<LanguageContextType>({
  language: 'en',
  setLanguage: () => {},
  t: () => '',
});

export const LanguageProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [language, setLanguage] = useState<'en' | 'ar'>('en');

  useEffect(() => {
    // Check if there's a saved language preference
    const savedLanguage = localStorage.getItem('language') as 'en' | 'ar';
    if (savedLanguage && (savedLanguage === 'en' || savedLanguage === 'ar')) {
      setLanguage(savedLanguage);
    } else {
      // Default to browser language if available
      const browserLang = navigator.language.split('-')[0];
      if (browserLang === 'ar') {
        setLanguage('ar');
      }
    }
    
    // Set the dir attribute on the document
    document.documentElement.dir = language === 'ar' ? 'rtl' : 'ltr';
  }, [language]);

  const handleSetLanguage = (lang: 'en' | 'ar') => {
    setLanguage(lang);
    localStorage.setItem('language', lang);
    document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
  };

  const t = (key: string): string => {
    return translations[language][key as keyof typeof translations[typeof language]] || key;
  };

  return (
    <LanguageContext.Provider value={{ language, setLanguage: handleSetLanguage, t }}>
      {children}
    </LanguageContext.Provider>
  );
};

export const useLanguage = () => useContext(LanguageContext);
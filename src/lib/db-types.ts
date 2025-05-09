export type Schema = {
  users: {
    id?: number;
    name: string;
    email: string;
    password: string;
    role?: string;
    points?: number;
    level?: number;
    profile_image?: string | null;
    language_preference?: string;
    company_id?: number | null;
    created_at?: string;
    updated_at?: string;
  };
  
  companies: {
    id?: number;
    name: string;
    description?: string | null;
    admin_id: number;
    logo_url?: string | null;
    created_at?: string;
    updated_at?: string;
  };
  
  courses: {
    id?: number;
    title: string;
    description?: string | null;
    creator_id: number;
    company_id?: number | null;
    status?: string;
    points_reward?: number;
    created_at?: string;
    updated_at?: string;
  };
  
  enrollments: {
    id?: number;
    user_id: number;
    course_id: number;
    progress?: number;
    completion_status?: string;
    created_at?: string;
    updated_at?: string;
  };
  
  lessons: {
    id?: number;
    course_id: number;
    title: string;
    content?: string | null;
    video_url?: string | null;
    order_num: number;
    created_at?: string;
    updated_at?: string;
  };
  
  challenges: {
    id?: number;
    title: string;
    description?: string | null;
    creator_id: number;
    company_id?: number | null;
    deadline?: string | null;
    points_reward?: number;
    created_at?: string;
    updated_at?: string;
  };
  
  rewards: {
    id?: number;
    title: string;
    description?: string | null;
    points_cost: number;
    image?: string | null;
    company_id?: number | null;
    created_at?: string;
    updated_at?: string;
  };
  
  user_rewards: {
    id?: number;
    user_id: number;
    reward_id: number;
    redeemed_date?: string;
  };
}
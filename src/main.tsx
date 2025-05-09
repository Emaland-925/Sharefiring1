// start the app always with '/' route
import { Toaster as Sonner } from "@/components/ui/sonner";

import { Toaster } from "@/components/ui/toaster";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { createRoot } from "react-dom/client";
import { BrowserRouter, Route, Routes } from "react-router-dom";

import { TooltipProvider } from "./components/ui/tooltip";

import { ThemeProvider } from "./components/layout/theme-provider";
import { LanguageProvider } from "./lib/language-context";
import "./index.css";
import Index from "./pages";
import LoginForm from "./pages/login";
import SignupForm from "./pages/signup";
import Logout from "./pages/logout";
import Dashboard from "./pages/dashboard";
import Courses from "./pages/courses";
import Profile from "./pages/profile";
import Leaderboard from "./pages/leaderboard";
import CompanyDashboard from "./pages/company-dashboard";
import AdminPanel from "./pages/admin-panel";

const queryClient = new QueryClient();

createRoot(document.getElementById("root")!).render(
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <ThemeProvider>
        <LanguageProvider>
          <BrowserRouter>
            <Routes>
              <Route path='/' element={<Index />} />
              <Route path='/login' element={<LoginForm />} />
              <Route path='/signup' element={<SignupForm />} />
              <Route path='/logout' element={<Logout />} />
              <Route path='/dashboard' element={<Dashboard />} />
              <Route path='/courses' element={<Courses />} />
              <Route path='/profile' element={<Profile />} />
              <Route path='/leaderboard' element={<Leaderboard />} />
              <Route path='/company-dashboard' element={<CompanyDashboard />} />
              <Route path='/admin-panel' element={<AdminPanel />} />
            </Routes>
          </BrowserRouter>
          <Sonner />
          <Toaster />
        </LanguageProvider>
      </ThemeProvider>
    </TooltipProvider>
  </QueryClientProvider>
);
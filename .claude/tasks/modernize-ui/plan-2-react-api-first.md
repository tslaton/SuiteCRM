# Plan 2: React-Based UI with API-First Architecture - Detailed Implementation Guide

## Overview
This plan creates a modern React-based frontend application that communicates with SuiteCRM through the V8 REST API. The new UI will be mobile-first, performant, and provide a superior user experience while maintaining all CRM functionality.

## Prerequisites
- Node.js 16+ and npm/yarn
- React development experience
- Understanding of REST APIs and OAuth2
- SuiteCRM V8 API properly configured
- Modern JavaScript/TypeScript knowledge

## Project Structure
```
suitecrm-react/
├── public/
│   ├── index.html
│   ├── manifest.json
│   └── icons/
├── src/
│   ├── api/
│   ├── components/
│   ├── contexts/
│   ├── hooks/
│   ├── layouts/
│   ├── modules/
│   ├── pages/
│   ├── services/
│   ├── store/
│   ├── styles/
│   ├── types/
│   ├── utils/
│   ├── App.tsx
│   └── index.tsx
├── package.json
├── tsconfig.json
└── .env.example
```

## Phase 1: Foundation and Infrastructure (Week 1-2)

### Task 1.1: Project Setup
**Owner**: Junior Developer  
**Duration**: 1 day

1. **Create React application with TypeScript**
   ```bash
   npx create-react-app suitecrm-react --template typescript
   cd suitecrm-react
   ```

2. **Install core dependencies**
   ```bash
   npm install --save \
     react-router-dom@6 \
     axios \
     @tanstack/react-query \
     zustand \
     tailwindcss \
     @headlessui/react \
     @heroicons/react \
     react-hook-form \
     yup \
     date-fns \
     react-hot-toast
   
   npm install --save-dev \
     @types/react-router-dom \
     @typescript-eslint/parser \
     @typescript-eslint/eslint-plugin \
     prettier \
     husky \
     lint-staged
   ```

3. **Set up Tailwind CSS**
   ```bash
   npx tailwindcss init -p
   ```
   
   Update `tailwind.config.js`:
   ```javascript
   module.exports = {
     content: [
       "./src/**/*.{js,jsx,ts,tsx}",
     ],
     theme: {
       extend: {
         colors: {
           primary: {
             50: '#f0f9ff',
             500: '#3b82f6',
             600: '#2563eb',
             700: '#1d4ed8',
           },
           gray: {
             50: '#f9fafb',
             100: '#f3f4f6',
             900: '#111827',
           }
         },
         fontSize: {
           'xs': ['0.75rem', { lineHeight: '1rem' }],
           'sm': ['0.875rem', { lineHeight: '1.25rem' }],
           'base': ['1rem', { lineHeight: '1.5rem' }],
         }
       },
     },
     plugins: [
       require('@tailwindcss/forms'),
       require('@tailwindcss/typography'),
     ],
   }
   ```

4. **Configure TypeScript**
   Update `tsconfig.json`:
   ```json
   {
     "compilerOptions": {
       "target": "ES2020",
       "lib": ["dom", "dom.iterable", "esnext"],
       "allowJs": true,
       "skipLibCheck": true,
       "esModuleInterop": true,
       "allowSyntheticDefaultImports": true,
       "strict": true,
       "forceConsistentCasingInFileNames": true,
       "noFallthroughCasesInSwitch": true,
       "module": "esnext",
       "moduleResolution": "node",
       "resolveJsonModule": true,
       "isolatedModules": true,
       "noEmit": true,
       "jsx": "react-jsx",
       "baseUrl": "src",
       "paths": {
         "@/*": ["*"]
       }
     },
     "include": ["src"]
   }
   ```

5. **Create environment configuration**
   ```bash
   # .env.example
   REACT_APP_API_URL=http://localhost/suitecrm/Api
   REACT_APP_API_VERSION=V8
   REACT_APP_CLIENT_ID=your-oauth-client-id
   REACT_APP_CLIENT_SECRET=your-oauth-client-secret
   ```

### Task 1.2: API Client Setup
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Create API configuration types**
   ```typescript
   // src/types/api.types.ts
   
   export interface ApiConfig {
     baseURL: string;
     version: string;
     clientId: string;
     clientSecret: string;
   }
   
   export interface ApiResponse<T> {
     data: T;
     meta?: {
       'total-pages'?: number;
       'record-count'?: number;
     };
     links?: {
       first?: string;
       prev?: string;
       next?: string;
       last?: string;
     };
   }
   
   export interface ApiError {
     errors: Array<{
       status: string;
       title: string;
       detail?: string;
     }>;
   }
   
   export interface JsonApiResource {
     type: string;
     id: string;
     attributes: Record<string, any>;
     relationships?: Record<string, any>;
   }
   ```

2. **Create base API client**
   ```typescript
   // src/api/client.ts
   
   import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';
   import { ApiConfig, ApiError } from '@/types/api.types';
   
   class ApiClient {
     private client: AxiosInstance;
     private config: ApiConfig;
     
     constructor() {
       this.config = {
         baseURL: process.env.REACT_APP_API_URL || '',
         version: process.env.REACT_APP_API_VERSION || 'V8',
         clientId: process.env.REACT_APP_CLIENT_ID || '',
         clientSecret: process.env.REACT_APP_CLIENT_SECRET || '',
       };
       
       this.client = axios.create({
         baseURL: `${this.config.baseURL}/${this.config.version}`,
         headers: {
           'Content-Type': 'application/vnd.api+json',
           'Accept': 'application/vnd.api+json',
         },
       });
       
       this.setupInterceptors();
     }
     
     private setupInterceptors() {
       // Request interceptor to add auth token
       this.client.interceptors.request.use(
         (config) => {
           const token = localStorage.getItem('access_token');
           if (token) {
             config.headers.Authorization = `Bearer ${token}`;
           }
           return config;
         },
         (error) => Promise.reject(error)
       );
       
       // Response interceptor for error handling
       this.client.interceptors.response.use(
         (response) => response,
         async (error) => {
           if (error.response?.status === 401) {
             // Token expired, try to refresh
             const refreshed = await this.refreshToken();
             if (refreshed) {
               // Retry original request
               return this.client(error.config);
             } else {
               // Redirect to login
               window.location.href = '/login';
             }
           }
           return Promise.reject(error);
         }
       );
     }
     
     async refreshToken(): Promise<boolean> {
       const refreshToken = localStorage.getItem('refresh_token');
       if (!refreshToken) return false;
       
       try {
         const response = await axios.post(
           `${this.config.baseURL}/access_token`,
           {
             grant_type: 'refresh_token',
             refresh_token: refreshToken,
             client_id: this.config.clientId,
             client_secret: this.config.clientSecret,
           }
         );
         
         localStorage.setItem('access_token', response.data.access_token);
         if (response.data.refresh_token) {
           localStorage.setItem('refresh_token', response.data.refresh_token);
         }
         
         return true;
       } catch {
         return false;
       }
     }
     
     async request<T>(config: AxiosRequestConfig): Promise<T> {
       try {
         const response = await this.client(config);
         return response.data;
       } catch (error: any) {
         if (error.response?.data?.errors) {
           throw error.response.data as ApiError;
         }
         throw error;
       }
     }
     
     // HTTP method shortcuts
     get<T>(url: string, config?: AxiosRequestConfig) {
       return this.request<T>({ ...config, method: 'GET', url });
     }
     
     post<T>(url: string, data?: any, config?: AxiosRequestConfig) {
       return this.request<T>({ ...config, method: 'POST', url, data });
     }
     
     patch<T>(url: string, data?: any, config?: AxiosRequestConfig) {
       return this.request<T>({ ...config, method: 'PATCH', url, data });
     }
     
     delete<T>(url: string, config?: AxiosRequestConfig) {
       return this.request<T>({ ...config, method: 'DELETE', url });
     }
   }
   
   export default new ApiClient();
   ```

3. **Create authentication service**
   ```typescript
   // src/services/auth.service.ts
   
   import apiClient from '@/api/client';
   
   interface LoginCredentials {
     username: string;
     password: string;
   }
   
   interface TokenResponse {
     access_token: string;
     expires_in: number;
     token_type: string;
     scope: string | null;
     refresh_token: string;
   }
   
   class AuthService {
     async login(credentials: LoginCredentials): Promise<boolean> {
       try {
         const response = await apiClient.post<TokenResponse>(
           '/access_token',
           {
             grant_type: 'password',
             client_id: process.env.REACT_APP_CLIENT_ID,
             client_secret: process.env.REACT_APP_CLIENT_SECRET,
             username: credentials.username,
             password: credentials.password,
           }
         );
         
         this.storeTokens(response);
         return true;
       } catch (error) {
         console.error('Login failed:', error);
         return false;
       }
     }
     
     async logout(): Promise<void> {
       try {
         await apiClient.post('/logout');
       } catch (error) {
         console.error('Logout error:', error);
       } finally {
         this.clearTokens();
         window.location.href = '/login';
       }
     }
     
     private storeTokens(response: TokenResponse) {
       localStorage.setItem('access_token', response.access_token);
       localStorage.setItem('refresh_token', response.refresh_token);
       
       // Set token expiry time
       const expiryTime = new Date().getTime() + (response.expires_in * 1000);
       localStorage.setItem('token_expiry', expiryTime.toString());
     }
     
     private clearTokens() {
       localStorage.removeItem('access_token');
       localStorage.removeItem('refresh_token');
       localStorage.removeItem('token_expiry');
     }
     
     isAuthenticated(): boolean {
       const token = localStorage.getItem('access_token');
       const expiry = localStorage.getItem('token_expiry');
       
       if (!token || !expiry) return false;
       
       return new Date().getTime() < parseInt(expiry);
     }
     
     getAccessToken(): string | null {
       return localStorage.getItem('access_token');
     }
   }
   
   export default new AuthService();
   ```

### Task 1.3: State Management Setup
**Owner**: Junior Developer  
**Duration**: 1 day

1. **Create Zustand store for global state**
   ```typescript
   // src/store/index.ts
   
   import { create } from 'zustand';
   import { devtools } from 'zustand/middleware';
   
   interface User {
     id: string;
     username: string;
     email: string;
     first_name: string;
     last_name: string;
   }
   
   interface AppState {
     // User state
     user: User | null;
     setUser: (user: User | null) => void;
     
     // UI state
     sidebarOpen: boolean;
     setSidebarOpen: (open: boolean) => void;
     
     // Module state
     currentModule: string | null;
     setCurrentModule: (module: string | null) => void;
     
     // Loading states
     isLoading: boolean;
     setIsLoading: (loading: boolean) => void;
   }
   
   export const useAppStore = create<AppState>()(
     devtools(
       (set) => ({
         // User state
         user: null,
         setUser: (user) => set({ user }),
         
         // UI state
         sidebarOpen: false,
         setSidebarOpen: (open) => set({ sidebarOpen: open }),
         
         // Module state
         currentModule: null,
         setCurrentModule: (module) => set({ currentModule: module }),
         
         // Loading states
         isLoading: false,
         setIsLoading: (loading) => set({ isLoading: loading }),
       }),
       {
         name: 'app-store',
       }
     )
   );
   ```

2. **Set up React Query**
   ```typescript
   // src/App.tsx
   
   import React from 'react';
   import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
   import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
   import { BrowserRouter } from 'react-router-dom';
   import { Toaster } from 'react-hot-toast';
   import Router from './Router';
   
   const queryClient = new QueryClient({
     defaultOptions: {
       queries: {
         retry: 1,
         refetchOnWindowFocus: false,
         staleTime: 5 * 60 * 1000, // 5 minutes
       },
     },
   });
   
   function App() {
     return (
       <QueryClientProvider client={queryClient}>
         <BrowserRouter>
           <Router />
           <Toaster 
             position="top-right"
             toastOptions={{
               duration: 4000,
               style: {
                 background: '#363636',
                 color: '#fff',
               },
             }}
           />
         </BrowserRouter>
         <ReactQueryDevtools initialIsOpen={false} />
       </QueryClientProvider>
     );
   }
   
   export default App;
   ```

## Phase 2: Core UI Components and Layouts (Week 3-4)

### Task 2.1: Base Layout Components
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Create mobile-first layout wrapper**
   ```typescript
   // src/layouts/MainLayout.tsx
   
   import React, { useState } from 'react';
   import { Outlet } from 'react-router-dom';
   import Sidebar from '@/components/navigation/Sidebar';
   import MobileNavbar from '@/components/navigation/MobileNavbar';
   import MobileBottomNav from '@/components/navigation/MobileBottomNav';
   
   const MainLayout: React.FC = () => {
     const [sidebarOpen, setSidebarOpen] = useState(false);
     
     return (
       <div className="min-h-screen bg-gray-50">
         {/* Mobile navbar */}
         <div className="lg:hidden">
           <MobileNavbar onMenuClick={() => setSidebarOpen(true)} />
         </div>
         
         {/* Desktop sidebar */}
         <div className="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
           <Sidebar />
         </div>
         
         {/* Mobile sidebar */}
         <div className="lg:hidden">
           <Sidebar 
             mobile 
             open={sidebarOpen} 
             onClose={() => setSidebarOpen(false)} 
           />
         </div>
         
         {/* Main content */}
         <div className="lg:pl-64">
           <main className="py-6 px-4 sm:px-6 lg:px-8">
             <Outlet />
           </main>
         </div>
         
         {/* Mobile bottom navigation */}
         <div className="lg:hidden">
           <MobileBottomNav />
         </div>
       </div>
     );
   };
   
   export default MainLayout;
   ```

2. **Create mobile navigation components**
   ```typescript
   // src/components/navigation/MobileNavbar.tsx
   
   import React from 'react';
   import { Bars3Icon, MagnifyingGlassIcon } from '@heroicons/react/24/outline';
   
   interface MobileNavbarProps {
     onMenuClick: () => void;
   }
   
   const MobileNavbar: React.FC<MobileNavbarProps> = ({ onMenuClick }) => {
     return (
       <div className="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm">
         <button
           type="button"
           className="-m-2.5 p-2.5 text-gray-700 lg:hidden"
           onClick={onMenuClick}
         >
           <span className="sr-only">Open sidebar</span>
           <Bars3Icon className="h-6 w-6" aria-hidden="true" />
         </button>
         
         <div className="flex flex-1 items-center justify-between">
           <img
             className="h-8 w-auto"
             src="/logo.png"
             alt="SuiteCRM"
           />
           
           <button
             type="button"
             className="-m-2.5 p-2.5 text-gray-700"
           >
             <span className="sr-only">Search</span>
             <MagnifyingGlassIcon className="h-6 w-6" aria-hidden="true" />
           </button>
         </div>
       </div>
     );
   };
   
   export default MobileNavbar;
   ```

3. **Create bottom navigation**
   ```typescript
   // src/components/navigation/MobileBottomNav.tsx
   
   import React from 'react';
   import { NavLink } from 'react-router-dom';
   import {
     HomeIcon,
     PlusCircleIcon,
     MagnifyingGlassIcon,
     CalendarIcon,
     EllipsisHorizontalIcon,
   } from '@heroicons/react/24/outline';
   
   const navigation = [
     { name: 'Home', href: '/', icon: HomeIcon },
     { name: 'Create', href: '/create', icon: PlusCircleIcon },
     { name: 'Search', href: '/search', icon: MagnifyingGlassIcon },
     { name: 'Activities', href: '/activities', icon: CalendarIcon },
     { name: 'More', href: '/more', icon: EllipsisHorizontalIcon },
   ];
   
   const MobileBottomNav: React.FC = () => {
     return (
       <div className="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200">
         <nav className="flex justify-around">
           {navigation.map((item) => (
             <NavLink
               key={item.name}
               to={item.href}
               className={({ isActive }) =>
                 `flex flex-col items-center py-2 px-3 text-xs ${
                   isActive ? 'text-blue-600' : 'text-gray-500'
                 }`
               }
             >
               <item.icon className="h-6 w-6" />
               <span className="mt-1">{item.name}</span>
             </NavLink>
           ))}
         </nav>
       </div>
     );
   };
   
   export default MobileBottomNav;
   ```

### Task 2.2: Reusable UI Components
**Owner**: Junior Developer  
**Duration**: 3 days

1. **Create Button component**
   ```typescript
   // src/components/ui/Button.tsx
   
   import React from 'react';
   import { cva, type VariantProps } from 'class-variance-authority';
   
   const buttonVariants = cva(
     'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
     {
       variants: {
         variant: {
           primary: 'bg-blue-600 text-white hover:bg-blue-700',
           secondary: 'bg-gray-100 text-gray-900 hover:bg-gray-200',
           outline: 'border border-gray-300 bg-white hover:bg-gray-50',
           ghost: 'hover:bg-gray-100 hover:text-gray-900',
           danger: 'bg-red-600 text-white hover:bg-red-700',
         },
         size: {
           sm: 'h-9 px-3',
           md: 'h-10 px-4 py-2',
           lg: 'h-11 px-8',
           icon: 'h-10 w-10',
         },
       },
       defaultVariants: {
         variant: 'primary',
         size: 'md',
       },
     }
   );
   
   export interface ButtonProps
     extends React.ButtonHTMLAttributes<HTMLButtonElement>,
       VariantProps<typeof buttonVariants> {
     loading?: boolean;
   }
   
   const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
     ({ className, variant, size, loading, children, ...props }, ref) => {
       return (
         <button
           className={buttonVariants({ variant, size, className })}
           ref={ref}
           disabled={loading}
           {...props}
         >
           {loading && (
             <svg
               className="mr-2 h-4 w-4 animate-spin"
               xmlns="http://www.w3.org/2000/svg"
               fill="none"
               viewBox="0 0 24 24"
             >
               <circle
                 className="opacity-25"
                 cx="12"
                 cy="12"
                 r="10"
                 stroke="currentColor"
                 strokeWidth="4"
               ></circle>
               <path
                 className="opacity-75"
                 fill="currentColor"
                 d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
               ></path>
             </svg>
           )}
           {children}
         </button>
       );
     }
   );
   
   Button.displayName = 'Button';
   
   export default Button;
   ```

2. **Create Card component for mobile list views**
   ```typescript
   // src/components/ui/Card.tsx
   
   import React from 'react';
   import { ChevronRightIcon } from '@heroicons/react/24/outline';
   
   interface CardProps {
     title: string;
     subtitle?: string;
     meta?: Array<{ label: string; value: string }>;
     onClick?: () => void;
     actions?: React.ReactNode;
   }
   
   const Card: React.FC<CardProps> = ({
     title,
     subtitle,
     meta,
     onClick,
     actions,
   }) => {
     return (
       <div className="bg-white shadow rounded-lg overflow-hidden">
         <div 
           className="px-4 py-4 sm:px-6 cursor-pointer hover:bg-gray-50"
           onClick={onClick}
         >
           <div className="flex items-center justify-between">
             <div className="flex-1 min-w-0">
               <h3 className="text-base font-semibold text-gray-900 truncate">
                 {title}
               </h3>
               {subtitle && (
                 <p className="mt-1 text-sm text-gray-500">{subtitle}</p>
               )}
               {meta && (
                 <div className="mt-2 flex flex-wrap gap-x-4 gap-y-1">
                   {meta.map((item, index) => (
                     <p key={index} className="text-xs text-gray-500">
                       <span className="font-medium">{item.label}:</span> {item.value}
                     </p>
                   ))}
                 </div>
               )}
             </div>
             <ChevronRightIcon className="h-5 w-5 text-gray-400" />
           </div>
         </div>
         {actions && (
           <div className="px-4 py-3 bg-gray-50 sm:px-6">
             {actions}
           </div>
         )}
       </div>
     );
   };
   
   export default Card;
   ```

3. **Create Form components**
   ```typescript
   // src/components/form/Input.tsx
   
   import React from 'react';
   import { UseFormRegisterReturn } from 'react-hook-form';
   
   interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {
     label: string;
     error?: string;
     register?: UseFormRegisterReturn;
   }
   
   const Input = React.forwardRef<HTMLInputElement, InputProps>(
     ({ label, error, register, ...props }, ref) => {
       return (
         <div>
           <label className="block text-sm font-medium text-gray-700">
             {label}
             {props.required && <span className="text-red-500 ml-1">*</span>}
           </label>
           <input
             ref={ref}
             className={`mt-1 block w-full rounded-md shadow-sm sm:text-sm ${
               error
                 ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
                 : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'
             }`}
             {...register}
             {...props}
           />
           {error && (
             <p className="mt-1 text-sm text-red-600">{error}</p>
           )}
         </div>
       );
     }
   );
   
   Input.displayName = 'Input';
   
   export default Input;
   ```

## Phase 3: Module System Implementation (Week 5-6)

### Task 3.1: Module API Service
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Create module types**
   ```typescript
   // src/types/module.types.ts
   
   export interface Module {
     name: string;
     label: string;
     icon?: string;
     fields: Record<string, Field>;
   }
   
   export interface Field {
     name: string;
     type: string;
     label: string;
     required?: boolean;
     options?: Array<{ value: string; label: string }>;
     relationship?: {
       module: string;
       link: string;
     };
   }
   
   export interface Record {
     id: string;
     type: string;
     attributes: RecordAttributes;
     relationships?: RecordRelationships;
   }
   
   export interface RecordAttributes {
     [key: string]: any;
   }
   
   export interface RecordRelationships {
     [key: string]: {
       links: {
         related: string;
       };
       data?: {
         type: string;
         id: string;
       } | Array<{
         type: string;
         id: string;
       }>;
     };
   }
   
   export interface ListParams {
     page?: {
       number?: number;
       size?: number;
     };
     sort?: string;
     filter?: Record<string, any>;
     fields?: Record<string, string>;
   }
   ```

2. **Create module service**
   ```typescript
   // src/services/module.service.ts
   
   import apiClient from '@/api/client';
   import { ApiResponse, JsonApiResource } from '@/types/api.types';
   import { Module, Record, ListParams } from '@/types/module.types';
   import { buildQueryString } from '@/utils/queryString';
   
   class ModuleService {
     // Get module metadata
     async getModuleMetadata(moduleName: string): Promise<Module> {
       const response = await apiClient.get<any>(
         `/meta/fields/${moduleName}`
       );
       
       return {
         name: moduleName,
         label: response.data.attributes.module_name,
         fields: response.data.attributes.fields,
       };
     }
     
     // List records
     async listRecords(
       moduleName: string,
       params?: ListParams
     ): Promise<ApiResponse<Record[]>> {
       const queryString = buildQueryString(params);
       const url = `/module/${moduleName}${queryString ? `?${queryString}` : ''}`;
       
       const response = await apiClient.get<ApiResponse<JsonApiResource[]>>(url);
       
       return {
         ...response,
         data: response.data.map(this.transformRecord),
       };
     }
     
     // Get single record
     async getRecord(moduleName: string, id: string): Promise<Record> {
       const response = await apiClient.get<ApiResponse<JsonApiResource>>(
         `/module/${moduleName}/${id}`
       );
       
       return this.transformRecord(response.data);
     }
     
     // Create record
     async createRecord(
       moduleName: string,
       data: RecordAttributes
     ): Promise<Record> {
       const payload = {
         data: {
           type: moduleName,
           attributes: data,
         },
       };
       
       const response = await apiClient.post<ApiResponse<JsonApiResource>>(
         '/module',
         payload
       );
       
       return this.transformRecord(response.data);
     }
     
     // Update record
     async updateRecord(
       moduleName: string,
       id: string,
       data: Partial<RecordAttributes>
     ): Promise<Record> {
       const payload = {
         data: {
           type: moduleName,
           id,
           attributes: data,
         },
       };
       
       const response = await apiClient.patch<ApiResponse<JsonApiResource>>(
         '/module',
         payload
       );
       
       return this.transformRecord(response.data);
     }
     
     // Delete record
     async deleteRecord(moduleName: string, id: string): Promise<void> {
       await apiClient.delete(`/module/${moduleName}/${id}`);
     }
     
     // Transform JSON API resource to Record
     private transformRecord(resource: JsonApiResource): Record {
       return {
         id: resource.id,
         type: resource.type,
         attributes: resource.attributes,
         relationships: resource.relationships,
       };
     }
   }
   
   export default new ModuleService();
   ```

3. **Create React Query hooks**
   ```typescript
   // src/hooks/useModule.ts
   
   import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
   import moduleService from '@/services/module.service';
   import { ListParams, RecordAttributes } from '@/types/module.types';
   import toast from 'react-hot-toast';
   
   // Hook to get module metadata
   export const useModuleMetadata = (moduleName: string) => {
     return useQuery({
       queryKey: ['module-metadata', moduleName],
       queryFn: () => moduleService.getModuleMetadata(moduleName),
       staleTime: 30 * 60 * 1000, // 30 minutes
     });
   };
   
   // Hook to list records
   export const useModuleRecords = (moduleName: string, params?: ListParams) => {
     return useQuery({
       queryKey: ['module-records', moduleName, params],
       queryFn: () => moduleService.listRecords(moduleName, params),
     });
   };
   
   // Hook to get single record
   export const useModuleRecord = (moduleName: string, id: string) => {
     return useQuery({
       queryKey: ['module-record', moduleName, id],
       queryFn: () => moduleService.getRecord(moduleName, id),
       enabled: !!id,
     });
   };
   
   // Hook to create record
   export const useCreateRecord = (moduleName: string) => {
     const queryClient = useQueryClient();
     
     return useMutation({
       mutationFn: (data: RecordAttributes) =>
         moduleService.createRecord(moduleName, data),
       onSuccess: () => {
         queryClient.invalidateQueries({
           queryKey: ['module-records', moduleName],
         });
         toast.success('Record created successfully');
       },
       onError: (error: any) => {
         toast.error(error.message || 'Failed to create record');
       },
     });
   };
   
   // Hook to update record
   export const useUpdateRecord = (moduleName: string) => {
     const queryClient = useQueryClient();
     
     return useMutation({
       mutationFn: ({ id, data }: { id: string; data: Partial<RecordAttributes> }) =>
         moduleService.updateRecord(moduleName, id, data),
       onSuccess: (_, variables) => {
         queryClient.invalidateQueries({
           queryKey: ['module-record', moduleName, variables.id],
         });
         queryClient.invalidateQueries({
           queryKey: ['module-records', moduleName],
         });
         toast.success('Record updated successfully');
       },
       onError: (error: any) => {
         toast.error(error.message || 'Failed to update record');
       },
     });
   };
   
   // Hook to delete record
   export const useDeleteRecord = (moduleName: string) => {
     const queryClient = useQueryClient();
     
     return useMutation({
       mutationFn: (id: string) => moduleService.deleteRecord(moduleName, id),
       onSuccess: () => {
         queryClient.invalidateQueries({
           queryKey: ['module-records', moduleName],
         });
         toast.success('Record deleted successfully');
       },
       onError: (error: any) => {
         toast.error(error.message || 'Failed to delete record');
       },
     });
   };
   ```

### Task 3.2: Generic Module Views
**Owner**: Junior Developer  
**Duration**: 3 days

1. **Create List View component**
   ```typescript
   // src/modules/common/ListView.tsx
   
   import React, { useState } from 'react';
   import { useNavigate } from 'react-router-dom';
   import { PlusIcon } from '@heroicons/react/24/outline';
   import { useModuleRecords } from '@/hooks/useModule';
   import Card from '@/components/ui/Card';
   import Button from '@/components/ui/Button';
   import LoadingSpinner from '@/components/ui/LoadingSpinner';
   import Pagination from '@/components/ui/Pagination';
   import { formatDate } from '@/utils/date';
   
   interface ListViewProps {
     module: string;
     fields: {
       title: string;
       subtitle?: string;
       meta?: string[];
     };
   }
   
   const ListView: React.FC<ListViewProps> = ({ module, fields }) => {
     const navigate = useNavigate();
     const [page, setPage] = useState(1);
     const [pageSize] = useState(20);
     
     const { data, isLoading, error } = useModuleRecords(module, {
       page: { number: page, size: pageSize },
       sort: '-date_modified',
     });
     
     const handleCardClick = (id: string) => {
       navigate(`/modules/${module}/${id}`);
     };
     
     const handleCreate = () => {
       navigate(`/modules/${module}/create`);
     };
     
     if (isLoading) return <LoadingSpinner />;
     if (error) return <div>Error loading records</div>;
     
     return (
       <div>
         {/* Header */}
         <div className="sm:flex sm:items-center sm:justify-between mb-6">
           <h1 className="text-2xl font-semibold text-gray-900">
             {module}
           </h1>
           <Button
             onClick={handleCreate}
             size="sm"
             className="mt-3 sm:mt-0"
           >
             <PlusIcon className="h-4 w-4 mr-2" />
             Create New
           </Button>
         </div>
         
         {/* List */}
         <div className="space-y-3">
           {data?.data.map((record) => (
             <Card
               key={record.id}
               title={record.attributes[fields.title] || 'Untitled'}
               subtitle={
                 fields.subtitle
                   ? record.attributes[fields.subtitle]
                   : undefined
               }
               meta={
                 fields.meta?.map((field) => ({
                   label: field.charAt(0).toUpperCase() + field.slice(1),
                   value: field === 'date_modified'
                     ? formatDate(record.attributes[field])
                     : record.attributes[field] || '-',
                 }))
               }
               onClick={() => handleCardClick(record.id)}
             />
           ))}
         </div>
         
         {/* Pagination */}
         {data?.meta && data.meta['total-pages'] > 1 && (
           <Pagination
             currentPage={page}
             totalPages={data.meta['total-pages']}
             onPageChange={setPage}
           />
         )}
       </div>
     );
   };
   
   export default ListView;
   ```

2. **Create Detail View component**
   ```typescript
   // src/modules/common/DetailView.tsx
   
   import React from 'react';
   import { useParams, useNavigate } from 'react-router-dom';
   import { PencilIcon, TrashIcon } from '@heroicons/react/24/outline';
   import { useModuleRecord, useDeleteRecord } from '@/hooks/useModule';
   import { useModuleMetadata } from '@/hooks/useModule';
   import Button from '@/components/ui/Button';
   import LoadingSpinner from '@/components/ui/LoadingSpinner';
   import FieldDisplay from '@/components/ui/FieldDisplay';
   
   interface DetailViewProps {
     module: string;
   }
   
   const DetailView: React.FC<DetailViewProps> = ({ module }) => {
     const { id } = useParams<{ id: string }>();
     const navigate = useNavigate();
     
     const { data: record, isLoading: recordLoading } = useModuleRecord(
       module,
       id!
     );
     const { data: metadata, isLoading: metaLoading } = useModuleMetadata(module);
     const deleteRecord = useDeleteRecord(module);
     
     const handleEdit = () => {
       navigate(`/modules/${module}/${id}/edit`);
     };
     
     const handleDelete = async () => {
       if (window.confirm('Are you sure you want to delete this record?')) {
         await deleteRecord.mutateAsync(id!);
         navigate(`/modules/${module}`);
       }
     };
     
     if (recordLoading || metaLoading) return <LoadingSpinner />;
     if (!record || !metadata) return <div>Record not found</div>;
     
     // Group fields by panels
     const panels = groupFieldsByPanel(metadata.fields);
     
     return (
       <div>
         {/* Header */}
         <div className="bg-white shadow-sm border-b border-gray-200 -mx-4 -mt-6 px-4 py-4 sm:px-6 mb-6">
           <div className="flex items-center justify-between">
             <h1 className="text-2xl font-semibold text-gray-900">
               {record.attributes.name || 'Untitled'}
             </h1>
             <div className="flex space-x-3">
               <Button
                 variant="outline"
                 size="sm"
                 onClick={handleEdit}
               >
                 <PencilIcon className="h-4 w-4 mr-2" />
                 Edit
               </Button>
               <Button
                 variant="danger"
                 size="sm"
                 onClick={handleDelete}
               >
                 <TrashIcon className="h-4 w-4 mr-2" />
                 Delete
               </Button>
             </div>
           </div>
         </div>
         
         {/* Content panels */}
         <div className="space-y-6">
           {Object.entries(panels).map(([panelName, fields]) => (
             <div
               key={panelName}
               className="bg-white shadow rounded-lg px-4 py-5 sm:p-6"
             >
               <h3 className="text-lg font-medium text-gray-900 mb-4">
                 {panelName}
               </h3>
               <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                 {fields.map((field) => (
                   <FieldDisplay
                     key={field.name}
                     label={field.label}
                     value={record.attributes[field.name]}
                     type={field.type}
                   />
                 ))}
               </div>
             </div>
           ))}
         </div>
       </div>
     );
   };
   
   // Helper function to group fields by panel
   function groupFieldsByPanel(fields: Record<string, any>) {
     const panels: Record<string, any[]> = {
       'Basic Information': [],
       'Additional Details': [],
     };
     
     Object.values(fields).forEach((field) => {
       const panel = field.panel || 'Basic Information';
       if (!panels[panel]) panels[panel] = [];
       panels[panel].push(field);
     });
     
     return panels;
   }
   
   export default DetailView;
   ```

3. **Create Edit View component**
   ```typescript
   // src/modules/common/EditView.tsx
   
   import React, { useState } from 'react';
   import { useParams, useNavigate } from 'react-router-dom';
   import { useForm } from 'react-hook-form';
   import { yupResolver } from '@hookform/resolvers/yup';
   import * as yup from 'yup';
   import {
     useModuleRecord,
     useModuleMetadata,
     useCreateRecord,
     useUpdateRecord,
   } from '@/hooks/useModule';
   import Input from '@/components/form/Input';
   import Select from '@/components/form/Select';
   import Textarea from '@/components/form/Textarea';
   import Button from '@/components/ui/Button';
   import LoadingSpinner from '@/components/ui/LoadingSpinner';
   
   interface EditViewProps {
     module: string;
   }
   
   const EditView: React.FC<EditViewProps> = ({ module }) => {
     const { id } = useParams<{ id: string }>();
     const navigate = useNavigate();
     const isNew = !id;
     
     const { data: record } = useModuleRecord(module, id || '');
     const { data: metadata, isLoading: metaLoading } = useModuleMetadata(module);
     const createRecord = useCreateRecord(module);
     const updateRecord = useUpdateRecord(module);
     
     // Build validation schema from metadata
     const validationSchema = React.useMemo(() => {
       if (!metadata) return yup.object();
       
       const shape: any = {};
       Object.values(metadata.fields).forEach((field: any) => {
         if (field.required) {
           shape[field.name] = yup.string().required(`${field.label} is required`);
         }
       });
       
       return yup.object(shape);
     }, [metadata]);
     
     const {
       register,
       handleSubmit,
       formState: { errors, isSubmitting },
     } = useForm({
       resolver: yupResolver(validationSchema),
       defaultValues: record?.attributes || {},
     });
     
     const onSubmit = async (data: any) => {
       try {
         if (isNew) {
           await createRecord.mutateAsync(data);
         } else {
           await updateRecord.mutateAsync({ id, data });
         }
         navigate(`/modules/${module}${isNew ? '' : `/${id}`}`);
       } catch (error) {
         console.error('Failed to save record:', error);
       }
     };
     
     if (metaLoading) return <LoadingSpinner />;
     if (!metadata) return <div>Module not found</div>;
     
     // Group fields by panel for mobile step form
     const panels = groupFieldsByPanel(metadata.fields);
     const [currentPanel, setCurrentPanel] = useState(0);
     const panelNames = Object.keys(panels);
     
     return (
       <form onSubmit={handleSubmit(onSubmit)}>
         {/* Progress indicator for mobile */}
         <div className="mb-6 lg:hidden">
           <div className="flex items-center justify-between">
             {panelNames.map((_, index) => (
               <div
                 key={index}
                 className={`flex-1 h-2 mx-1 rounded ${
                   index <= currentPanel ? 'bg-blue-600' : 'bg-gray-200'
                 }`}
               />
             ))}
           </div>
           <p className="text-sm text-gray-600 mt-2">
             Step {currentPanel + 1} of {panelNames.length}
           </p>
         </div>
         
         {/* Form panels */}
         <div className="bg-white shadow rounded-lg px-4 py-5 sm:p-6">
           <h2 className="text-lg font-medium text-gray-900 mb-4">
             {isNew ? `Create ${module}` : `Edit ${module}`}
           </h2>
           
           {/* Desktop: Show all panels, Mobile: Show current panel */}
           <div className="space-y-6">
             {panelNames.map((panelName, panelIndex) => (
               <div
                 key={panelName}
                 className={`${
                   panelIndex === currentPanel ? 'block' : 'hidden lg:block'
                 }`}
               >
                 <h3 className="text-base font-medium text-gray-900 mb-4">
                   {panelName}
                 </h3>
                 <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                   {panels[panelName].map((field: any) => (
                     <div key={field.name}>
                       {renderField(field, register, errors)}
                     </div>
                   ))}
                 </div>
               </div>
             ))}
           </div>
           
           {/* Navigation buttons */}
           <div className="mt-6 flex flex-col sm:flex-row sm:justify-between gap-3">
             <div className="flex gap-3 order-2 sm:order-1">
               <Button
                 type="button"
                 variant="outline"
                 onClick={() => navigate(-1)}
               >
                 Cancel
               </Button>
             </div>
             
             <div className="flex gap-3 order-1 sm:order-2">
               {/* Mobile panel navigation */}
               <div className="flex gap-3 lg:hidden">
                 {currentPanel > 0 && (
                   <Button
                     type="button"
                     variant="secondary"
                     onClick={() => setCurrentPanel(currentPanel - 1)}
                   >
                     Previous
                   </Button>
                 )}
                 {currentPanel < panelNames.length - 1 && (
                   <Button
                     type="button"
                     onClick={() => setCurrentPanel(currentPanel + 1)}
                   >
                     Next
                   </Button>
                 )}
               </div>
               
               {/* Submit button */}
               {(currentPanel === panelNames.length - 1 || window.innerWidth >= 1024) && (
                 <Button type="submit" loading={isSubmitting}>
                   {isNew ? 'Create' : 'Save'}
                 </Button>
               )}
             </div>
           </div>
         </div>
       </form>
     );
   };
   
   // Helper function to render field based on type
   function renderField(field: any, register: any, errors: any) {
     const commonProps = {
       label: field.label,
       error: errors[field.name]?.message,
       register: register(field.name),
       required: field.required,
     };
     
     switch (field.type) {
       case 'enum':
         return (
           <Select {...commonProps} options={field.options || []} />
         );
       case 'text':
         return <Textarea {...commonProps} rows={3} />;
       case 'date':
         return <Input {...commonProps} type="date" />;
       case 'datetime':
         return <Input {...commonProps} type="datetime-local" />;
       case 'email':
         return <Input {...commonProps} type="email" />;
       case 'phone':
         return <Input {...commonProps} type="tel" />;
       case 'url':
         return <Input {...commonProps} type="url" />;
       case 'int':
       case 'float':
       case 'decimal':
         return <Input {...commonProps} type="number" />;
       case 'bool':
         return (
           <div className="flex items-center">
             <input
               type="checkbox"
               {...register(field.name)}
               className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
             />
             <label className="ml-2 block text-sm text-gray-900">
               {field.label}
             </label>
           </div>
         );
       default:
         return <Input {...commonProps} />;
     }
   }
   
   // Helper function to group fields by panel
   function groupFieldsByPanel(fields: Record<string, any>) {
     const panels: Record<string, any[]> = {};
     
     Object.values(fields).forEach((field) => {
       const panel = field.panel || 'Basic Information';
       if (!panels[panel]) panels[panel] = [];
       panels[panel].push(field);
     });
     
     return panels;
   }
   
   export default EditView;
   ```

## Phase 4: Advanced Features (Week 7-8)

### Task 4.1: Global Search
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Create search service**
   ```typescript
   // src/services/search.service.ts
   
   import apiClient from '@/api/client';
   
   interface SearchParams {
     query: string;
     modules?: string[];
     limit?: number;
   }
   
   interface SearchResult {
     module: string;
     records: Array<{
       id: string;
       name: string;
       summary: string;
     }>;
   }
   
   class SearchService {
     async globalSearch(params: SearchParams): Promise<SearchResult[]> {
       // This would need a custom API endpoint
       // For now, we'll search each module individually
       const modules = params.modules || ['Accounts', 'Contacts', 'Leads'];
       const results: SearchResult[] = [];
       
       for (const module of modules) {
         try {
           const response = await apiClient.get(`/module/${module}`, {
             params: {
               'filter[name][like]': `%${params.query}%`,
               'page[size]': params.limit || 5,
             },
           });
           
           if (response.data.length > 0) {
             results.push({
               module,
               records: response.data.map((record: any) => ({
                 id: record.id,
                 name: record.attributes.name || 'Untitled',
                 summary: this.generateSummary(record.attributes),
               })),
             });
           }
         } catch (error) {
           console.error(`Failed to search ${module}:`, error);
         }
       }
       
       return results;
     }
     
     private generateSummary(attributes: any): string {
       const fields = ['email', 'phone', 'account_name', 'title'];
       const summaryParts = [];
       
       for (const field of fields) {
         if (attributes[field]) {
           summaryParts.push(attributes[field]);
         }
       }
       
       return summaryParts.join(' • ');
     }
   }
   
   export default new SearchService();
   ```

2. **Create search UI component**
   ```typescript
   // src/components/search/GlobalSearch.tsx
   
   import React, { useState, useCallback } from 'react';
   import { useNavigate } from 'react-router-dom';
   import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';
   import { useDebounce } from '@/hooks/useDebounce';
   import searchService from '@/services/search.service';
   
   const GlobalSearch: React.FC = () => {
     const navigate = useNavigate();
     const [query, setQuery] = useState('');
     const [results, setResults] = useState<any[]>([]);
     const [isLoading, setIsLoading] = useState(false);
     const [isOpen, setIsOpen] = useState(false);
     
     const debouncedQuery = useDebounce(query, 300);
     
     React.useEffect(() => {
       if (debouncedQuery.length >= 2) {
         performSearch();
       } else {
         setResults([]);
       }
     }, [debouncedQuery]);
     
     const performSearch = useCallback(async () => {
       setIsLoading(true);
       try {
         const searchResults = await searchService.globalSearch({
           query: debouncedQuery,
         });
         setResults(searchResults);
       } catch (error) {
         console.error('Search failed:', error);
       } finally {
         setIsLoading(false);
       }
     }, [debouncedQuery]);
     
     const handleResultClick = (module: string, id: string) => {
       navigate(`/modules/${module}/${id}`);
       setQuery('');
       setIsOpen(false);
     };
     
     return (
       <div className="relative">
         <div className="relative">
           <MagnifyingGlassIcon className="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
           <input
             type="text"
             className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
             placeholder="Search..."
             value={query}
             onChange={(e) => {
               setQuery(e.target.value);
               setIsOpen(true);
             }}
             onFocus={() => setIsOpen(true)}
           />
         </div>
         
         {isOpen && query.length >= 2 && (
           <div className="absolute z-50 w-full mt-2 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto">
             {isLoading ? (
               <div className="p-4 text-center text-gray-500">
                 Searching...
               </div>
             ) : results.length === 0 ? (
               <div className="p-4 text-center text-gray-500">
                 No results found
               </div>
             ) : (
               <div className="py-2">
                 {results.map((group) => (
                   <div key={group.module}>
                     <div className="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">
                       {group.module}
                     </div>
                     {group.records.map((record: any) => (
                       <button
                         key={record.id}
                         className="w-full px-4 py-2 text-left hover:bg-gray-50 focus:bg-gray-50 focus:outline-none"
                         onClick={() => handleResultClick(group.module, record.id)}
                       >
                         <div className="font-medium text-gray-900">
                           {record.name}
                         </div>
                         {record.summary && (
                           <div className="text-sm text-gray-500">
                             {record.summary}
                           </div>
                         )}
                       </button>
                     ))}
                   </div>
                 ))}
               </div>
             )}
           </div>
         )}
       </div>
     );
   };
   
   export default GlobalSearch;
   ```

### Task 4.2: Offline Support & PWA
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Create service worker**
   ```javascript
   // public/service-worker.js
   
   const CACHE_NAME = 'suitecrm-react-v1';
   const urlsToCache = [
     '/',
     '/static/css/main.css',
     '/static/js/main.js',
     '/manifest.json',
   ];
   
   // Install event
   self.addEventListener('install', (event) => {
     event.waitUntil(
       caches.open(CACHE_NAME).then((cache) => {
         return cache.addAll(urlsToCache);
       })
     );
   });
   
   // Activate event
   self.addEventListener('activate', (event) => {
     event.waitUntil(
       caches.keys().then((cacheNames) => {
         return Promise.all(
           cacheNames.map((cacheName) => {
             if (cacheName !== CACHE_NAME) {
               return caches.delete(cacheName);
             }
           })
         );
       })
     );
   });
   
   // Fetch event
   self.addEventListener('fetch', (event) => {
     event.respondWith(
       caches.match(event.request).then((response) => {
         // Cache hit - return response
         if (response) {
           return response;
         }
         
         return fetch(event.request).then((response) => {
           // Check if valid response
           if (!response || response.status !== 200 || response.type !== 'basic') {
             return response;
           }
           
           // Clone the response
           const responseToCache = response.clone();
           
           caches.open(CACHE_NAME).then((cache) => {
             cache.put(event.request, responseToCache);
           });
           
           return response;
         });
       })
     );
   });
   
   // Background sync for offline requests
   self.addEventListener('sync', (event) => {
     if (event.tag === 'sync-records') {
       event.waitUntil(syncOfflineRecords());
     }
   });
   
   async function syncOfflineRecords() {
     // Get offline queue from IndexedDB
     const db = await openDB();
     const tx = db.transaction('offline-queue', 'readonly');
     const store = tx.objectStore('offline-queue');
     const requests = await store.getAll();
     
     for (const request of requests) {
       try {
         await fetch(request.url, {
           method: request.method,
           headers: request.headers,
           body: request.body,
         });
         
         // Remove from queue after successful sync
         await removeFromQueue(request.id);
       } catch (error) {
         console.error('Failed to sync request:', error);
       }
     }
   }
   ```

2. **Create manifest.json**
   ```json
   // public/manifest.json
   {
     "short_name": "SuiteCRM",
     "name": "SuiteCRM Mobile",
     "icons": [
       {
         "src": "icon-192.png",
         "sizes": "192x192",
         "type": "image/png"
       },
       {
         "src": "icon-512.png",
         "sizes": "512x512",
         "type": "image/png"
       }
     ],
     "start_url": "/",
     "display": "standalone",
     "theme_color": "#2563eb",
     "background_color": "#ffffff",
     "orientation": "portrait"
   }
   ```

3. **Create offline queue manager**
   ```typescript
   // src/services/offline.service.ts
   
   import { openDB, DBSchema } from 'idb';
   
   interface OfflineDB extends DBSchema {
     'offline-queue': {
       key: string;
       value: {
         id: string;
         timestamp: number;
         url: string;
         method: string;
         headers: Record<string, string>;
         body: any;
       };
     };
   }
   
   class OfflineService {
     private dbPromise = openDB<OfflineDB>('suitecrm-offline', 1, {
       upgrade(db) {
         db.createObjectStore('offline-queue', { keyPath: 'id' });
       },
     });
     
     async addToQueue(request: any) {
       const db = await this.dbPromise;
       await db.add('offline-queue', {
         id: `${Date.now()}-${Math.random()}`,
         timestamp: Date.now(),
         ...request,
       });
       
       // Register sync
       if ('serviceWorker' in navigator && 'sync' in self.registration) {
         await (self.registration as any).sync.register('sync-records');
       }
     }
     
     async getQueuedRequests() {
       const db = await this.dbPromise;
       return db.getAll('offline-queue');
     }
     
     async removeFromQueue(id: string) {
       const db = await this.dbPromise;
       await db.delete('offline-queue', id);
     }
     
     isOnline() {
       return navigator.onLine;
     }
     
     watchConnectivity(callback: (online: boolean) => void) {
       window.addEventListener('online', () => callback(true));
       window.addEventListener('offline', () => callback(false));
     }
   }
   
   export default new OfflineService();
   ```

## Phase 5: Testing and Optimization (Week 9-10)

### Task 5.1: Unit Testing Setup
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Install testing dependencies**
   ```bash
   npm install --save-dev \
     @testing-library/react \
     @testing-library/jest-dom \
     @testing-library/user-event \
     @testing-library/react-hooks \
     jest-environment-jsdom \
     msw
   ```

2. **Create test utilities**
   ```typescript
   // src/test/utils.tsx
   
   import React from 'react';
   import { render } from '@testing-library/react';
   import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
   import { BrowserRouter } from 'react-router-dom';
   
   const createTestQueryClient = () =>
     new QueryClient({
       defaultOptions: {
         queries: {
           retry: false,
         },
       },
     });
   
   export function renderWithProviders(
     ui: React.ReactElement,
     options = {}
   ) {
     const queryClient = createTestQueryClient();
     
     function Wrapper({ children }: { children: React.ReactNode }) {
       return (
         <QueryClientProvider client={queryClient}>
           <BrowserRouter>
             {children}
           </BrowserRouter>
         </QueryClientProvider>
       );
     }
     
     return render(ui, { wrapper: Wrapper, ...options });
   }
   
   export * from '@testing-library/react';
   ```

3. **Create example component test**
   ```typescript
   // src/components/ui/Button.test.tsx
   
   import { screen } from '@testing-library/react';
   import userEvent from '@testing-library/user-event';
   import { renderWithProviders } from '@/test/utils';
   import Button from './Button';
   
   describe('Button', () => {
     it('renders with text', () => {
       renderWithProviders(<Button>Click me</Button>);
       expect(screen.getByText('Click me')).toBeInTheDocument();
     });
     
     it('handles click events', async () => {
       const handleClick = jest.fn();
       const user = userEvent.setup();
       
       renderWithProviders(
         <Button onClick={handleClick}>Click me</Button>
       );
       
       await user.click(screen.getByText('Click me'));
       expect(handleClick).toHaveBeenCalledTimes(1);
     });
     
     it('shows loading state', () => {
       renderWithProviders(<Button loading>Save</Button>);
       expect(screen.getByText('Save')).toBeInTheDocument();
       expect(screen.getByRole('button')).toBeDisabled();
     });
   });
   ```

### Task 5.2: Performance Optimization
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Implement code splitting**
   ```typescript
   // src/Router.tsx
   
   import React, { Suspense, lazy } from 'react';
   import { Routes, Route } from 'react-router-dom';
   import LoadingSpinner from '@/components/ui/LoadingSpinner';
   import ProtectedRoute from '@/components/auth/ProtectedRoute';
   
   // Lazy load pages
   const Login = lazy(() => import('@/pages/Login'));
   const Dashboard = lazy(() => import('@/pages/Dashboard'));
   const ModuleRouter = lazy(() => import('@/modules/ModuleRouter'));
   
   const Router: React.FC = () => {
     return (
       <Suspense fallback={<LoadingSpinner />}>
         <Routes>
           <Route path="/login" element={<Login />} />
           <Route
             path="/*"
             element={
               <ProtectedRoute>
                 <Routes>
                   <Route path="/" element={<Dashboard />} />
                   <Route path="/modules/*" element={<ModuleRouter />} />
                 </Routes>
               </ProtectedRoute>
             }
           />
         </Routes>
       </Suspense>
     );
   };
   
   export default Router;
   ```

2. **Add image optimization**
   ```typescript
   // src/components/ui/OptimizedImage.tsx
   
   import React, { useState } from 'react';
   
   interface OptimizedImageProps {
     src: string;
     alt: string;
     className?: string;
     sizes?: string;
   }
   
   const OptimizedImage: React.FC<OptimizedImageProps> = ({
     src,
     alt,
     className,
     sizes,
   }) => {
     const [isLoading, setIsLoading] = useState(true);
     const [error, setError] = useState(false);
     
     // Generate responsive image URLs
     const srcSet = `
       ${src}?w=320 320w,
       ${src}?w=640 640w,
       ${src}?w=1024 1024w
     `;
     
     return (
       <div className={`relative ${className}`}>
         {isLoading && (
           <div className="absolute inset-0 bg-gray-200 animate-pulse" />
         )}
         {error ? (
           <div className="absolute inset-0 bg-gray-100 flex items-center justify-center">
             <span className="text-gray-500">Failed to load image</span>
           </div>
         ) : (
           <img
             src={`${src}?w=640`}
             srcSet={srcSet}
             sizes={sizes || '100vw'}
             alt={alt}
             loading="lazy"
             onLoad={() => setIsLoading(false)}
             onError={() => {
               setIsLoading(false);
               setError(true);
             }}
             className={`${isLoading ? 'opacity-0' : 'opacity-100'} transition-opacity duration-300`}
           />
         )}
       </div>
     );
   };
   
   export default OptimizedImage;
   ```

3. **Add performance monitoring**
   ```typescript
   // src/utils/performance.ts
   
   export const measurePerformance = {
     mark(name: string) {
       if ('performance' in window) {
         performance.mark(name);
       }
     },
     
     measure(name: string, startMark: string, endMark: string) {
       if ('performance' in window) {
         try {
           performance.measure(name, startMark, endMark);
           const measure = performance.getEntriesByName(name)[0];
           console.log(`${name}: ${measure.duration}ms`);
         } catch (error) {
           console.error('Performance measurement error:', error);
         }
       }
     },
     
     reportWebVitals() {
       if ('web-vital' in window) {
         import('web-vitals').then(({ getCLS, getFID, getFCP, getLCP, getTTFB }) => {
           getCLS(console.log);
           getFID(console.log);
           getFCP(console.log);
           getLCP(console.log);
           getTTFB(console.log);
         });
       }
     },
   };
   ```

## Phase 6: Deployment and Migration (Week 11-12)

### Task 6.1: Build Configuration
**Owner**: Junior Developer  
**Duration**: 1 day

1. **Configure production build**
   ```javascript
   // craco.config.js
   
   module.exports = {
     webpack: {
       configure: (webpackConfig) => {
         // Optimize bundle size
         webpackConfig.optimization = {
           ...webpackConfig.optimization,
           splitChunks: {
             chunks: 'all',
             cacheGroups: {
               vendor: {
                 test: /[\\/]node_modules[\\/]/,
                 name: 'vendor',
                 priority: 10,
               },
               common: {
                 minChunks: 2,
                 priority: 5,
                 reuseExistingChunk: true,
               },
             },
           },
         };
         
         return webpackConfig;
       },
     },
   };
   ```

2. **Create deployment scripts**
   ```json
   // package.json scripts
   {
     "scripts": {
       "build:staging": "REACT_APP_ENV=staging npm run build",
       "build:production": "REACT_APP_ENV=production npm run build",
       "deploy:staging": "npm run build:staging && rsync -avz --delete build/ user@staging:/var/www/suitecrm-react/",
       "deploy:production": "npm run build:production && rsync -avz --delete build/ user@production:/var/www/suitecrm-react/"
     }
   }
   ```

3. **Create nginx configuration**
   ```nginx
   # /etc/nginx/sites-available/suitecrm-react
   
   server {
       listen 80;
       server_name app.suitecrm.com;
       return 301 https://$server_name$request_uri;
   }
   
   server {
       listen 443 ssl http2;
       server_name app.suitecrm.com;
       
       ssl_certificate /etc/ssl/certs/suitecrm.crt;
       ssl_certificate_key /etc/ssl/private/suitecrm.key;
       
       root /var/www/suitecrm-react;
       index index.html;
       
       # Gzip compression
       gzip on;
       gzip_vary on;
       gzip_min_length 1024;
       gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
       
       # Cache static assets
       location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
           expires 1y;
           add_header Cache-Control "public, immutable";
       }
       
       # SPA routing
       location / {
           try_files $uri $uri/ /index.html;
       }
       
       # Security headers
       add_header X-Frame-Options "SAMEORIGIN" always;
       add_header X-Content-Type-Options "nosniff" always;
       add_header X-XSS-Protection "1; mode=block" always;
       add_header Referrer-Policy "no-referrer-when-downgrade" always;
       add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
   }
   ```

### Task 6.2: Migration Strategy
**Owner**: Junior Developer  
**Duration**: 2 days

1. **Create migration documentation**
   ```markdown
   # SuiteCRM React Migration Guide
   
   ## Overview
   This guide outlines the process for migrating from the legacy SuiteCRM UI to the new React-based interface.
   
   ## Migration Phases
   
   ### Phase 1: Parallel Deployment (Week 1-2)
   - Deploy React app to app.domain.com
   - Keep legacy app at crm.domain.com
   - No data migration required (shared database)
   
   ### Phase 2: User Training (Week 3-4)
   - Create training materials
   - Conduct training sessions
   - Gather feedback
   
   ### Phase 3: Gradual Migration (Week 5-8)
   - Migrate department by department
   - Monitor usage and issues
   - Address feedback
   
   ### Phase 4: Full Cutover (Week 9-10)
   - Redirect legacy URLs to new app
   - Maintain legacy app in read-only mode
   - Final data validation
   
   ## User Migration Steps
   
   1. **Account Setup**
      - Users log in with existing credentials
      - OAuth2 client automatically created
      - Preferences migrated
   
   2. **Data Access**
      - All data available immediately
      - Saved filters need recreation
      - Dashboards need reconfiguration
   
   3. **Training Resources**
      - Video tutorials
      - Interactive walkthrough
      - Help documentation
   
   ## Rollback Plan
   
   If issues arise:
   1. Revert nginx configuration
   2. Direct users back to legacy app
   3. Investigate and fix issues
   4. Retry migration
   ```

2. **Create user onboarding flow**
   ```typescript
   // src/components/onboarding/OnboardingTour.tsx
   
   import React, { useState } from 'react';
   import { useAppStore } from '@/store';
   import Button from '@/components/ui/Button';
   
   const steps = [
     {
       target: '.mobile-navbar',
       title: 'Welcome to the New SuiteCRM',
       content: 'This is your new mobile-friendly navigation bar.',
     },
     {
       target: '.module-list',
       title: 'Access Your Modules',
       content: 'All your CRM modules are available here.',
     },
     {
       target: '.global-search',
       title: 'Quick Search',
       content: 'Search across all your CRM data instantly.',
     },
     {
       target: '.create-button',
       title: 'Create Records',
       content: 'Quickly create new records from anywhere.',
     },
   ];
   
   const OnboardingTour: React.FC = () => {
     const [currentStep, setCurrentStep] = useState(0);
     const [isActive, setIsActive] = useState(true);
     const { user } = useAppStore();
     
     // Check if user has completed onboarding
     React.useEffect(() => {
       const hasCompleted = localStorage.getItem(`onboarding_${user?.id}`);
       if (hasCompleted) {
         setIsActive(false);
       }
     }, [user]);
     
     const handleNext = () => {
       if (currentStep < steps.length - 1) {
         setCurrentStep(currentStep + 1);
       } else {
         completeOnboarding();
       }
     };
     
     const handleSkip = () => {
       completeOnboarding();
     };
     
     const completeOnboarding = () => {
       localStorage.setItem(`onboarding_${user?.id}`, 'true');
       setIsActive(false);
     };
     
     if (!isActive) return null;
     
     const step = steps[currentStep];
     
     return (
       <div className="fixed inset-0 z-50 bg-black bg-opacity-50">
         <div className="absolute top-20 left-4 right-4 bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto">
           <h3 className="text-lg font-semibold mb-2">{step.title}</h3>
           <p className="text-gray-600 mb-4">{step.content}</p>
           
           <div className="flex justify-between">
             <Button variant="ghost" onClick={handleSkip}>
               Skip Tour
             </Button>
             <div className="space-x-2">
               {currentStep > 0 && (
                 <Button
                   variant="outline"
                   onClick={() => setCurrentStep(currentStep - 1)}
                 >
                   Back
                 </Button>
               )}
               <Button onClick={handleNext}>
                 {currentStep < steps.length - 1 ? 'Next' : 'Finish'}
               </Button>
             </div>
           </div>
         </div>
       </div>
     );
   };
   
   export default OnboardingTour;
   ```

## Maintenance and Future Enhancements

### Regular Maintenance Tasks
1. **Dependency Updates**
   ```bash
   # Check for updates
   npm outdated
   
   # Update dependencies
   npm update
   
   # Security audit
   npm audit fix
   ```

2. **Performance Monitoring**
   - Set up Google Analytics
   - Monitor Core Web Vitals
   - Track API response times
   - Monitor error rates

3. **User Feedback**
   - Implement feedback widget
   - Regular user surveys
   - Usage analytics
   - Feature request tracking

### Future Enhancement Ideas
1. **Advanced Features**
   - Real-time notifications (WebSocket)
   - Offline record creation
   - Voice input support
   - Biometric authentication
   
2. **Native Mobile Apps**
   - React Native implementation
   - Share code with web app
   - Push notifications
   - Device-specific features

3. **AI Integration**
   - Smart search suggestions
   - Predictive text
   - Data insights
   - Automated workflows

## Conclusion
This implementation plan provides a comprehensive approach to building a modern React-based UI for SuiteCRM. The modular architecture ensures maintainability, the mobile-first design provides excellent user experience, and the API-first approach enables future extensibility. Each phase builds upon the previous one, allowing for incremental development and testing.
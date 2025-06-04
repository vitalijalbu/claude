export interface ApiData<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
  links: Array<{ url: string | null; label: string; active: boolean; }>;
}
export interface ListParams { page?: number; per_page?: number; filters?: Record<string, any>; }

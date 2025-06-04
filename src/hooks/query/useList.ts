import { ApiData, ListParams } from "@/types/query";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api";

export const useList = <T>(
  resource: string,
  params: ListParams = {},
  options?: Omit<UseQueryOptions<ApiData<T>>, 'queryKey' | 'queryFn'>
) => {
  return useQuery({
    queryKey: [resource, 'list', params],
    queryFn: async () => {
      const query = new URLSearchParams();
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          if (typeof value === 'object') {
            query.append(key, JSON.stringify(value));
          } else {
            query.append(key, String(value));
          }
        }
      });
      const { data } = await apiClient.get<ApiData<T>>(`/${resource}${query.toString() ? `?${query}` : ''}`);
      return data;
    },
    staleTime: 5 * 60 * 1000,
    refetchOnWindowFocus: false,
    ...options,
  });
};
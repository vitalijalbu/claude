import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api";

export const useGet = <T>(resource: string, id: string | number | null | undefined) => {
  return useQuery({
    queryKey: [resource, 'detail', id],
    queryFn: async () => {
      const { data } = await apiClient.get<T>(`/${resource}/${id}`);
      return data;
    },
    enabled: id !== null && id !== undefined && id !== '',
    staleTime: 5 * 60 * 1000,
    refetchOnWindowFocus: false,
  });
};

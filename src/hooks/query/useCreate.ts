import { apiClient } from "@/lib/api";
import { useMutation, useQueryClient } from "@tanstack/react-query";

export const useCreate = <T, V = any>(resource: string) => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (variables: V) => {
      const { data } = await apiClient.post<T>(`/${resource}`, variables);
      return data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: [resource, 'list'] });
    },
  });
};
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { apiClient } from "@/lib/api";

export const useUpdate = <T, V = any>(resource: string) => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, data }: { id: string | number; data: V }) => {
      const response = await apiClient.put<T>(`/${resource}/${id}`, data);
      return response.data;
    },
    onSuccess: (_, { id }) => {
      queryClient.invalidateQueries({ queryKey: [resource, 'list'] });
      queryClient.invalidateQueries({ queryKey: [resource, 'detail', id] });
    },
  });
};

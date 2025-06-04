import { useMutation, useQueryClient } from "@tanstack/react-query";
import { apiClient } from "@/lib/api";

export const useDelete = (resource: string) => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: string | number) => {
      await apiClient.delete(`/${resource}/${id}`);
      return id;
    },
    onSuccess: (id) => {
      queryClient.invalidateQueries({ queryKey: [resource, 'list'] });
      queryClient.removeQueries({ queryKey: [resource, 'detail', id] });
    },
  });
};

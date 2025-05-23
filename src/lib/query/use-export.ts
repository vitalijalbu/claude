import { useMutation } from "@tanstack/react-query";
import { exportData as exportDataApi } from "@/lib/api/strapi";
import { toast } from "sonner";

export const useExport = () => {
  return useMutation({
    mutationFn: (filters) => exportDataApi(filters),
    onSuccess: (data) => {
      toast.success("Esportazione completata");
    },
    onError: (error) => {
      toast.error("Errore nell'esportazione :" + error.message);
    },
  });
};

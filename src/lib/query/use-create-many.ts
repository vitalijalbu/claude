import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createMany } from "../api/strapi";
import { toast } from "sonner";
import { getSession } from "@/lib/session";

export const useCreateMany = () => {
  const queryClient = useQueryClient();
  const { user } = getSession();

  return useMutation({
    mutationFn: ({ resource, body }) => {
      return createMany({ resource, body });
    },
    onError: (error) => {
      toast.error("Errore nella creazione dell'elemento:", error);
    },
    onSuccess: ({ data, resource }) => {
      toast.success(`Elementi creati con successo`);
    },
    onSettled: ({data, error, variables, resource}) => {
      if (resource) {
        queryClient.invalidateQueries([resource]);
      } else {
        console.error("No resource found in mutation variables");
      }
    },
  });
};

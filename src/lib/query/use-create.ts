import { useMutation, useQueryClient } from "@tanstack/react-query";
import { create } from "../api/strapi";
import { toast } from "sonner";
import { getSession } from "@/lib/session";
import { getWeatherData } from "../api/weather";

export const useCreate = () => {
  const queryClient = useQueryClient();
  const { user } = getSession();

  return useMutation({
    mutationFn: async ({ resource, body, appendUser = false, appendWeather = false }) => {
      try {
        let requestBody = appendUser ? { ...body, user: user?.id } : { ...body };

        if (appendWeather) {
          const weatherData = await getWeatherData(); // Get weather data
          requestBody = { ...requestBody, weather: weatherData };
        }

        return create({ resource, body: requestBody });
      } catch (error) {
        console.error("Errore durante il recupero dei dati meteo o la creazione:", error);
        throw error;
      }
    },
    onError: (error) => {
      toast.error(`Errore nella creazione dell'elemento: ${error.message}`);
    },
    onSuccess: ({ data, resource }) => {
      toast.success("Elemento creato con successo");
    },
    onSettled: (_, __, { resource }) => {
      queryClient.invalidateQueries([resource]);
    },
  });
};

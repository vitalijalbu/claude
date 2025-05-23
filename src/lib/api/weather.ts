import axios from "axios";
import { getDefaultStore } from "jotai";
import { geoAtom } from "../store";

const airQualityDescriptions = {
  1: "buona", 2: "discreta", 3: "moderata", 4: "scarsa", 5: "molto scarsa"
};

// Get Weather Data
export const getWeatherData = async () => {
  const { lat, lon } = getDefaultStore().get(geoAtom);
  if (!lat || !lon) return null;
  
  const response = await axios.get("https://api.openweathermap.org/data/2.5/weather", {
    params: { lat, lon, units: "metric", lang: "it", appid: "54665b46ac95ec3ebede5359de28cd26" }
  });

  const { main, weather, wind } = response.data;
  const qualitaAria = await getAirPollutionData(lat, lon);

  return {
    temperatura: `${main.temp.toFixed(1)} °C`,
    precipitazioni: weather[0]?.description || "nessuna precipitazione",
    umidita: `${main.humidity} %`,
    vento: `${(wind.speed * 3.6).toFixed(1)} km/h`,
    pressione_atmosferica: `${main.pressure} mb`,
    qualita_aria: qualitaAria
  };
};


// Get Air Pollution Data
const getAirPollutionData = async (lat, lon) => {
  const response = await axios.get("https://api.openweathermap.org/data/2.5/air_pollution", {
    params: { lat, lon, appid: "54665b46ac95ec3ebede5359de28cd26" }
  });

  const aqi = response.data.list[0]?.main?.aqi;
  return airQualityDescriptions[aqi] || "Dati non disponibili";
};

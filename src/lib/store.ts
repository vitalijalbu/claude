import { atom } from "jotai";

// Sidebar atom admin dashboard
export const sidebarAtom = atom(false);

// Geolocation atom
export const geoAtom = atom({
    lat: null,
    lon: null
});
import axios from 'axios';

export const apiClient = axios.create({
  baseURL: import.meta.env.PUBLIC_API_URL || "/api",
  withCredentials: true,
  headers: {
    "Content-Type": "application/json",
  },
});

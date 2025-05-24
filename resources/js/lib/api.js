import { apiClient } from "./client";

// Upload products to WooCommerce
export const uploadCatalog = async (body) => {
    try {
        const response = await apiClient.post(`/listings/upload`, body);
        return response.data;
    } catch (error) {
        console.error("Error downloading catalog:", error);
        throw error;
    }
};

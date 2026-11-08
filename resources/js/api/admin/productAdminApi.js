export const productAdminApi = {
    createProduct: async (productData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.post('/api/admin/products', productData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    },

    updateProduct: async (id, productData) => {
        try {
            const token = localStorage.getItem('auth_token');
            
            if (productData instanceof FormData && !productData.has('_method')) {
                productData.append('_method', 'PUT');
            }

            const response = await window.axios.post(`/api/admin/products/${id}`, productData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    },

    deleteProduct: async (id) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.delete(`/api/admin/products/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    },

    deleteSubImage: async (subImageId) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.delete(`/api/admin/product-images/${subImageId}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    },

    setMainImage: async (productId, mainImagePath) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.put(`/api/admin/products/${productId}/set-main-image`, {
                main_image_path: mainImagePath
            }, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    }
};
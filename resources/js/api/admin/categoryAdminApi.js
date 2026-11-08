export const categoryAdminApi = {
    createCategory: async (categoryData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.post('/api/admin/categories', categoryData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    },

    updateCategory: async (id, categoryData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.put(`/api/admin/categories/${id}`, categoryData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    },

    deleteCategory: async (id) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.delete(`/api/admin/categories/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { ok: false, status: error.response?.status || 500, data: error.response?.data };
        }
    }
};
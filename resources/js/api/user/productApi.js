// Sản phẩm & Danh mục phía User/Guest
export const productApi = {
    getProducts: async (params = {}) => {
        try {
            const response = await window.axios.get('/api/products', { params });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Không thể tải danh sách sản phẩm!' } 
            };
        }
    },

    getProductDetail: async (id) => {
        try {
            const response = await window.axios.get(`/api/products/${id}`);
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Không tìm thấy chi tiết sản phẩm!' } 
            };
        }
    },

    getCategories: async () => {
        try {
            const response = await window.axios.get('/api/categories');
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Không thể tải danh sách danh mục!' } 
            };
        }
    }
};
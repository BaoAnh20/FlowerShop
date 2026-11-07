// resources/js/api.js

export const apiService = {
    // 1. AUTHENTICATION
    login: async (credentials) => {
        try {
            const response = await window.axios.post('/api/login', credentials);
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Lỗi kết nối máy chủ!' } 
            };
        }
    },

    register: async (userData) => {
        try {
            const response = await window.axios.post('/api/register', userData);
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Lỗi kết nối máy chủ!' } 
            };
        }
    },

    logout: async () => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.post('/api/logout', {}, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Lỗi đăng xuất!' } 
            };
        }
    },

    // 2. USER / PUBLIC API(guest)

    
    // Lấy danh sách sản phẩm (Hỗ trợ tìm kiếm, lọc giá, danh mục, sắp xếp)
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

    // Lấy chi tiết sản phẩm theo ID
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

    // Lấy danh sách danh mục (Public)
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
    },

    // 3. ADMIN API 

    // Quản lý Sản phẩm 
    createProduct: async (productData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.post('/api/products', productData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data 
            };
        }
    },

    updateProduct: async (id, productData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.put(`/api/products/${id}`, productData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data 
            };
        }
    },

    deleteProduct: async (id) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.delete(`/api/products/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data 
            };
        }
    },

    //  Danh mục (Admin CRUD) 
    createCategory: async (categoryData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.post('/api/categories', categoryData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data 
            };
        }
    },

    updateCategory: async (id, categoryData) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.put(`/api/categories/${id}`, categoryData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data 
            };
        }
    },

    deleteCategory: async (id) => {
        try {
            const token = localStorage.getItem('auth_token');
            const response = await window.axios.delete(`/api/categories/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data 
            };
        }
    }

    // 4. CART & ORDER API 
    // chưa làm..
};
export const authApi = {
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
    }
};
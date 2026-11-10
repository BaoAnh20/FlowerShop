/**
 * Cart API Service - Xử lý các thao tác giỏ hàng (Thêm, Sửa số lượng, Xóa)
 */
export const cartApi = {
    // Thêm sản phẩm vào giỏ hàng
    addToCart: async (productId, quantity = 1) => {
        try {
            const response = await window.axios.post(`/cart/add/${productId}`, { quantity });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Không thể thêm sản phẩm vào giỏ hàng!' } 
            };
        }
    },

    // Cập nhật số lượng sản phẩm trong giỏ
    updateCart: async (cartId, quantity) => {
        try {
            const response = await window.axios.patch(`/cart/update/${cartId}`, { quantity });
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Không thể cập nhật số lượng giỏ hàng!' } 
            };
        }
    },

    // Xóa sản phẩm khỏi giỏ hàng
    removeFromCart: async (cartId) => {
        try {
            const response = await window.axios.delete(`/cart/remove/${cartId}`);
            return { ok: true, data: response.data };
        } catch (error) {
            return { 
                ok: false, 
                status: error.response?.status || 500, 
                data: error.response?.data || { message: 'Không thể xóa sản phẩm khỏi giỏ hàng!' } 
            };
        }
    }
};
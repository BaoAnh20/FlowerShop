// resources/js/api.js

import { authApi } from './api/user/authApi';
import { productApi } from './api/user/productApi';
import { cartApi } from './api/user/cart.js'; 
import { productAdminApi } from './api/admin/productAdminApi';
import { categoryAdminApi } from './api/admin/categoryAdminApi';

export const apiService = {
    // Gom tất cả các module vào 1 object
    ...authApi,
    ...productApi,

    ...cartApi, 

    ...productAdminApi,
    ...categoryAdminApi,
};

window.apiService = apiService;
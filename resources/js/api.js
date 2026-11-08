// resources/js/api.js

import { authApi } from './api/user/authApi';
import { productApi } from './api/user/productApi';
import { productAdminApi } from './api/admin/productAdminApi';
import { categoryAdminApi } from './api/admin/categoryAdminApi';

export const apiService = {
    // Gom tất cả các module vào 1 object
    ...authApi,
    ...productApi,
    ...productAdminApi,
    ...categoryAdminApi,
};

window.apiService = apiService;
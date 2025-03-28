/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "./BaseService.js";

export default class AuthenticationService extends BaseService {
    constructor(baseUrl, serviceUrl = 'auth') {
        super(baseUrl, serviceUrl);
    }

    async register(data) {
        return await this.post(data, 'register');
    }

    async login(data) {
        return await this.post(data, 'login');
    }

    async refresh() {
        return await this.client().get('refresh');
    }

    async logout() {
        return await this.client.get('logout');
    }

    async sendCode(phone, password, password_confirmation) {
        return await this.post({ phone, password, password_confirmation }, 'send-code')
    }

    async checkCode(code, phone) {
        return await this.post({ code, phone }, 'check-code')
    }

    async sendRecoveryCode(phone) {
        return await this.post({ phone }, 'send-recovery-code')
    }

    async changePassword(phone, code, password, password_confirmation) {
        return await this.post({ phone, code, password, password_confirmation }, 'change-password')
    }

    /**
     * Return authenticated user
     *
     * @return {Promise<AxiosResponse<any>>}
     */
    async getUser() {
        return this.client().get(this.buildUrl(this.serviceUrl, 'get-authenticated'));
    }

}

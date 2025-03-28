/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "./BaseService.js";

export default class ServisPolzovatelei extends BaseService {
    constructor(baseUrl, serviceUrl = 'users') {
        super(baseUrl, serviceUrl);
    }

    async login(data) {
        return await this.create()
    }


    async changeActivationStatus(status, user_id) {
        const data = new FormData();
        data.set('status', status);
        data.set('user_id', user_id);

        return await this.client().post(this._normalizeUrl(this.serviceUrl, 'change-activation-status'), data);
    }

    async changeRole(role, user_id) {
        return await this.client().put(this.buildUrl(this.serviceUrl, user_id), { role });
    }


    async search(username) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'search'), {username});
    }


    async obnovitPasport({passport_image}) {

        if (!passport_image)
            throw new Error('Post data param {passport_image} should be passed');

        return await this.post({passport_image}, 'update-passport');
    }
}

/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "./BaseService.js";

export default class FailServis extends BaseService {
    constructor(baseUrl, url = 'files') {
        super(baseUrl, url);
    }

    async search(filename) {
        const formData = new FormData();
        formData.set('filename', filename);

        return await this.client().post(this.buildUrl(this.serviceUrl, 'search'), formData);
    }

    /**
     *
     * @param formData {FormData}
     * @param onProgress {Function}
     * @param url
     * @return {Promise<void>}
     */
    async uploadFile(formData, onProgress, url = '/files/upload') {

        return await this.client().post(url, formData, {
            //headers: this.config.headers,
            onUploadProgress: progressEvent => {
                onProgress((progressEvent.loaded / progressEvent.total) * 100);
            }
        });

    }

}

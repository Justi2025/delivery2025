/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "./BaseService.js";
import {BASE_URL} from "./nastroika.js";

export default class ServisKompanii extends BaseService {
    constructor(baseUrl, serviceUrl = 'companies') {
        super(baseUrl, serviceUrl);
    }

    /**
     *
     * @param country {'ru'|'ab'|null}
     * @return {Promise<AxiosResponse<any>>}
     */
    async spisokPoluchit() {
        return await this.client().get(this.serviceUrl);
    }

    async get(id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, id));
    }

    async updateMarketplace(id, data){
        return await this.client().put(this.buildUrl(this.serviceUrl, id), data);
    }
}

export const companiesService = () => new ServisKompanii(BASE_URL);


import BaseService from "./BaseService.js";
import {BASE_URL} from "./nastroika.js";

export default class ServisPvzs extends BaseService {
    constructor(baseUrl, serviceUrl = 'delivery-points') {
        super(baseUrl, serviceUrl);
    }

    /**
     *
     * @param country {'ru'|'ab'|null}
     * @return {Promise<AxiosResponse<any>>}
     */
    async list(country= 'ru') {
        return await this.client().get(this.serviceUrl + '?country=' + country);
    }

    async get(id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, id));
    }

    async updatePoint(id, data){
        return await this.client().put(this.buildUrl(this.serviceUrl, id), data);
    }

    async updatePointUsageFrequency(id, usage_frequency) {
        return await this.client().put(this.buildUrl(this.serviceUrl, id, 'usage-frequency'), {usage_frequency});
    }
}

export const deliveryPointsService = () => new ServisPvzs(BASE_URL);

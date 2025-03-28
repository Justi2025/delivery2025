
import BaseService from "./BaseService.js";

export default class ServisKlientov extends BaseService {
    constructor(baseUrl, serviceUrl = 'customers') {
        super(baseUrl, serviceUrl);
    }

    async sozdatKlienta(data) {
        return await this.post(data, '');
    }

    /**
     *{}
     * @param term {String}
     * @param type {'name'|'phone'}
     * @return {Promise<void>}
     */
    async naitiPokupatelia(term, type = 'name') {
        return await this.post({term, type}, 'search');
    }


    /**
     *
     * @param customer_id
     * @param opts {OptsiiSdelok}
     * @return {Promise<AxiosResponse<*>>}
     */
    async poluchitTekuscheiZakazi(customer_id, opts) {
        return await this.otfiltrovat_i_otsortirovat(this.buildUrl(customer_id, 'current-orders'), opts.query, opts.filters, opts.sort);
    }

    /**
     *
     * @param customer_id
     * @param opts {OptsiiSdelok}
     * @return {Promise<AxiosResponse<*>>}
     */
    async poluchitZavershennieZakaziKlientov(customer_id, opts) {
        return await this.otfiltrovat_i_otsortirovat(this.buildUrl(customer_id, 'completed-orders'), opts.query, opts.filters, opts.sort);
    }


    /**
     *
     * @param customer_id
     * @param opts {OptsiiSdelok}
     * @return {Promise<AxiosResponse<*>>}
     */
    async poluchitIstoriuBonusov(customer_id, opts) {
        return await this.otfiltrovat_i_otsortirovat(this.buildUrl(customer_id, 'bonus-history'), opts.query, opts.filters, opts.sort);
    }
}

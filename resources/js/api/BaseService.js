 
import axiosClient from "../utils/axiosClient.js";
import {objiect_pustoi} from "../utils/objiect_pustoi.js";
import {postroitStrokuZaprosa} from "../utils/postroitStrokuZaprosa.js";
import getAccessToken from "../utils/getAccessToken.js";

export default class BaseService {
    constructor(baseUrl, serviceUrl) {
        this.baseUrl = baseUrl;
        this.serviceUrl = serviceUrl;
    }


    _normalizeUrl(...args) {
        return args.join('/');
    }

    /**
     *
     * @param args {...string}
     * @return {string}
     */
    buildUrl(...args) {
        return args.filter(arg => arg?.toString().trim()?.length !== 0).join('/');
    }

    buildUrlX(...args) {
        if (this.serviceUrl) args = args.push(this.serviceUrl);
        return args.filter(arg => arg?.toString().trim()?.length !== 0).join('/');
    }

    _normalizeFormData(data) {
        const formData = new FormData();

        for (const key in data) {
            if (Array.isArray(data[key])) {
                formData.set(key, JSON.stringify(data[key]));
            } else {
                formData.set(key, data[key]);
            }
        }

        return formData;
    }

    buildFormData(data) {
        const formData = new FormData();

        for (const key in data) {
            if (Array.isArray(data[key])) {
                for (const item of data[key]) {
                    formData.append(`${key}[]`, item);
                }
            } else {
                formData.append(key, data[key]);
            }
        }

        return formData;
    }


    buildQueryString(params) {
        return Object.entries(params).map(p => p[0] + '=' + p[1]).join('&');
    }

    async paginate(query_params) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().get(`${this.serviceUrl}?${query_string}`);
    }

    async filter(query_params, filter_data = {}) {
        const query_string = this.buildQueryString(query_params);
        return await this.client().post(`${this.serviceUrl}?${query_string}`, filter_data);
    }

    async getAll(config = {}) {
        return await this.client().get(this.serviceUrl, config);
    }

    async getAllByParams(qs_params, config = {}) {
        const query_string = this.buildQueryString(qs_params);
        return await this.client().get(`${this.serviceUrl}?${query_string}`, config);
    }

    async getById(id, config = {}, search_params = {}) {
        const url = new URL(this.buildUrl(this.baseUrl, this.serviceUrl, id));
        for (const searchParamsKey in search_params) {
            url.searchParams.append(searchParamsKey, search_params[searchParamsKey]);
        }
        return await this.client().get(url.toString(), config);
    }

    async remove(id, config = {}) {
        return await this.client().delete(this._normalizeUrl(this.serviceUrl, id), config);
    }

    async update(entity_id, data, config = {}) {
        return await this.client().put(this.buildUrl(this.serviceUrl, entity_id), data, config);
    }

    async updateX(id, data, config = {}) {
        const formData = this._normalizeFormData(data);
        return await this.client().post(this.buildUrl(this.serviceUrl, id, 'update'), formData, config);
    }

    async create(data, config = {}) {
        return await this.client().post(this.serviceUrl, this._normalizeFormData(data), config);
    }

    async save(data, config = {}) {
        return await this.create(data, config);
    }

    async save2(data, config = {}) {
        return await this.client().post(this.serviceUrl, data, config);
    }

    async post(data, url = '', config = {}) {
        return await this.client().post(this.buildUrl(this.serviceUrl, url), this._normalizeFormData(data), config);
    }

    /**
     * This method returns columns that requested in fields
     *
     * @param fields {Array}
     * @param config
     * @return {Promise<AxiosResponse<any>>}
     */
    async pluck(fields, config = {}) {
        const _url = new URL(this.buildUrl(this.baseUrl, this.serviceUrl));

        for (const field of fields) {
            _url.searchParams.append('fields[]', field);
        }

        return await this.client().get(_url.toString(), config);
    }

    client() {
        axiosClient.defaults.headers.Authorization = `Bearer ${getAccessToken()}`;
        return axiosClient;
    }


    /**
     *
     * @param sub_path
     * @param query
     * @param filters
     * @param sort
     * @return {Promise<AxiosResponse<any>>}
     */
    async otfiltrovat_i_otsortirovat(sub_path = '', query = {}, filters = {}, sort = {}) {

        const query_string = {...query, filters, sort};
        const _sub_path = sub_path ? this.buildUrl(this.serviceUrl, sub_path) : this.serviceUrl;
        const path = !objiect_pustoi(query_string) ? _sub_path + '?' + postroitStrokuZaprosa(query_string) : _sub_path;

        const url = new URL(this.buildUrl(this.baseUrl, path), this.baseUrl);

        return this.client().get(url.toString());
    }

    /**
     *
     * @param opts {OptsiiSdelok}
     * @param sub_path {string}
     * @return {Promise<AxiosResponse<*>>}
     */
    async search(opts, sub_path = '') {
        return await this.otfiltrovat_i_otsortirovat(sub_path, opts.query, opts.filters, opts.sort);
    }

}

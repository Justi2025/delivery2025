/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "../BaseService.js";
import {BASE_URL} from "../nastroika.js";
import {postroitStrokuZaprosa} from "../../utils/postroitStrokuZaprosa.js";
import {objiect_pustoi} from "../../utils/objiect_pustoi.js";

export default class SdelkiServis extends BaseService {
    constructor(baseUrl, serviceUrl = 'dostavka') {
        super(baseUrl, serviceUrl);
    }

    /**
     *
     * @param query Route query part
     * @param filters
     * @param sort
     * @return {Promise<AxiosResponse<any>>}
     */
    async index(query = {}, filters = {}, sort = {}) {

        const query_string = {...query, filters, sort};
        const path = !objiect_pustoi(query_string) ? this.serviceUrl + '?' + postroitStrokuZaprosa(query_string) : this.serviceUrl;

        const url = new URL(this.buildUrl(this.baseUrl, path), this.baseUrl);

        return this.client().get(url.toString());
    }


    /**
     *
     * @param options {OptsiiSdelok}
     * @return {Promise<void>}
     */
    async getAcceptedInPoint(options) {
        const opts = options.toObject();
        opts.filters.addresses_from.push(1);
    }

    async getGroupedByDeliveryPoints(query = {}, filters = {}, sort = {})
    {
        const query_string = {...query, filters, sort};

        const path = !objiect_pustoi(query_string)
            ? this.buildUrl(this.serviceUrl, 'grouped-by-shipping-from') + '?' + postroitStrokuZaprosa(query_string)
            : this.buildUrl(this.serviceUrl, 'grouped-by-shipping-from');

        const url = new URL(this.buildUrl(this.baseUrl, path), this.baseUrl);

        return this.client().get(url.toString());
    }

    async sozdatSdelku(data) {
        return await this.post(data);
    }

    async sozdatDostavku(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'incoming-delivery'), this.buildFormData(data));
    }

    async naznachitNaKuriera(courier_id, order_ids) {
        return await this.client().put(this.buildUrl(this.serviceUrl, 'assign-to-courier'), {order_ids, courier_id});
    }

    async otmenitSdelku(data) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'cancel'), this.buildFormData(data));
    }

    async izmenitStatusSdelki(data) {
        const url = this.buildUrl(this.serviceUrl, 'status');
        return await this.client().post(url, this.buildFormData(data));
    }

    async obnovitShtrikhKod({order_id, barcode_image}) {
        return await this.post({order_id, barcode_image}, 'barcode-image');
    }

    /**
     *
     * @param opts {OptsiiSdelok}
     * @return {Promise<*>}
     */
    async poluchitVseSdelki(opts) {
        return await this.otfiltrovat_i_otsortirovat('', opts.query, opts.filters, opts.sort || {});
    }


    /**
     *
     * @param opts {OptsiiSdelok}
     * @return {Promise<*>}
     */
    async getPendingOrders(opts) {
        return await this.otfiltrovat_i_otsortirovat('pending', opts.query, opts.filters, opts.sort || {});
    }


    async poluchitPrinatieZakazi(opts, id = '') {
        return await this.otfiltrovat_i_otsortirovat(this.buildUrl('accepted', id), opts.query, opts.filters, opts.sort);
    }


    async zakaziNaKuriera(opts, id = '') {
        return await this.otfiltrovat_i_otsortirovat(this.buildUrl('assigned-to-courier', id), opts.query, opts.filters, opts.sort);
    }

    async poluchitPoluchennieKurieromZakazi(opts, id = '') {
        return await this.otfiltrovat_i_otsortirovat(this.buildUrl('received-by-courier', id), opts.query, opts.filters, opts.sort);
    }

    async poluchitOtmenennieSdelki(opts) {
        return await this.otfiltrovat_i_otsortirovat('cancelled', opts.query, opts.filters, opts.sort);
    }
    /**
     *
     * @param opts {OptsiiSdelok}
     * @return {Promise<AxiosResponse<*>>}
     */
    async vidaniKlientam(opts) {
        return await this.otfiltrovat_i_otsortirovat('issued', opts.query, opts.filters, opts.sort);
    }


    async ojidaiutKlientov(opts) {
        return await this.otfiltrovat_i_otsortirovat('waiting-for-customers', opts.query, opts.filters, opts.sort || {});
    }

    async pokazatNomerKlienta(order_id) {
        return await this.client().get(this.buildUrl(this.serviceUrl, order_id, 'get-contact'));
    }

    /**
     *
     * @param {Object} data
     * @return {Promise<AxiosResponse<any>>}
     */
    async obnovitSdelku(data) {
        return await this.client().put(this.buildUrl(this.serviceUrl, 'update-incoming-order'), data);
    }


    /**
     * Courier can refuse order so its status changed to Accepted
     *
     * @param order_id
     * @return {Promise<AxiosResponse<any>>}
     */
    async otklonitSdelku(order_id) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'refuse'), {order_id});
    }

    /**
     *
     * @param order_id {Number}
     * @param order_status {OrderStatus}
     * @return {Promise<AxiosResponse<any>>}
     */
    async izmenitStatusNa(order_id, order_status){
        return await this.client().post(this.buildUrl(this.serviceUrl, 'change-status-to'), {order_id, order_status});
    }

    /**
     *
     * @param order_id
     * @param payment_type
     * @return {Promise<AxiosResponse<any>>}
     */
    async vidat(order_id, payment_type) {
        return await this.client().post(this.buildUrl(this.serviceUrl, 'issue'), {order_id, payment_type});
    }
}

export const servicSdelok = () => new SdelkiServis(BASE_URL);
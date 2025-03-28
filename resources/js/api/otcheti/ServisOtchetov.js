/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "../BaseService.js";
import {BASE_URL} from "../nastroika.js";
import {postroitStrokuZaprosa} from "../../utils/postroitStrokuZaprosa.js";


export default class ServisOtchetov extends BaseService {
    constructor(baseUrl, serviceUrl = 'reports') {
        super(baseUrl, serviceUrl);
    }

    async platejiPoDniamIOfisam(query, filter) {
        const qs = postroitStrokuZaprosa({...query, ...filter});
        return this.client().get(this.buildUrl(this.serviceUrl, 'payments-by-day-and-pickup-points') + (qs ? `?${qs}` : ''));
    }

    async poDniam(query = {}, filter = {}) {
        const qs = postroitStrokuZaprosa({...query, ...filter});
        return await this.client().get(this.buildUrl(this.serviceUrl, 'payments-by-day') + (qs ? `?${qs}` : ''));
    }


    async dolgiKlientov(query = {}, filter = {}) {
        const qs = postroitStrokuZaprosa({...query, ...filter});
        return await this.client().get(this.buildUrl(this.serviceUrl, 'debts-by-customers') + (qs ? `?${qs}` : ''));
    }
}

export const reportsService = () => new ServisOtchetov(BASE_URL);
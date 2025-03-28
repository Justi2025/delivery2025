/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "../BaseService.js";
import {BASE_URL} from "../nastroika.js";


export default class NastroikiAdminaServis extends BaseService {
    constructor(baseUrl, serviceUrl = 'settings') {
        super(baseUrl, serviceUrl);
    }

    async vseNastroki() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'all'));
    }


    async ustanovitStandartniBonus(amount) {
        return await this.post({amount}, 'set-standard-bonus');
    }


    async ustanovitVipBonus(amount) {
        return await this.post({amount}, 'set-vip-bonus');
    }


    async menedgerViditOtcheti(reports_visible) {
        return await this.post({reports_visible}, 'set-reports-visibility');
    }

    /**
     *
     * @return {Promise<*>}
     * @param status
     */
    async toggleTelegramNotifications(status) {
        return await this.post({status}, 'set-telegram-notifications');
    }
}

export const servisNastroekAdmina = () => new NastroikiAdminaServis(BASE_URL);
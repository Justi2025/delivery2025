 
import BaseService from "../BaseService.js";
import {BASE_URL} from "../nastroika.js";


export default class NastroikiPolzovaliaServis extends BaseService {
    constructor(baseUrl, serviceUrl = 'user-settings') {
        super(baseUrl, serviceUrl);
    }

    async vseNastroiki() {
        return await this.client().get(this.buildUrl(this.serviceUrl, 'all'));
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

export const servisNastroekPolzovatelia = () => new NastroikiPolzovaliaServis(BASE_URL);
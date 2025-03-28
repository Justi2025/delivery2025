/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import BaseService from "../BaseService.js";
import {BASE_URL} from "../nastroika.js";


export default class ServisBonusov extends BaseService {
    constructor(baseUrl, serviceUrl = 'bonuses') {
        super(baseUrl, serviceUrl);
    }

    /**
     *
     * @param data {KolichestvoBonusovProsto}
     * @return {Promise<*>}
     */
    async addBonuses(data) {
        return await this.post(data, 'add');
    }

    /**
     *
     * @param data {KolichestvoBonusovProsto}
     * @return {Promise<*>}
     */
    async withdrawBonuses(data) {
        return await this.post(data, 'withdraw');
    }
}


export const bonusesService = () => new ServisBonusov(BASE_URL);
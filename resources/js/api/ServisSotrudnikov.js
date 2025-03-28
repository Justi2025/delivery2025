
import BaseService from "./BaseService.js";
import {BASE_URL} from "./nastroika.js";


export class ServisSotrudnikov extends BaseService
{
    constructor(baseUrl, serviceUrl='employees') {
        super(baseUrl, serviceUrl);
    }

    async getCouriers() {
        return this.client().get(this.buildUrl(this.serviceUrl, 'couriers'));
    }

    async updateRole(role, user_id) {
        return await this.client().put(this.buildUrl(this.serviceUrl, user_id), { role });
    }

    async changeOffice(office_id, user_id) {
        return await this.client().put(this.buildUrl(this.serviceUrl, user_id), { office_id });
    }
}

export const employeeService = () => new ServisSotrudnikov(BASE_URL);
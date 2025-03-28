 
import BaseService from "./BaseService.js";


export default class ServisGorodov extends BaseService
{
    constructor(baseUrl, serviceUrl = 'cities') {
        super(baseUrl, serviceUrl);
    }


    async poluchitGoroda() {
        const res = await this.getAll();
        return await res.data;
    }
}
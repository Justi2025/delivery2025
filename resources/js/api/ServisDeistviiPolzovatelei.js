 
import BaseService from "./BaseService.js";
import {BASE_URL} from "./nastroika.js";

export default class ServisDeistviiPolzovatelei extends BaseService {
    constructor(baseUrl, serviceUrl = 'logs') {
        super(baseUrl, serviceUrl);
    }
}

export const logsService = () => new ServisDeistviiPolzovatelei(BASE_URL);

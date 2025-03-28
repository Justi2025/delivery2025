 
import BaseService from "../BaseService.js";
import {BASE_URL} from "../nastroika.js";

export class FilesService extends BaseService {
    constructor(baseUrl, url = 'files') {
        super(baseUrl, url);
    }

    /**
     *
     * @param filename {string}
     * @return {Promise<AxiosResponse<any>>}
     */
    async search(filename) {
        const formData = new FormData();
        formData.set('filename', filename);

        return await this.client().post(this.buildUrl(this.serviceUrl, 'search'), formData);
    }

    /**
     *
     * @param formData
     * @param onProgress
     * @return {Promise<AxiosResponse<any>>}
     */
    async uploadFile(formData, onProgress) {

        return await this.client().post('/files/upload', formData, {
            onUploadProgress: progressEvent => {
                onProgress((progressEvent.loaded / progressEvent.total) * 100);
            }
        });
    }

}

export const filesService = () => new FilesService(BASE_URL);

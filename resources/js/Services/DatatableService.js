import axios from "axios";

export default class DatatableService {
    getData(endpoint, queryParams) {
        return axios.get(endpoint, {params: queryParams})
            .then(res => res.data);
    }
}

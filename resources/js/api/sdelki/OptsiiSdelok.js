/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
export default class OptsiiSdelok {

    // query string params
    query = {};

    // sort
    sort = {

    };

    filters = {
        customer_id: null,
        couriers: [],
        order_statuses: [],
        addresses_from: [],
        addresses_to: [],
        start_datetime: '',
        end_datetime: '',
        customer_cities: [],
        customer_name: '',
        order_id: 0,
    }


    toObject() {

        const filters = {
            customer_id: this.filters.customer_id,
            couriers: this.filters.couriers,
            order_statuses: this.filters.order_statuses,
            addresses_from: this.filters.addresses_from,
            addresses_to: this.filters.addresses_to,
            start_datetime: this.filters.start_datetime,
            end_datetime: this.filters.end_datetime,
            customer_cities: this.filters.customer_cities,
            customer_name: this.filters.customer_name,
            order_id: this.filters.order_id,
        };

        return {...this.query, filters, sort: this.sort};
    }
}
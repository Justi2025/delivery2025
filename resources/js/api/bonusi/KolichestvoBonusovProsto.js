/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
export default class KolichestvoBonusovProsto {

    constructor(customer_id, amount, comment = '') {
        this.customer_id = customer_id;
        this.bonus_amount = amount;
        this.comment = comment;
    }
}
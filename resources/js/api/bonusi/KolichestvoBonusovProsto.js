 
export default class KolichestvoBonusovProsto {

    constructor(customer_id, amount, comment = '') {
        this.customer_id = customer_id;
        this.bonus_amount = amount;
        this.comment = comment;
    }
}
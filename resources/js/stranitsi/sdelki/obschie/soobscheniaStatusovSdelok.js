
import {OrderStatus} from "../../../utils/zakazi/statusi_zakaza.js";

export const soobscheniaStatusovSdelok = {
    [OrderStatus.Waiting]: 'Ваш заказ ожидает рассмотрения',
    [OrderStatus.Accepted]: 'Ваш заказ принят',
    [OrderStatus.AssignedToCourier]: 'Мы передали Ваш заказ курьеру',
    [OrderStatus.ReceivedByCourier]: 'Курьер забрал заказ',
    [OrderStatus.WaitingForCustomers]: 'Заказ готов для выдачи',
    [OrderStatus.IssuedToCustomer]: 'Ваш заказ выдан. Ждем Вас снова :)',
    [OrderStatus.IssuedPartiallyPaid]: 'Ваш заказ выдан, но оплачена только часть стоимости доставки. ' +
    'Если оплачивать заказы сразу и полностью, будут начисляться балы, которыми потом можно оплатить другие заказы :)',
    [OrderStatus.Postponed]: 'Ваш заказ временно отложен, но мы обязательно к нему вернемся'
}
 
const optsciiSortirovkiSdelok = [

    { sort_order: 'customer=asc', value: 'ФИО Клиента (от А до Я)' },
    { sort_order: 'customer=desc', value: 'ФИО Клиента (от Я до А)' },

    { sort_order: 'dp=asc', value: 'Пункты откуда (от А до Я)' },
    { sort_order: 'dp=desc', value: 'Пункты откуда (от Я до А)' },

    { sort_order: 'created_at=asc', value: 'Дата создания (возрастает)' },
    { sort_order: 'created_at=desc', value: 'Дата создания (убывает)' },

    { sort_order: 'issued_at=asc', value: 'Дата выдачи (возрастает)' },
    { sort_order: 'issued_at=desc', value: 'Дата выдачи (убывает)' },

    { sort_order: 'order_status=asc', value: 'Статус заказа (от А до Я)' },
    { sort_order: 'order_status=desc', value: 'Статус заказа (от Я до А)' },
];

export default optsciiSortirovkiSdelok;

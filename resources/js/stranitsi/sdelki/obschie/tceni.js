 
export const tceni = [
    {
        id: 1,
        name: 'Свой расчет',
        price: 0,
        min: 0,
        max: 10,
        measure_unit: 1
    },
    {
        id: 2,
        name: 'Основной',
        price: 100,
        min: 10,
        max: -1,
        measure_unit: 1
    },
    {
        id: 3,
        name: 'Коммерческий груз',
        price: 200,
        min: -1,
        max: -1,
        measure_unit: 1
    },
    {
        id: 4,
        name: 'Тяжелая мебель',
        price: 80,
        min: -1,
        max: -1,
        measure_unit: 1
    },
    {
        id: 5,
        name: 'Хрупкий/габаритный/нестандартный груз',
        price: 200,
        min: -1,
        max: -1,
        measure_unit: 1
    },
    {
        id: 6,
        name: 'Обувь от 3 шт',
        price: 300,
        min: -1,
        max: -1,
        measure_unit: 1
    },
];

const Tariffs = Object.freeze({
    BasicUpTo10kg: 1,
    BasicFrom10kg: 2,
    CommercialCargo: 3,
    HeavyFurniture: 4,
    FragileOversizedNonStandardCargo: 5,
    ShoesFrom3Pieces: 6

});

export const DefaultTariff = tceni[1];

/**
 *
 * @param weight {Number}
 * @param tariff {{id, name, price, min, max, measure_unit}}
 */
export function calculatePrice(weight, tariff) {

    const TenKilograms = 10;
    const MinPrice = 150;
    let value;

    if (tariff.id === Tariffs.BasicFrom10kg) {
        if (weight > TenKilograms) {
            const excess = weight - TenKilograms;
            value = (TenKilograms * MinPrice) + (excess * tariff.price);
        } else {
            value = weight * MinPrice;
        }
    } else {
        value = weight * tariff.price;
    }

    return Math.floor(value);
}
<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderWarehouseCell
 *
 * @method static Builder|OrderWarehouseCell newModelQuery()
 * @method static Builder|OrderWarehouseCell newQuery()
 * @method static Builder|OrderWarehouseCell query()
 * @property int $id
 * @property int $order_id
 * @property string|null $cell Ячейка/ячейки, в которой лежат товары заказа, готовые к выдаче
 * @property int $employee_id Идентификатор сотрудника – кладовщика
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|OrderWarehouseCell whereCell($value)
 * @method static Builder|OrderWarehouseCell whereCreatedAt($value)
 * @method static Builder|OrderWarehouseCell whereEmployeeId($value)
 * @method static Builder|OrderWarehouseCell whereId($value)
 * @method static Builder|OrderWarehouseCell whereOrderId($value)
 * @method static Builder|OrderWarehouseCell whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderWarehouseCell extends Model
{
    use HasFactory;
}

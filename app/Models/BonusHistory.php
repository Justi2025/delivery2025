<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BonusHistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory query()
 * @property int $id
 * @property int $client_id
 * @property int $bonus_amount
 * @property int $employee_id Идентификатор сотрудника сделавшего начисления
 * @property int $order_id Идентификатор заказа, за который было начисление/списание
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereBonusAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BonusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'bonus_amount',
        'employee_id',
        'order_id',
        'comment'
    ];
}

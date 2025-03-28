<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\OrderHistory
 *
 * @method static Builder|IstoriaZakaza newModelQuery()
 * @method static Builder|IstoriaZakaza newQuery()
 * @method static Builder|IstoriaZakaza query()
 * @property int $id
 * @property int $order_id
 * @property string|null $status
 *                 1 – ожидает рассмотрения, 2 – принята, 3 – назначена курьеру (в работе), 4- получена курьером,
 *                 5 – готова к выдаче, 6 – выдана, 7 – выдана, оплачена частично, 8 – отменена
 * @property string|null $comment
 * @property int $creator_id Идентификатор пользователя, внесшего изменения в заказ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|IstoriaZakaza whereComment($value)
 * @method static Builder|IstoriaZakaza whereCreatedAt($value)
 * @method static Builder|IstoriaZakaza whereCreatorId($value)
 * @method static Builder|IstoriaZakaza whereId($value)
 * @method static Builder|IstoriaZakaza whereOrderId($value)
 * @method static Builder|IstoriaZakaza whereStatus($value)
 * @method static Builder|IstoriaZakaza whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @mixin \Eloquent
 */
class IstoriaZakaza extends Model
{
    use HasFactory;

    protected $table = 'order_histories';

    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'creator_id',
    ];


    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id','id')->whereIn('fileable_type', [self::class, 'App\Models\OrderHistory']);
    }


    /**
     * Creator of this order history item
     *
     * @return void
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id')->select(['id', 'role']);
    }
}

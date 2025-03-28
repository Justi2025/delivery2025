<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BotState
 *
 * @method static Builder|BotState newModelQuery()
 * @method static Builder|BotState newQuery()
 * @method static Builder|BotState query()
 * @property int $id
 * @property int $chat_id
 * @property string $phone
 * @property string|null $state
 * @property string|null $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BotState whereChatId($value)
 * @method static Builder|BotState whereCreatedAt($value)
 * @method static Builder|BotState whereId($value)
 * @method static Builder|BotState wherePayload($value)
 * @method static Builder|BotState wherePhone($value)
 * @method static Builder|BotState whereState($value)
 * @method static Builder|BotState whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BotState extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'phone',
        'state',
        'payload'
    ];
}

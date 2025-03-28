<?php
 

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auth\RefreshToken
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken query()
 * @property int $id
 * @property string $token
 * @property string $expired_at
 * @property int $user_id
 * @property string|null $user_agent
 * @property string $client_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereClientIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereUserId($value)
 * @property string $access_token_md5
 * @property string $user_ip
 * @property string $value
 * @property string $is_revoked 0 - active, 1 - revoked
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereAccessTokenMd5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereIsRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereUserIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefreshToken whereValue($value)
 * @mixin \Eloquent
 */
class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'expired_at',
        'user_id',
        'user_agent',
        'client_ip'
    ];
}

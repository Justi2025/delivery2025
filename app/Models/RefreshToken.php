<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\RefreshToken
 *
 * @property int $id
 * @property string $token
 * @property string $expired_at
 * @property int $user_id
 * @property string|null $user_agent
 * @property string $client_ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RefreshToken newModelQuery()
 * @method static Builder|RefreshToken newQuery()
 * @method static Builder|RefreshToken query()
 * @method static Builder|RefreshToken whereClientIp($value)
 * @method static Builder|RefreshToken whereCreatedAt($value)
 * @method static Builder|RefreshToken whereExpiredAt($value)
 * @method static Builder|RefreshToken whereId($value)
 * @method static Builder|RefreshToken whereToken($value)
 * @method static Builder|RefreshToken whereUpdatedAt($value)
 * @method static Builder|RefreshToken whereUserAgent($value)
 * @method static Builder|RefreshToken whereUserId($value)
 * @property string $access_token_md5
 * @property string $user_ip
 * @property string $value
 * @property string $is_revoked 0 - active, 1 - revoked
 * @method static Builder|RefreshToken whereAccessTokenMd5($value)
 * @method static Builder|RefreshToken whereIsRevoked($value)
 * @method static Builder|RefreshToken whereUserIp($value)
 * @method static Builder|RefreshToken whereValue($value)
 * @mixin \Eloquent
 */
class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token_md5',
        'user_id',
        'user_ip',
        'user_agent',
        'value',
        'is_revoked',
    ];

    protected $hidden = [
        'value'
    ];

    public function expired(int $ttl): bool
    {
        return now()->gt($this->updated_at->addSeconds($ttl));
    }

    public function revoke(): bool
    {
        return $this->update(['is_revoked' => 1]);
    }
}

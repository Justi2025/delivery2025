<?php
 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhoneVerification
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification query()
 * @property int $id
 * @property string $phone
 * @property string $ip
 * @property string|null $user_agent
 * @property int $code
 * @property int $attempts_count
 * @property string|null $service Which service is used to verify phone number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $token
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereAttemptsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneVerification whereUserAgent($value)
 * @mixin \Eloquent
 */
class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'ip',
        'code',
        'token',
        'attempts_count',
        'service',
        'user_agent',
    ];
}

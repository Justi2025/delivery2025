<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccountVerification
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification query()
 * @property int $id
 * @property string $phone
 * @property int $code Код верификации
 * @property int|null $attempts
 * @property string $ip
 * @property string $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountVerification whereVerifiedAt($value)
 * @mixin \Eloquent
 */
class AccountVerification extends Model
{
    use HasFactory;
}

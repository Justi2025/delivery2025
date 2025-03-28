<?php
 

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\UserSettings
 *
 * @method static Builder|UserSettings newModelQuery()
 * @method static Builder|UserSettings newQuery()
 * @method static Builder|UserSettings query()
 * @property int $id
 * @property int $user_id
 * @property string $setting_id
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|UserSettings whereCreatedAt($value)
 * @method static Builder|UserSettings whereId($value)
 * @method static Builder|UserSettings whereSettingId($value)
 * @method static Builder|UserSettings whereUpdatedAt($value)
 * @method static Builder|UserSettings whereUserId($value)
 * @method static Builder|UserSettings whereValue($value)
 * @mixin \Eloquent
 */
class UserSettings extends Model
{
    use HasFactory;

    protected $table = 'users_settings';

    protected $fillable = [
        'user_id',
        'setting_id',
        'value',
    ];
}

<?php


namespace App\Models\Settings;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminSettings
 *
 * @method static Builder|AdminSettings newModelQuery()
 * @method static Builder|AdminSettings newQuery()
 * @method static Builder|AdminSettings query()
 * @property int $id
 * @property string $setting_id
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|AdminSettings whereCreatedAt($value)
 * @method static Builder|AdminSettings whereId($value)
 * @method static Builder|AdminSettings whereSettingId($value)
 * @method static Builder|AdminSettings whereUpdatedAt($value)
 * @method static Builder|AdminSettings whereValue($value)
 * @mixin Eloquent
 */
class AdminSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_id',
        'value',
    ];
}

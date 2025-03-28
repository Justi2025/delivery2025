<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Marketplace
 *
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereId($value)
 * @method static Builder|Company whereName($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @property string $country
 * @property string|null $company_color
 * @property string|null $label_color
 * @property int|null $usage_frequency usage frequency statuses: 1- often, 2 - very often, 3 - rarely
 * @method static Builder|Company whereCompanyColor($value)
 * @method static Builder|Company whereCountry($value)
 * @method static Builder|Company whereLabelColor($value)
 * @method static Builder|Company whereUsageFrequency($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'country',
        'company_color',
        'label_color',
        'usage_frequency',
        'pickup_only_paid',
    ];

}

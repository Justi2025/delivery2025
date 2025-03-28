<?php
 

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Khranilischa\RoliPolzovatelei;
use App\Khranilischa\UserStatus;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\Models\User
 *
 * @property mixed $password
 * @property-read Collection<int, File> $files
 * @property-read int|null $files_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @property int $id
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string $full_name
 * @property string $phone
 * @property string|null $passport_image
 * @property int|null $city_id
 * @property string|null $role
 * @property string|null $status
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCityId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFullName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User wherePassportImage($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property int|null $creator_id Сотрудник, создавший запись пользователя, пусто, если пользователь создал аккаунт сам
 * @property string $firstname
 * @property string $lastname
 * @property int $year_of_birth
 * @property string|null $middlename
 * @property string|null $street
 * @property string|null $house
 * @property string|null $flat
 * @property int|null $is_vip
 * @property string|null $phone_verified_at
 * @method static Builder|User whereCreatorId($value)
 * @method static Builder|User whereFirstname($value)
 * @method static Builder|User whereFlat($value)
 * @method static Builder|User whereHouse($value)
 * @method static Builder|User whereIsVip($value)
 * @method static Builder|User whereLastname($value)
 * @method static Builder|User whereMiddlename($value)
 * @method static Builder|User wherePhoneVerifiedAt($value)
 * @method static Builder|User whereStreet($value)
 * @method static Builder|User whereYearOfBirth($value)
 * @property int|null $office_id Склад/офис, где работает сотрудник
 * @property int|null $bonus_balance customer current  bonus balance, needed to avoid joins with bonus_histories
 * @property int|null $telegram_chat_id
 * @method static Builder|User whereBonusBalance($value)
 * @method static Builder|User whereOfficeId($value)
 * @method static Builder|User whereTelegramChatId($value)
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Pvz|null $office
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'password',
        'full_name',
        'office_id',
        'passport_image',
        'city_id',
        'street',
        'house',
        'flat',
        'year_of_birth',
        'avatar',
        'phone_verified_at',

        'role',
        'status',

        'creator_id',
        'is_vip',
        'bonus_balance',

        'telegram_chat_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function passwordEquals(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function office()
    {
        return $this->belongsTo(Pvz::class, 'office_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === RoliPolzovatelei::Ahmad;
    }

    public function isManager(): bool
    {
        return $this->role === RoliPolzovatelei::ZamAhmada->value;
    }

    public function isStorekeeper()
    {
        return $this->role === RoliPolzovatelei::Skladovschik->value;
    }

    public function isActivated()
    {
        return $this->status === UserStatus::Activated;
    }
}

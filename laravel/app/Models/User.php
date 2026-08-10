<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Asset;
use App\Models\Request;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,HasRoles,HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'group',
        'password',
    ];
    public const GROUPS = [
        '000' => 'Tiada Kumpulan',//Default
        '041' => 'Bahagian Khidmat Teknikal',
        '052' => "Bahagian Galakan Industri, Penyelidikan dan Pembangunan Perniagaan",
        '051' => "Bahagian Audit Dalaman",
        '061' => 'Bahagian Pengurusan Harta',//06
        '071' => 'Bahagian Digital',
        '072' => 'Bahagian Perancangan Korporat',//07
        '082' => 'Bahagian Perundangan dan Integriti',//08
        '081' => 'Bahagian Hartanah dan Sumber Asli',//08
        '092' => 'Bahagian Pembangunan Usahawan dan Pelaburan',//09
        '091' => 'Bahagian Kewangan',//09
        '101' => 'Bahagian Pengurusan Sumber Manusia'//10
    ];
    public function requests(){
        return $this->hasMany(Request::class, 'T30T10_user_id','id');
    }
    public static function getGroupName(?string $code): string
    {
        // If code is null or doesn't exist in the array, default to '000' (Tiada Kumpulan)
        return self::GROUPS[$code] ?? self::GROUPS['000'];
    }
    public function groupName(): string
    {
        return $this->getGroupName($this->group);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Rest omitted for brevity

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'password',
        'user_name',
        'name',
        'balance',
        'country',
        'currency',
        'city',
        'dob',
        'street',
        'profile',
        'reference_id',
        'is_verified',
        'is_blocked',
        'role',
        'playerId'
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

    public function activeStatus(){
        return $this->hasOne(ActiveStatus::class);
    }

    public function balance(){
        return $this->hasMany(Balance::class);
    }

    public function refer(){
        return $this->hasOne(Refer::class);
    }

    public function turnOver(){
        return $this->hasMany(UserTurnOver::class);
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }

    public function reward(){
        return $this->hasMany(Reward::class);
    }

    public function userDepositBonus(){
        return $this->hasMany(UserDepositBonus::class);
    }

    public function referHistory(){
        return $this->hasMany(ReferHistory::class);
    }

    public function paymentTransaction(){
        return $this->hasMany(PaymentTransaction::class);
    }

}

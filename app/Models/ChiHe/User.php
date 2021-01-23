<?php
/**
 * 2019年11月29日 14:23
 */

namespace App\Models\ChiHe;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table    = 'chihe_user';
    protected $fillable = [
        'id',
        'open_id',
        'created_at',
        'is_deleted',
        'updated_at',
        'nickname',
        'avatar',
        'city',
        'gender',
        'ref_uid', //推荐人ID
        'get_coupon_num',
        'subscribe_num'
    ];

    static function userStatus($type = null)
    {
        $status = [
            1 => '冻结',
            2 => '正常',
        ];
        return $status[$type] ?? $status;
    }
    //用户类型
    static function userType($type = null)
    {
        $userTypes = [
            0 => '使用会员',
            1 => '高级会员',
            2 => '终生会员',
        ];
        return $userTypes[$type] ?? $userTypes;
    }
    const UPDATED_AT = null;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}


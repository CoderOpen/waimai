<?php
/**
 * 2019年11月29日 14:23
 */

namespace App\Models\ChiHe;

use Illuminate\Database\Eloquent\Model;
class GetCouponLog extends Model
{
    protected $table    = 'chihe_coupon_log';
    protected $fillable = [
        'id',
        'user_id',
        'created_at',
        'project_id',
    ];
    const UPDATED_AT = null;
}


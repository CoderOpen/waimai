<?php
/**
 * 2019年11月29日 14:23
 */

namespace App\Models\ChiHe;
use Illuminate\Database\Eloquent\Model;
class Subscribe extends Model
{

    protected $table    = 'chihe_subscribe';
    protected $fillable = [
        'id',
        'user_id',
        'created_at',
        'template_id',
        'is_send',
    ];
    const UPDATED_AT = null;
}


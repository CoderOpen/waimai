<?php
/**
 * 2019年11月29日 14:23
 */

namespace App\Models\ChiHe;

use Illuminate\Database\Eloquent\Model;


class Link extends Model
{

    protected $table    = 'link';
    //protected $connection = 'common';
    protected $fillable = [
        'id',
        'local_app_id',
        'app_id',
        'created_at',
        'logo',
        'status',
        'app_name',
        'description',
        'path',
        'sort',
        'updated_at'
    ];

    //const UPDATED_AT = null;

    //const UPDATED_AT = null;
    static function showStatus($type = null)
    {
        $map = [
            1 => '显示',
            2 => '隐藏',
        ];

        if ($type) {
            return $map[$type] ?? '';
        }
        return $map;
    }

    /**
     *  获取设置
     */
    function getLogoAttribute($value)
    {
        if (strpos($value,'http') === false) {
            return url('/') . '/storage/'. $value;
        } else {
            return $value;
        }

    }


    /**
     *  设置
     */
    function set()
    {

    }
}


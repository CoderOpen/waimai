<?php
/**
 * 2020年08月11日 07:34
 */

namespace App\Models\ChiHe;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
{

    protected $table      = 'chihe_project';
    protected $fillable   = [
        'id',
        'title',
        'app_id',
        'created_at',
        'image',
        'status',
        'description',
        'path',
        'sort',
        'updated_at'
    ];

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
    function getImageAttribute($value)
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


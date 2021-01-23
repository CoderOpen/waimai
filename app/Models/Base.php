<?php
/**
 * Created by PhpStorm.
 * User: 13616
 * Date: 2019/1/19
 * Time: 11:44
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Base extends Model
{
    public function batchInsert(Array $data)
    {
        $rs = DB::table($this->getTable())->insert($data);
        return $rs;
    }
}

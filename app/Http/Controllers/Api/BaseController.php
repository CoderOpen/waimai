<?php
/**
 * 2019年07月17日 22:50
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    const PAGE_SIZE = 10;
    const PAGE_NO  = 1;

    /**
     * 操作成功
     *
     * @param array  $data
     * @param string $msg
     */
    protected function success($data = [], $msg = '成功')
    {
        $result = [
            'code' => 1,
            'data' => $data,
            'msg'  => $msg,
        ];
        return response()->json($result)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * 操作失败
     *
     * @param array  $data
     * @param string $msg
     */
    protected function fail($msg = '成功', $code = 0, $data = [])
    {
        $result = [
            'code' => $code,
            'data' => $data,
            'msg'  => $msg,
        ];
        return response()->json($result)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}

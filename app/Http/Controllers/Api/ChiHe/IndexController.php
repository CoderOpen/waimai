<?php
/**
 * 2020年04月19日 12:25
 */
namespace App\Http\Controllers\Api\ChiHe;

use App\Http\Controllers\Api\BaseController;
use App\Models\ChiHe\GetCouponLog;
use App\Models\ChiHe\Subscribe;
use App\Models\Common\Feedback;
use App\Models\Youtube\Code;
use App\Models\Youtube\Log;
use App\Models\Youtube\OperateLog;
use App\Models\Youtube\Share;
use App\Models\Youtube\ShareAccept;
use App\Models\ChiHe\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Overtrue\Socialite\SocialiteManager;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Services\Net\Curl;
class IndexController extends BaseController
{

    /**
     *  订阅记录增加
     */
    function addSubscribe(Request $request)
    {
        $userId = $request->user_id;
        $templateId = $request->post('template_id', '');
        if (!($userId && $templateId)) {
            return $this->fail('缺少参数');
        }

        $subscribe = new Subscribe();
        $subscribe->template_id = $templateId;
        $subscribe->user_id = $userId;
        $subscribe->is_send = 2;
        $res = $subscribe->save();
        if (!$res) {
            return $this->fail('添加记录失败');
        }

        $user = User::where('id', $userId)->first();
        $user->subscribe_num = $user->subscribe_num + 1;
        $res = $user->save();
        if (!$res) {
            return $this->fail();
        }
        return $this->success([],'提醒成功，明天继续哦');
    }

    /**
     * 增加领红包记录
     */
    function addCouponLog(Request $request)
    {
        $userId = $request->user_id;
        $projectId = $request->post('project_id', '');
        if (!($userId && $projectId)) {
            return $this->fail('缺少参数');
        }
        $couponLog = new GetCouponLog();
        $couponLog->project_id = $projectId;
        $couponLog->user_id    = $userId;
        $res = $couponLog->save();
        if (!$res) {
            return $this->fail('保存失败');
        }
        $user = User::where('id', $userId)->first();
        $user->get_coupon_num = $user->get_coupon_num + 1;
        if($user->save()) {
            return $this->success();
        }
        return $this->fail();
    }
}

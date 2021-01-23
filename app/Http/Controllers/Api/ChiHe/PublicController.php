<?php
/**
 * 2020年04月19日 12:25
 */
namespace App\Http\Controllers\Api\ChiHe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Services\PeiYin\AliAi;
use App\Models\ChiHe\Project;
use App\Models\ChiHe\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Overtrue\Socialite\SocialiteManager;
use Tymon\JWTAuth\Facades\JWTAuth;

class PublicController extends BaseController
{
    function project(Request $request)
    {
        //return $this->success(['age' => 1]);
        $projects = Project::where([
            'status' => 1
        ]);
        $platform = $request->get('platform');
        if ($platform == 'h5' || ($platform == 'qq')) {
            $projects = $projects->orderBy('h5_sort', 'DESC');
        } else {
            $projects = $projects->orderBy('sort', 'DESC');
        }
        $projects = $projects->orderBy('created_at', 'DESC')->get()->toArray();
        if ($platform == 'h5' || ($platform == 'qq')) {
            foreach ($projects as $k => $v) {
                if (empty($v['url'])) {
                    unset($projects[$k]);
                }
            }

        }
        return $this->success($projects);
    }


    /**
     *  发送订阅消息
     */
    function sendMsg()
    {
        $userId = 'ocBpY4zNqTzD5JfTEsVLSHQVJSyY';
        $data = [
            'template_id' => 'kXBmBC98SYth6HltqOVc-3_Y2EncSybE2-0TMvyCXDk', // 所需下发的订阅模板id
            'touser' => $userId,     // 接收者（用户）的 openid
            'page' => 'index/index',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => [         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
                'thing2' => [
                    'value' => '快来领优惠券啦',
                ],
                'thing4' => [
                    'value' => '炒鸡棒棒哒',
                ],
            ],
        ];
        $config = 'chi_he';
        $app = \EasyWeChat::miniProgram($config); // 小程序
        $res = $app->subscribe_message->send($data);
        return $this->success($res);

    }


    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * 第三方登录
     */
    function login(Request $request)
    {
        $code = $request->post('code', '');
        $refId = $request->post('ref_id', '');
        $uniqueCode = $request->post('unique_code', '');
        $nickname= $request->post('nickname', '');
        $avatar= $request->post('head_url', '');
        $gender= $request->post('gender', 0);


        //$config = config('wechat.mini_program.short_url');
        $config = 'chi_he';
        $miniProgram = \EasyWeChat::miniProgram($config); // 小程序

        $info = $miniProgram->auth->session($code);
        $openId = $info['openid'];
        $user = User::where(['open_id' => $openId])->first();

        if ($user) {
            if ($user->is_deleted == 1) {
                return $this->fail('账户冻结，请联系管理员');
            }
            $data['user'] = $user->toArray();
            $token       = JWTAuth::fromUser($user);
            $data['token'] = 'bearer ' . $token;
            return  $this->success($data);
        }
        $user = new User();
        $user->is_deleted = 2;
        $user->open_id = $openId;
        $user->nickname = $nickname;
        $user->gender = $gender;
        $user->avatar = $avatar;
        $user->get_coupon_num = 0;
        if ($refId) {
            //主表保存推荐人
            $refUser = User::where(['id' => $refId])->first();
            if ($refUser) {
                $user->ref_id = $refId;
                $refUser->share_num += 1; //分享成功次数
                $addTimes = config('config.youtube.add_times', 10);
                $refUser->left_num = $refUser->left_num + $addTimes;
                $refUser->save();
            }
        }
        $res = $user->save();
        if ($res) {
            if ($uniqueCode) {
                //保存接收到分享的记录
                $share = Share::where([
                    'unique_code' => $uniqueCode,
                    'user_id' => $refId,
                ])->first();
                if ($share) {
                    $shareAccpet = new ShareAccept();
                    $shareAccpet->user_id = $refId;
                    $shareAccpet->accept_uid = $user->id;
                    $shareAccpet->share_id = $share->id;
                    $shareAccpet->save();
                }
            }
            $token       = JWTAuth::fromUser($user);
            $data['user'] = $user->toArray();
            $data['token'] = 'bearer ' . $token;
            return  $this->success($data);
        } else {
            return $this->fail('新用户保存失败');
        }
    }
}

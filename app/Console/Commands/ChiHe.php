<?php
namespace App\Console\Commands;
use App\Models\ChiHe\Subscribe;
use App\Models\ChiHe\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;



class ChiHe extends Command
{
    /**
     * 控制台命令 signature 的名称。
     * $signature = 'swoole:send {user}'
     * @var string
     */
    protected $signature = 'chihe:send-msg';
    /**
     * 控制台命令说明。
     *
     * @var string
     */
    protected $description = 'send email to sale something';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行控制台命令。
     *
     * @return mixed
     */
    public function handle()
    {
        if (date('H:i:') === '19:36:') {
            $msg = Subscribe::where('is_send', 2)->get()->toArray();
            if ($msg) {
                //保证每个人每天只发一次
                $hasSendUserIds = [];
                foreach ($msg as $k => $v) {
                    $userId     = $v['user_id'];
                    if (in_array($userId, $hasSendUserIds)) {
                        continue;
                    }
                    $user       = User::where('id', $userId)->first();
                    $templateId = $v['template_id'];
                    $data       = [
                        'template_id' => $templateId, // 所需下发的订阅模板id
                        'touser'      => $user->open_id,     // 接收者（用户）的 openid
                        'page'        => '/pages/index/index',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
                        'data'        => [         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
                            'thing4' => [
                                'value' => '又到了吃饭时刻，快来领吃喝炒鸡优惠券啦！',
                            ],
                            'thing8' => [
                                'value' => '红包天天领，天天能提醒，叫外卖省省省',
                            ],
                        ],
                    ];
                    $config     = 'chi_he';
                    $app        = \EasyWeChat::miniProgram($config); // 小程序
                    $res        = $app->subscribe_message->send($data);

                    $subscribe          = Subscribe::where('id', $v['id'])->first();
                    $subscribe->is_send = 1;
                    $subscribe->send_at = date('Y-m-d H:i:s');
                    $updateRes          = $subscribe->save();

                    $logData = [
                        'time'       => date('Y-m-d H:i:s'),
                        'data'       => $data,
                        'res'        => $res,
                        'update_res' => $updateRes,
                    ];
                    $hasSendUserIds[] = $userId;
                    var_dump($logData);
                    Log::info('SendSubscribeMsg', $logData);

                }
            }
        }
        echo date('Y-m-d H:i:s') . 'leidage' . PHP_EOL;
        return;
    }
}

<?php
/**
 * 2020年04月19日 12:25
 */
namespace App\Http\Controllers\Api\ChiHe;

use App\Http\Controllers\Api\BaseController;
use App\Models\ChiHe\GetCouponLog;
use App\Models\ChiHe\Link;
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
class LinkController extends BaseController
{
    function index(Request $request)
    {
        $projects = Link::where([
            'status' => 1
        ])->orderBy('sort', 'DESC')->orderBy('created_at', 'DESC')->get()->toArray();
        return $this->success($projects);
    }
}

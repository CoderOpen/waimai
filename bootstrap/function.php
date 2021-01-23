<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/10/12
 * Time: 18:15
 */

// 获取ip地址
function getClientIp()
{
    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        // nginx 代理模式下，获取客户端真实IP
        $ip_address = $_SERVER['HTTP_X_REAL_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        // 客户端的ip
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // 浏览当前页面的用户计算机的网关
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) {
            unset($arr[$pos]);
        }
        $ip_address = trim($arr[0]);
    } else {
        $ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }
    return $ip_address;
}

/**
 * 根据ip获取客户端语言
 * @return string
 */
function getLanguage()
{
    if (empty($_COOKIE['lang'])) {
        $ip  = getClientIp();
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        try {
            $res = file_get_contents($url);
        } catch (Exception $e) {

        }
        if (!empty($res)) {
            $ipData = json_decode($res, true);
            if ($ipData['code'] == 0 && in_array($ipData['data']['country_id'], ['CN', 'HK', 'TW'])) {
                $_COOKIE['lang'] = 'zh';
            }
        }
        $_COOKIE['lang'] = 'en';
    }
    return $_COOKIE['lang'];
}











//生成确认key
function getVerifyKey($email, $type)
{
    return md5(config('backer.subscriber_confirm_key') . $email . $type);
}

//用户分类信息
function cate_info()
{
    $cate_info = config('backer.category_info');
    array_walk($cate_info, function (&$t) {
        $t = array_diff_key($t, ['status' => 0, 'label' => 0]);
    });
    return array_pluck($cate_info, 'cate_name', 'cate_id');
}

//用户状态信息
function status_info()
{
    $status_info = config('backer.category_info');
    array_walk($status_info, function (&$t) {
        $t = array_diff_key($t, ['cate_name' => 0, 'cate_id' => 0]);
    });
    return array_pluck($status_info, 'label', 'status');
}

//分类下的状态
function statusBelongToCate($id)
{
    $cate_info = config('backer.category_info');
    $status_info = array_filter($cate_info, function ($arr) use ($id) {
        return $arr['cate_id'] == $id;
    });

    array_walk($status_info, function (&$t) {
        $t = array_diff_key($t, ['cate_name' => 0, 'cate_id' => 0]);
    });

    return array_values($status_info);
}

//计算最大公约数
function max_gys($a, $b)
{
    if ($b == 0) {
        return $a;
    }
    return max_gys($b, $a % $b);

}

/**
 * 加密 和 解密 算法
 * @param  String $string 需要加密的字符
 * @param  String $operation decrypt表示解密，其它表示加密
 * @param  Int $expiry 有效期，秒数
 * @return  String $result, 为空表示解密失败，不存在
 */
function authcode($string, $operation = '', $expiry = 0)
{
    // 密匙
    $key = md5('Uh9j_jJ797j');
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'decrypt' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
    // 解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'decrypt' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'decrypt') {
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc . str_replace('=', '', base64_encode($result));
    }

}

//将数组编码解码
function arr_parse($data, $operation = 'encode')
{
    if ($operation == 'encode') {
        return base64_encode(http_build_query($data));
    } elseif ($operation == 'decode') {
        parse_str(base64_decode($data), $da);
        return $da;
    }
}

//数据加密
function arr_encrypt(Array $arr)
{
    return base64_encode(authcode(arr_parse($arr)));
}

//数据解密
function arr_decrypt($string)
{
    return arr_parse(authcode(base64_decode($string), 'decrypt'), 'decode');
}

//根据时间戳返回星期几
function week($time)
{
    return date('D', $time);
}

//无极限分类
function getTree($array, $pid =0, $level = 0){
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $value['level'] = $level;
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            getTree($array, $value['id'], $level+1);
        }
    }
    return $list;
}
function paramQuery($query, $index = '')
{
    $queryParts = explode('&', $query);
    $params = array();

    foreach ($queryParts as $param) {

        $item = explode('=', $param);

        $params[$item[0]] = $item[1];

    }
    return $params[$index] ?? $params;
}

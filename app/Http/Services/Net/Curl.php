<?php
/**
 * Created by PhpStorm.
 * User: 13616
 * Date: 2019/1/19
 * Time: 12:46
 */
namespace App\Http\Services\Net;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use http\Message\Body;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Curl {
    static function getData($url, $params = false, $ispost = 0, $header = [], $https = 0)
    {
        //dd($url, $params, $ispost);
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if ($header) {
            $newHeader = [];
            foreach ($header as $k => $v) {
                $newHeader[] = $k . ':' . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $newHeader);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        $error = curl_error($ch);

        if ($response === FALSE  || $error) {
            return false;
        }
        //$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //$httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        $result = json_decode($response, true) ? json_decode($response, true) : $response;
        return $result;
    }

    /**
     *  简单请求数据
     */
    static function guzzleRequest($url, $params, $timeout, $headers = [])
    {
        $client = new \GuzzleHttp\Client(['timeout' => $timeout, 'headers' => $headers, 'http_errors' => false,]);
        try {
            $data = [];
            if ($headers) {
                $data['headers'] = $headers;
            }
            $jar = new \GuzzleHttp\Cookie\CookieJar;
            $data['cookies'] = $jar;
            if (!$params) {
                $response = $client->request('GET', $url, $data);
            } else {
                $data = array_merge($data, $params);
                $response = $client->request('POST', $url, $data);
            }
            $body = $response->getBody();
            if ($body instanceof Stream) {
                $body = $body->getContents();
            }
            $res = json_decode($body, true);
            if ($res) {
                return $res;
            }
            return $body;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            echo $e->getTraceAsString();
            return  false;
        }
    }

    /**
     *  query必填成参数
     *  发送多个请求
     */
    static function guzzleMany($url, $isPost = false, $params = [], $headers = [], $cookies = [])
    {
        $client = new Client();
        //$url = 'https://tools.taokeduo.cn/a.php?';
        $requests = function () use ($url, $isPost, $params, $headers, $cookies) {
            $total = count($params);
            $headers = ['X-Foo' => 'Bar'];
            for ($i = 0; $i < $total; $i++) {
                if (!$isPost) {
                    $param    = $params[$i];
                    $wholeUrl = $url . "?";
                    foreach ($param as $k => $v) {
                        $wholeUrl .= $k . '=' . (string)$v . '&';
                   }
                    $url = rtrim($url, '&');
                    yield new Request('GET', $wholeUrl,[]);
                } else {
                    $body = [
                        'form_params' => $params[$i]
                    ];
                    file_put_contents('1.txt', $url . '|' . json_encode($body) . PHP_EOL, FILE_APPEND);
                    $headers['Content-Type'] = "multipart/form-data";
                    yield  new Request('POST', $url, $headers, json_encode($body));
                }
            }
        };

        $ret = [];

        $pool = new Pool($client, $requests(), [

            'concurrency' => 5,

            'fulfilled' => function (Response $response, $index) use (&$ret) {

                // this is delivered each successful response

                $contents = $response->getBody();
                if ($contents instanceof Stream) {
                    $contents = $contents->getContents();
                }
                $contents = json_decode($contents, true);

                $ret[$index] = $contents;

            },

            'rejected' => function (RequestException $reason, $index) {

                // this is delivered each failed request

            },

        ]);

        // Initiate the transfers and create a promise

        $promise = $pool->promise();

        // Force the pool of requests to complete.

        $promise->wait();

        echo json_encode($ret);


    }
}

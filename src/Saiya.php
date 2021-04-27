<?php

declare(strict_types=1);

namespace Verdient\Saiya;

use Tuupola\Base62Proxy;
use Verdient\HttpAPI\AbstractClient;
use Verdient\signature\Signature;

/**
 * Saiya
 * @author Verdient。
 */
class Saiya extends AbstractClient
{
    /**
     * @var string 授权编号
     * @author Verdient。
     */
    public $accessKey = '';

    /**
     * @var string 授权秘钥
     * @author Verdient。
     */
    public $accessSecret = '';

    /**
     * 获取请求
     * @return Request
     * @author Verdient。
     */
    public function request($path): Request
    {
        $this->request = Request::class;
        $request = parent::request($path);
        $request->bodySerializer = 'json';
        $request->addHeader('Authorization', $this->accessKey);
        $request->on(Request::EVENT_BEFORE_REQUEST, [$this, 'signature']);
        return $request;
    }

    /**
     * 签名
     * @param Request 请求对象
     * @author Verdient。
     */
    public function signature(Request $request){
        $signature = new Signature;
        $signature->key = $this->accessSecret;
        $url = parse_url($request->getUrl());
        $get = $url['query'] ?? '';
        $post = $request->getContent();
        $signString = $get . "\n" . $post . "\n";
        $sign = $signature->sign($signString, $this->accessSecret);
        $request->addHeader('Signature', Base62Proxy::encode(random_bytes(10) . $sign));
    }
}
<?php

declare(strict_types=1);

namespace Verdient\Saiya;

/**
 * 请求
 * @author Verdient。
 */
class Request extends \Verdient\http\Request
{
    /**
     * @var string 签名秘钥
     * @author Verdient。
     */
    public $accessSecret = null;

    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function send(): Response
    {
        return new Response(parent::send());
    }
}
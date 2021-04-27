<?php

declare(strict_types=1);

namespace Verdient\Saiya;

use Verdient\http\Response as HttpResponse;
use Verdient\HttpAPI\AbstractResponse;
use Verdient\HttpAPI\Result;

/**
 * 响应
 * @author Verdient。
 */
class Response extends AbstractResponse
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    protected function normailze(HttpResponse $response): Result
    {
        $result = new Result;
        $data = $response->getBody();
        if($response->getStatusCode() === 200 && isset($data['code']) && $data['code'] === 200){
            $result->isOK = true;
            $result->data = $data['data'] ?? null;
        }else{
            $result->errorCode = $data['code'] ?? $response->getStatusCode();
            $result->errorMessage = $data['message'] ?? $response->getRawContent();
        }
        return $result;
    }
}
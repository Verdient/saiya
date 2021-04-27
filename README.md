# Saiya Client
Saiya 客户端

## 创建客户端
```php
use Verdient\Saiya\Saiya;

/**
 * 创建客户端
 * @param string $host 主机地址
 * @param string $port 端口
 * @param string $accessKey 授权标识
 * @param string $accessSecret 授权秘钥
 */
$saiya = new Saiya([
    'host' => '127.0.0.1',
    'port' => 80,
    'accessKey' => 'XXX',
    'accessSecret' => 'XXXX'
]);
```
## 获取请求对象
```php

$request = $saiya->request($path);
```

## 设置请求参数
```php
$request->setHeaders([$name => $value, ...]); //设置请求头部
$request->setQuery([$name => $value, ...]); //设置查询参数
$request->setBody([$name => $value, ...]); //设置消息体参数
$request->setProxy($address, $port=null); //设置代理
$request->setTimeout($timeout); //设置超时时间
```
## 添加参数
`Header`, `Query`, `Body`均支持添加参数，相应方法为：
- addHeader($name, $value)
- addFilterHeader($name, $value)
- addQuery($name, $value)
- addFilterQuery($name, $value)
- addBody($name, $value)
- addFilterBody($name, $value)

其中`addFilterXXX`与`addXXX`的区别是`addFilterXXX`仅添加非空参数，而`addXXX`则无此限制
## 直接设置消息体
若消息体格式并非Key-Value格式或其他需要直接设置消息体的情况，可以直接调用
```php
$request->setContent($data, $serializer = null);
```
其中`$data`可以为`String`，`Array`或`Builder`及其子类的实例，`$serializer`为字符串或匿名函数。`setContent`的优先级比`setBody`的优先级高，即设置了Content后无论是否设置Body，在发送时均会忽略Body的内容
## 发送请求
```php
$response = $request->send();
```
## 响应
```php
$response->getIsOK(); //获取请求是否成功
$response->getData(); //获取请求地址
$response->getErrorCode(); //获取错误码
$response->getErrorMessage(); //获取错误信息
$response->getResponse(); //获取原始响应对象
$response->getResponse()->getRawResponse(); //获取响应原文
$response->getResponse()->getRawContent(); //获取消息体原文
$response->getResponse()->getRawHeaders(); //获取头部原文
$response->getResponse()->getBody(); //获取解析后的消息体参数
$response->getResponse()->getHeaders(); //获取解析后的头部
$response->getResponse()->getCookies(); //获取解析后的Cookie
$response->getResponse()->getStatusCode(); //获取状态码
$response->getResponse()->getContentType(); //获取消息体类型
$response->getResponse()->getCharset(); //获取字符集
$response->getResponse()->getStatusMessage(); //获取状态消息
$response->getResponse()->getHttpVersion(); //获取HTTP版本
```

## 批量请求
```php
use Verdient\http\BatchRequest;

/**
 * 传输组件配置
 * 内置了三种传输组件，分别是：
 *   cUrl, 基于cUrl的传输组件
 *   coroutine 基于Swoole的协程的传输组件
 *   stream 基于Streams的传输组件
 * 可自行新增或覆盖相应的传输组件
 */
$transports = [
    'cUrl' => 'Verdient\http\transport\CUrlTransport',
    'coroutine' => 'Verdient\http\transport\CoroutineTransport',
    'stream' => 'Verdient\http\transport\StreamTransport'
];

/**
 * 传输组件，默认为cUrl
 */
$transport = 'cUrl';

/**
 * 批大小，默认为100
 */
$batchSize = 100;

$batch = new BatchRequest([
    'batchSize' => $batchSize,
    'transports' => $transports,
    'transport' => $transport,
]);

/**
 * 请求对象的集合
 * 集合内的元素必须是Verdient\http\Request的实例
 */
$requests = [];


for($i = 0; $i < 100; $i++){
    $request = $saiya->request($path);
    $request->addQuery('id', $i);
    ...
    $requests[] = $request;
}

$batch->setRequests($requests);

/**
 * 返回内容为数组，keyValue对应关系与构造BatchRequest时传入的数组相同
 * 遍历返回的结果，结果与Request调用send方法后返回的内容一致，使用方法也相同
 */
$response = $batch->send();
```
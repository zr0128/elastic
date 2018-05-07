<?php
namespace elastic;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Stream;

trait ElasticReturn {
    private $_error = '';

    /**
     * 根据Guzzlehttp的相应，返回请求操作是否成功
     * @param ResponseInterface $response
     * @return bool
     */
    public function ret(ResponseInterface $response) {
        $body = $response->getBody();
        $content = '';

        if (is_a($body, Stream::class)) {
            while ( ! $body->eof()) {
                $content .= $body->read(1024);
            }
        }
        else {
            $content = $body;
        }

        $response = @json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->_error = json_last_error_msg();
            myLog(json_last_error_msg());
            return false;
        }

        if (property_exists($response, 'error')) {
            $this->_error = $response->error;
            myLog($content);

            return false;
        }

        return true;
    }

    /**
     * 获取请求相应的内容
     * @param ResponseInterface $response
     * @return string
     */
    public function getBody(ResponseInterface $response) {
        return self::body($response);
    }

    public static function body(ResponseInterface $response) {
        $body = $response->getBody();
        $content = '';

        if (is_a($body, Stream::class)) {
            while ( ! $body->eof()) {
                $content .= $body->read(1024);
            }
        }
        else {
            $content = $body;
        }

        return $content;
    }

    /**
     * 获取最后一次出错的内容
     * @return string
     */
    public function getLastError() {
        return $this->_error;
    }
}

function myLog($message) { // todo 应该从容器获取或者外部注入
    file_put_contents('/tmp/elastic', $message . "\n", FILE_APPEND);
}
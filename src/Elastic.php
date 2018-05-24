<?php
namespace elastic;

use elastic\query\ElasticBulkQuery;
use GuzzleHttp\Client;

class Elastic {
    use ElasticReturn;

    public static $client = null;
    private static $_elasticHost = '';

    public static function setHost(string $host) {
        self::$_elasticHost = $host;
    }

    /**
     * 提交查询请求
     * @param string $method 请求方式【POST GET DELETE PUT等】
     * @param string $uri
     * @param mixed $condition 查询条件，字符串或数组
     * @return string
     */
    public static function query(string $method, string $uri, $condition = '') {
        $condition = is_array($condition) ? ['json' => $condition] : ['body' => $condition];

        return self::body(
            self::client()->request($method, $uri, $condition)
        );
    }

    /**
     * 执行一个批量操作
     * @param string $uri 执行批量操作请求的uri
     * @param ElasticBulkQuery $query 批量请求查询对象实例
     * @return string
     */
    public static function bulk(string $uri, ElasticBulkQuery $query) {
        return self::query('POST', rtrim($uri, '/') . '/_bulk', $query->toJson());
    }

    public function reindex(string $index) { // todo reindex
    }

    public static function delete(string $uri) {
        $response = Elastic::client()->delete($uri);

        return $response->getStatusCode() == HttpCode::OK;
    }

    /**
     * 原子的切换别名
     * 两个参数都是类似['index' => 'my_index', 'alias' => 'old_alias']这样的数组。
     * @param array $removeAlias 要删除的别名定义
     * @param array $addAlias 新的别名定义
     * @return bool
     */
    public function aliases(array $removeAlias, array $addAlias) {
        return self::query('POST', '_aliases', [
            ['remove' => $removeAlias],
            ['add' => $addAlias],
        ]);
    }

    /**
     * 判断一个index是否存在
     * @param string $index
     * @return bool
     */
    public static function exists(string $index) {
        $response = self::client()->head($index);

        return $response->getStatusCode() != '404';
    }

    public static function client()
    {
        if ( ! self::$_elasticHost) {
            throw new ElasticException('未设置elasticsearch的主机地址');
        }

        return self::$client ?: (self::$client = new Client([ // todo 参数可配置
            'http_errors' => false,
            'stream' => false,
            'base_uri' => self::$_elasticHost,
            'connect_timeout' => 2.0,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'timeout' => 0,
        ]));
    }
}
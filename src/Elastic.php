<?php
namespace elastic;

use elastic\query\ElasticBulkQuery;
use elastic\query\IQuery;
use GuzzleHttp\Client;

class Elastic {
    use ElasticReturn;

    public static $client = null;
    private static $_elasticHost = '';

    public static function setHost(string $host) {
        self::$_elasticHost = $host;
    }

    public static function delete(string $uri) {
        $response = Elastic::client()->delete($uri);

        return $response->getStatusCode() == HttpCode::OK;
    }

    public static function query($method, string $uri, IQuery $query = null) {
        $client = self::client();
//        $options = ['headers' => [
//            'Content-Type' => 'application/json',
//        ]];
        if ( ! is_null($query)) {
            $options['body'] = $query->toJson();
        }

        return self::body($client->request($method, $uri, $options));
    }

    public static function bulk(string $uri, ElasticBulkQuery $query) {
        return self::query('POST', rtrim($uri, '/') . '/_bulk', $query);
    }

    public function reindex(string $index) { // todo reindex
    }

    /**
     * 创建索引
     * @param string $index 索引名
     * @param array $schema 数据类型定义
     * @return bool
     */
    public function addIndex(string $index, array $schema = []) {
        $mappings = [];
        if ( ! empty($schema)) {
            foreach ($schema as $type => $property) {
                $mappings[$type] = [
                    'properties' => $property
                ];
            }
        }

        $response = self::client()->request(
            'PUT',
            $index,
            ['json' => ['mappings' => $mappings]]
        );

        return $this->ret($response);
    }

    /**
     * 原子的切换别名
     * 两个参数都是类似['index' => 'my_index', 'alias' => 'old_alias']这样的数组。
     * @param array $removeAlias 要删除的别名定义
     * @param array $addAlias 新的别名定义
     * @return bool
     */
    public function aliases(array $removeAlias, array $addAlias) {
        $response = self::client()->request('POST', '_aliases', [
            'json' => [
                ['remove' => $removeAlias],
                ['add' => $addAlias],
            ]
        ]);

        return $this->ret($response);
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

    /**
     * 获取所有索引
     * @return string
     * @throws ElasticException
     */
    public static function all() {
        $response = self::client()->get('_cat/indices?v');

        if ($response->getStatusCode() != HttpCode::OK) {
            throw new ElasticException(
                'status code:' . $response->getStatusCode() . ', message: ' . $response->getBody());
        }

        return  $response->getBody();
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
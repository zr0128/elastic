<?php
namespace elastic\query;

/**
 * 批量操作
 * 示例：
 $bulk = new ElasticBulkQuery();
//        $bulk->addDelete(['_id' => 1]);
//        $bulk->addDelete(['_id' => 2]);
//        $bulk->addDelete(['_id' => 3]);
//        $json = Elastic::bulk('tao_test/t1/_bulk', $bulk);
//        var_dump(json_decode($json, true));exit;

$bulk->addCreate([
//            '_index' => 'tao_test',
//            '_type' => 't1',
'_id' => 1,
], ['name' => '山姆', 'age' => 62]);
$bulk->addCreate([
//            '_index' => 'tao_test',
//            '_type' => 't1',
'_id' => 2,
],
['name' => '田中一郎', 'age' => 36]);

$bulk->addCreate([
//            '_index' => 'tao_test',
//            '_type' => 't1',
'_id' => 3,
], ['name' => '林嘉茵', 'age' => 20]);

$json = Elastic::bulk('tao_test/t1', $bulk);
var_dump(json_decode($json, true));
 *
 * Class ElasticBulkQuery
 * @package elastic\query
 */
class ElasticBulkQuery extends AbstractQuery {
    private $_bulk = [];

    public function addIndex(array $metadata, array $mapData) {
        $this->_bulkAdd('index', $metadata, $mapData);
    }

    public function addCreate(array $metadata, array $mapData) {
        $this->_bulkAdd('create', $metadata, $mapData);
    }

    public function addUpdate(array $metadata, array $mapData) {
        $this->_bulkAdd('update', $metadata, $mapData);
    }

    public function addDelete(array $metadata) {
        $this->_bulkAdd('delete', $metadata);
    }

    private function _bulkAdd($method, array $metadata, array $mapData = []) {
        $this->_bulk[] = [$method => $metadata];
        if ($mapData) {
            $this->_bulk[] = $mapData;
        }
    }

    public function toJson() {
        $querySet = array_map('json_encode', $this->_bulk);

        return implode("\n", $querySet) . "\n";
    }
}
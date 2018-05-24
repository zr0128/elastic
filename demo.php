<?php
use elastic\query\ElasticQuery,
    elastic\query\ElasticBulkQuery,
    elastic\Elastic,
    elastic\ElasticSearch;

include 'vendor/autoload.php';

Elastic::setHost('http://117.50.0.28:9200');
//$body = Elastic::query('PUT', 'demo', [
//    'mappings' => [
//        'person' => [
//            'properties' => [
//                'name' => [
//                    'type' => 'text',
//                ],
//                'age' => [
//                    'type' => 'integer',
//                ],
//                'skill' => [
//                    'type' => 'keyword',
//                ],
//            ]
//        ]
//    ]
//]);

// var_dump(Elastic::exists('demo')); // output true

//$bulkQuery = new ElasticBulkQuery();
//$bulkQuery->addCreate(['_id' => 1], [
//    'name' => '蜡笔小新',
//    'age' => 3,
//    'skill' => '卖萌',
//]);
//$bulkQuery->addCreate(['_id' => 2], [
//    'name' => '路飞',
//    'age' => 16,
//    'skill' => '橡皮果实能力者',
//]);
//$results = Elastic::bulk('demo/person', $bulkQuery);
//var_dump(json_decode($results, true));
/* output
 array(3) {
  ["took"]=>
  int(14)
  ["errors"]=>
  bool(false)
  ["items"]=>
  array(2) {
    [0]=>
    array(1) {
      ["create"]=>
      array(9) {
        ["_index"]=>
        string(4) "demo"
        ["_type"]=>
        string(6) "person"
        ["_id"]=>
        string(1) "1"
        ["_version"]=>
        int(1)
        ["result"]=>
        string(7) "created"
        ["_shards"]=>
        array(3) {
          ["total"]=>
          int(2)
          ["successful"]=>
          int(2)
          ["failed"]=>
          int(0)
        }
        ["_seq_no"]=>
        int(0)
        ["_primary_term"]=>
        int(1)
        ["status"]=>
        int(201)
      }
    }
    [1]=>
    array(1) {
      ["create"]=>
      array(9) {
        ["_index"]=>
        string(4) "demo"
        ["_type"]=>
        string(6) "person"
        ["_id"]=>
        string(1) "2"
        ["_version"]=>
        int(1)
        ["result"]=>
        string(7) "created"
        ["_shards"]=>
        array(3) {
          ["total"]=>
          int(2)
          ["successful"]=>
          int(2)
          ["failed"]=>
          int(0)
        }
        ["_seq_no"]=>
        int(0)
        ["_primary_term"]=>
        int(1)
        ["status"]=>
        int(201)
      }
    }
  }
}
 */


$query = new ElasticQuery();
$query->term(['name' => '蜡笔小新']);
$query->add('size', 1);

$searcher = new ElasticSearch();
$searcher->setQuery($query);
$response = $searcher->search('demo/person/_search');

var_dump(json_decode($response, true));

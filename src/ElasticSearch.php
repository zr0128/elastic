<?php
namespace elastic;

class ElasticSearch extends AbstractElasticSearch{
    use ElasticReturn;

    public function search(string $uri) {
        return Elastic::query('GET', $uri, $this->_generateRequestBody());
    }
}
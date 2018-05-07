<?php
namespace elastic;

use elastic\query\IQuery;

class ElasticSearch extends AbstractElasticSearch{
    use ElasticReturn;

    public function search(string $uri) {
        return $this->getBody(
            Elastic::client()->request('GET', $uri, $this->_generateRequestBody())
        );
    }

    private function _generateRequestBody() {
        if ( ! $this->_query) {
            return [];
        }

        if ($this->_query instanceof IQuery) {
            return ['json' => $this->_query->getQuery()];
        }

        if (is_array($this->_query)) {
            return ['json' => $this->_query];
        }

        if (is_string($this->_query)) {
            return ['body' => $this->_query];
        }

        throw new ElasticException('不支持的查询条件，只支持json或数组');
    }
}
<?php
namespace elastic;

use elastic\query\IQuery;

abstract class AbstractElasticSearch implements IElasticSearch {
    /**
     * @var IQuery|string|array 查询条件
     */
    protected $_query = '';

    abstract public function search(string $uri);

    public function setQuery($query) {
        $this->_query = $query;
    }

    protected function _generateRequestBody() {
        if ($this->_query instanceof IQuery) {
            return $this->_query->getQuery();
        }

        if (is_array($this->_query) || is_string($this->_query)) {
            return $this->_query;
        }

        throw new ElasticException('不支持的查询条件，只支持json或数组');
    }
}
<?php
namespace elastic;

use elastic\query\IQuery;

abstract class AbstractElasticSearch implements IElasticSearch {
    /**
     * @var IQuery|string|array 查询条件
     */
    protected $_query = null;

    abstract public function search(string $uri);

    public function setQuery($query) {
        $this->_query = $query;
    }
}
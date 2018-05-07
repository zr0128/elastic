<?php
namespace elastic\query;

class AbstractQuery implements IQuery {
    private $_queries = null;
    protected $_condition = [];

    public function __construct() {
        $this->_queries = new \SplObjectStorage();
    }

    /**
     * 增加一条查询条件
     * @param string $field 条件字段名【query、match、size等】
     * @param array|IQuery $value 查询条件
     * @return $this
     */
    public function add(string $field, $value) {
        if ($field) {
            $this->_condition[$field] = $this->_value($value);
        }
        else {
            $this->_condition[] = $this->_value($value);
        }

        return $this;
    }

    public function addQuery(IQuery $query) {
        $this->_queries->attach($query);

        return $this;
    }

    public function compositeQueryAdd($method, $value) {
        if ( ! isset($this->_condition['bool'])) {
            $this->_condition['bool'] = [];
        }
        if ( ! isset($this->_condition['bool'][$method])) {
            $this->_condition['bool'][$method] = [];
        }
        $this->_condition['bool'][$method][] = $this->_value($value);

        return $this;
    }

    public function removeQuery(IQuery $query) {
        $this->_queries->detach($query);

        return $this;
    }

    public function getQuery() {
        foreach ($this->_queries as $query) {
            /** @var IQuery $query */
            $this->_condition = array_merge($this->_condition, $query->getQuery());
        }

        return $this->_condition;
    }

    public function toJson() {
        return json_encode($this->getQuery());
    }

    public function hasChildren() {
        return $this->_queries->count() > 0;
    }

    public function isEmpty() {
        return empty($this->_condition) && $this->_queries->count() === 0;
    }

    protected function _value($value) {
        return is_object($value) && is_a($value, self::class) ? current($value->getQuery()) : $value;
    }

    public function __toString() {
        return json_encode($this->getQuery());
    }
}

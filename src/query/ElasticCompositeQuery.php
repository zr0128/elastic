<?php
namespace elastic\query;

class ElasticCompositeQuery extends AbstractQuery {

    public function must($value) {
        return $this->compositeQueryAdd('must', $value);
    }

    public function mustNot($value) {
        return $this->compositeQueryAdd('must_not', $value);
    }

    public function should($value) {
        return $this->compositeQueryAdd('should', $value);
    }

    public function filter($value) {
        return $this->compositeQueryAdd('filter', $value);
    }

    /**
     * 以最大化的匹配到用户查询的字符串为高权重，而不是多字段匹配次数来计算权重。
     * @param mixed $queries dis_max的查询内容
     * @param array $options 其他的可选内容，如：tie_breaker
     * @return $this
     */
    public function disMax($queries, $options = []) {
        return $this->compositeQueryAdd(
            'dis_max',
            array_merge(['queries' => $this->_value($queries)], $options)
        );
    }
}
<?php
namespace elastic\query;

class ElasticQuery extends AbstractQuery {

    /**
     * 在一个字段中匹配查询匹配短语
     * @param $value
     * @return $this
     */
    public function matchPhrase($value) {
        return $this->_addQuery('match_phrase', $value);
    }

    /**
     * 在一个字段中匹配查询内容
     * @param $value
     * @return $this
     */
    public function match($value) {
        return $this->_addQuery('match', $value);
    }

    /**
     * 在多个字段中匹配一个查询内容
     * @param $keywords
     * @param array $fields 字段名，可以模糊匹配（*_field通配符）或者提升权重（field^boost）
     * @param array $options 其他选项
     *   type、tie_breaker、minimum_should_match、operator等
     * @return $this
     */
    public function multiMatch($keywords, array $fields, array $options = []) {
        $this->_condition['multi_match'] = array_merge([
            'query' => $keywords,
            'fields' => $fields,
        ], $options);

        return $this;
    }

    /**
     * 常量分值查询
     * @param $value
     * @return $this
     */
    public function constantScore($value) {
        return $this->_addQuery('constant_score', $this->_value($value));
    }

    public function range($value) {
        return $this->_addQuery('range', $value);
    }

    public function term($value) {
        return $this->_addQuery('term', $value);
    }

    public function terms(string $field, array $termSet) {
        return $this->_addQuery('terms', [$field => $termSet]);
    }

    public function exists($value) {
        return $this->_addQuery('exists', $value);
    }

    public function prefix($value) {
        return $this->_addQuery('prefix', $value);
    }

    public function regexp($value) {
        return $this->_addQuery('regexp', $value);
    }

    protected function _addQuery(string $field, $value) {
        return $this->add('query', [$field => $value]);
    }
}
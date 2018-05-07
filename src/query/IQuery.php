<?php
namespace elastic\query;

interface IQuery {
    /**
     * 新增一个查询条件
     * @param string $method 查询条件的key，如：match, term, filter等
     * @param $value
     * @return $this
     */
    public function add(string $method, $value);

    /**
     * 新增一个查询对象
     * @param IQuery $query
     * @return $this
     */
    public function addQuery(IQuery $query);

    /**
     * 删除增加到查询条件的查询对象
     * @param IQuery $query
     * @return mixed
     */
    public function removeQuery(IQuery $query);

    /**
     * 以数组形式返回构造的查询条件
     * @return array
     */
    public function getQuery();

    /**
     * 把构造的查询条件转换成json字符串并返回
     * @return string
     */
    public function toJson();

    /**
     * 是否有子查询对象
     * @return bool
     */
    public function hasChildren();
}

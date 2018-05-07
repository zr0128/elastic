<?php
namespace elastic;

interface IElasticSearch {
    public function search(string $uri);
}
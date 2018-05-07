<?php
namespace elastic;

class ElasticScrollSearch extends AbstractElasticSearch{
    use ElasticReturn;

    private $_body = [];
    private $_scrollId = '';
    private $_scrollTime = '';

    public function __construct(string $scrollTime) {
        $this->_scrollTime = $scrollTime;
    }

    public function search(string $uri) {
        $client = Elastic::client();
        $scrollId = $this->_scrollId ?: $this->_getScrollId("{$uri}/_search?scroll=" . $this->_scrollTime);
        if (false === $scrollId) {
            return false;
        }

        $requestBody = ['scroll' => $this->_scrollTime, 'scroll_id' => $scrollId];

        $response = $client->request('GET', '_search/scroll', [
            'json' => $requestBody + $this->_body
        ]);

        return $this->getBody($response);
    }

    public function setScrollId(string $scrollId) {
        $this->_scrollId = $scrollId;
    }

    public function sortDoc() {
        $this->_body['sort'] = ['_doc'];
    }

    private function _getScrollId($uri) {
        $body = $this->getBody(Elastic::client()->request('GET', $uri));
        $responseInfo = json_decode($body, true);
        if ( ! isset($responseInfo['scroll_id'])) {
            return false;
        }

        return $responseInfo['scroll_id'];
    }
}
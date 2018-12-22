<?php namespace HttpClient;
class Request
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';

    private $url;
    private $headers = [];
    private $cookies = [];
    private $proxy = null;
    private $method;
    private $data;

    /**
     * Request constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->setHeaders($this->getDefaultHeaders());
    }

    /**
     * @return array
     */
    private function getDefaultHeaders()
    {
        return [];
    }

    /**
     * @param $proxy
     * @return Request
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @param $header
     * @param $value
     * @return Request
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function setHeaders($headers = [])
    {
        foreach ($headers as $header => $value) {
            $this->setHeader($header, $value);
        }

        return $this;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function resetHeaders($headers = [])
    {
        $this->headers = $headers;
        return $this;
    }

    /**
    * @param $cookieName
    * @param $value
    * @return Request
    */
    public function setCookie($cookieName, $value)
    {
        $this->cookies[$cookieName] = $value;
        return $this;
    }

    /**
     * @param array $cookies
     * @return Request
     */
    public function setCookies($cookies = [])
    {
        foreach ($cookies as $cookieName => $value) {
            $this->setCookie($cookieName, $value);
        }

        return $this;
    }

    /**
     * @param array $cookies
     * @return Request
     */
    public function resetCookies($cookies = [])
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function hasProxy()
    {
        return !empty($this->proxy);
    }

    /**
     * @return null
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @return bool
     */
    public function hasCookies()
    {
        return !empty($this->cookies);
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $data
     * @return Request
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return !empty($this->data);
    }

    /**
     * @return bool
     */
    public function getData()
    {
        return $this->data;
    }
}
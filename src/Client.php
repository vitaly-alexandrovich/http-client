<?php namespace HttpClient;
class Client
{
    protected $cookies = [];
    protected $proxy;

    /**
     * @param $url
     * @return Response
     */
    public function get($url)
    {
        return $this->sendRequest((new Request($url)));
    }

    /**
     * @param $url
     * @param $data
     * @return Response
     */
    public function post($url, $data)
    {
        return $this->sendRequest((new Request($url))->setData($data));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $ch = curl_init();

        $cookies = array_merge($this->cookies, $request->getCookies());

        if (count($cookies) > 0) {
            $cookieString = '';

            foreach ($cookies as $key => $value) {
                $cookieString .= "$key=$value; ";
            }

            $request->setHeader('cookie', $cookieString);
        }

        curl_setopt_array($ch, [
            CURLOPT_URL             => $request->getUrl(),
            CURLOPT_CUSTOMREQUEST   => $request->getMethod(),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => true,
            CURLOPT_VERBOSE         => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 3,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_HTTPHEADER      => static::prepareHeaders($request->getHeaders()),
        ]);

        if ($request->hasData()) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getData());
        }

        $proxy = $this->getProxy();

        if ($request->hasProxy()) {
            $proxy = $request->getProxy();
        }

        if (!empty($proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }

        $response_raw = curl_exec($ch);
        $response = Response::createFromCurlResponse($response_raw, $ch);

        curl_close($ch);

        foreach ($response->getCookies() as $cookieName => $cookieValue) {
            $this->setCookie($cookieName, $cookieValue);
        }

        return $response;
    }

    /**
     * @param $headers
     * @return array
     */
    private static function prepareHeaders($headers)
    {
        $formattedHeaders = [];

        $combinedHeaders = array_change_key_case($headers);

        foreach ($combinedHeaders as $key => $val) {
            $formattedHeaders[] = static::getHeaderString($key, $val);
        }

        return $formattedHeaders;
    }

    /**
     * @param $key
     * @param $val
     * @return string
     */
    private static function getHeaderString($key, $val)
    {
        $key = trim(strtolower($key));
        return $key . ': ' . $val;
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     * @return Client
     */
    public function setCookies(array $cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * @param $cookieName
     * @param $cookieValue
     */
    public function setCookie($cookieName, $cookieValue)
    {
        $this->cookies[$cookieName] = $cookieValue;
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
     * @param $proxy
     * @return string|null
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function resetProxy()
    {
        return $this->setProxy(null);
    }
}
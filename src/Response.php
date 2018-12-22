<?php namespace HttpClient;

class Response
{
    public $body_raw;
    public $httpCode;
    public $headers = [];
    public $cookies = [];

    /**
     * Response constructor.
     * @param $content
     * @param array $headers
     * @param int $httpCode
     */
    public function __construct($content, $headers = [], $httpCode = 200)
    {
        $this->body_raw = $content;
        $this->headers  = $headers;
        $this->cookies  = static::parseCookies($this->getHeader('Set-Cookie', []));
        $this->httpCode = $httpCode;
    }

    /**
     * @return string|null
     */
    public function getBody()
    {
        return $this->body_raw;
    }

    /**
     * @return int|null
     */
    public function getCode()
    {
        return $this->httpCode;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $headerName
     * @return bool
     */
    public function hasHeader($headerName)
    {
        return isset($this->getHeaders()[$headerName]);
    }

    /**
     * @param $headerName
     * @param null $defaultValue
     * @return mixed|null
     */
    public function getHeader($headerName, $defaultValue = null)
    {
        return $this->hasHeader($headerName) ? $this->getHeaders()[$headerName] : $defaultValue;
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param $cookieName
     * @return bool
     */
    public function hasCookie($cookieName)
    {
        return isset($this->getCookies()[$cookieName]);
    }

    /**
     * @param $cookieName
     * @param null $defaultValue
     * @return mixed|null
     */
    public function getCookie($cookieName, $defaultValue = null)
    {
        return $this->hasCookie($cookieName) ? $this->getCookies()[$cookieName] : $defaultValue;
    }

    /**
     * @param $response
     * @param $curl
     * @return Response
     */
    public static function createFromCurlResponse($response, $curl)
    {
        $headers_size       = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers            = static::parseHeaders(substr($response, 0, $headers_size));
        $body_content       = substr($response, $headers_size);
        $httpCode           = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return new static($body_content, $headers, $httpCode);
    }

    /**
     * @param $raw_headers
     * @return array
     */
    public static function parseHeaders($raw_headers)
    {
        if (function_exists('http_parse_headers')) {
            return http_parse_headers($raw_headers);
        } else {
            $key = '';
            $headers = [];

            foreach (explode("\n", $raw_headers) as $i => $h) {
                $h = explode(':', $h, 2);

                if (isset($h[1])) {
                    if (!isset($headers[$h[0]])) {
                        $headers[$h[0]] = trim($h[1]);
                    } else if (is_array($headers[$h[0]])) {
                        $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                    } else {
                        $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                    }

                    $key = $h[0];
                } else {
                    if (substr($h[0], 0, 1) == "\t") {
                        $headers[$key] .= "\r\n\t".trim($h[0]);
                    } elseif (!$key) {
                        $headers[0] = trim($h[0]);
                    }
                }
            }

            return $headers;
        }
    }

    /**
     * @param $rawCookies
     * @return array
     */
    public static function parseCookies($rawCookies)
    {
        if (!is_array($rawCookies)) {
            $rawCookies = [$rawCookies];
        }

        $not_secure_cookies = [];
        $secure_cookies = [];

        foreach ($rawCookies as $cookie) {
            $cookie_array = 'not_secure_cookies';
            $cookie_parts = explode(';', $cookie);
            foreach ($cookie_parts as $cookie_part) {
                if (trim($cookie_part) == 'Secure') {
                    $cookie_array = 'secure_cookies';
                    break;
                }
            }
            $value = array_shift($cookie_parts);
            $parts = explode('=', $value);
            if (sizeof($parts) >= 2 && !is_null($parts[1])) {
                ${$cookie_array}[$parts[0]] = $parts[1];
            }
        }

        $cookies = $secure_cookies + $not_secure_cookies;
        return $cookies;
    }
}
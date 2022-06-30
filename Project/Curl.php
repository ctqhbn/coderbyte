<?php

require_once 'Project/Response.php';

/**
 * Class HTTP_Client
 *
 * Lightweight HTTP client capable of the following:
 *
 * Send HTTP requests to the given URL using different methods, such as GET, POST, etc.
 * Send JSON payloads
 * Send custom HTTP headers
 * Throw an exception for erroneous HTTP response codes (e.g. 4xx, 5xx)
 */
class Curl
{
    public function __construct(){}

    /**
     * GET request.
     *
     * @param string $url Request URL
     * @param array $body Request body
     * @param array $headers Request headers
     * @return mixed
     * @throws Exception
     */

  public function get($url, $body = null, $headers = [])
  {
    return $this->send('GET', $url, $body, $headers);
  }

  /**
   * POST request.
   *
   * @param string $url Request URL
   * @param array $body Request body
   * @param array $headers Request headers
   * @return mixed
   * @throws Exception
   */
  public function post($url, $body = null, $headers = [])
  {
    return $this->send('POST', $url, $body, $headers);
  }

  /**
   * PUT request.
   *
   * @param string $url Request URL
   * @param array $body Request body
   * @param array $headers Request headers
   * @return mixed
   * @throws Exception
   */
  public function put($url, $body = null, $headers = [])
  {
    return $this->send('PUT', $url, $body, $headers);
  }

  /**
   * DELETE request.
   *
   * @param string $url Request URL
   * @param array $body Request body
   * @param array $headers Request headers
   * @return mixed
   * @throws Exception
   */
  public function delete($url, $body = null, $headers = [])
  {
    return $this->send('DELETE', $url, $body, $headers);
  }

  /**
   * HEAD request.
   *
   * @param string $url Request URL
   * @param array $body Request body
   * @param array $headers Request headers
   * @return mixed
   * @throws Exception
   */
  public function head($url, $body = null, $headers = [])
  {
    return $this->send('HEAD', $url, $body, $headers);
  }

  /**
   * OPTIONS request.
   *
   * @param string $url Request URL
   * @param array $body Request body
   * @param array $headers Request headers
   * @return mixed
   * @throws Exception
   */
  public function options($url, $body = null, $headers = [])
  {
    return $this->send('OPTIONS', $url, $body, $headers);
  }

    /**
     * Build structure for HTTP Request.
     *
     * @param string $method Method (GET, POST, etc.)
     * @param string $url Request URL
     * @param array $body Request body
     * @param array $headers Request headers
     * @return array Request data: 0 - url; 1 - request options
     */
    private function buildRequest($method, $url, $body = null, $headers = [])
    {
        $content = '';

        $method = strtoupper($method);
        $headers = array_change_key_case($headers, CASE_LOWER);

        switch ($method) {
        case 'HEAD':
        case 'OPTIONS':
        case 'GET':
            $url = $this->buildUrlQuery($url, $body);
            break;
        case 'DELETE':
        case 'PUT':
        case 'POST':
            if (is_array($body)) {
            if (!empty($headers['content-type'])) {
                switch (trim($headers['content-type'])) {
                    case 'application/x-www-form-urlencoded':
                        $body = http_build_query($body);
                        break;
                    case 'application/json':
                        $body = json_encode($body);
                        break;
                }
            } else {
                $headers['content-type'] = 'application/x-www-form-urlencoded';
                $body = http_build_query($body);
            }
            } elseif (empty($headers['content-type'])) {
            $headers['content-type'] = 'application/x-www-form-urlencoded';
            }

            $content = $body;
            break;
        }
        $options = $this->generateOptions($method, $headers, $content);

        return [$url, $options];
    }

    /**
    * generate Options
    *
    * @param string $method Method (GET, POST, etc.)
    * @param array $content Request $content
    * @param array $headers Request headers
    * @return Response
    *
    * @return options
    */
    protected function generateOptions($method, $headers, $content){
        $options = [
            'http' => [
                'method' => $method,
            ],
        ];
        if ($headers) {
            $options['http']['header'] = implode(
                "\r\n",
                array_map(
                function ($v, $k) {
                    return sprintf("%s: %s", $k, $v);
                },
                $headers,
                array_keys($headers)
                )
            );
        }
        if ($content) {
            $options['http']['content'] = $content;
        }
        return $options;
    }

    /**
    * Build URL.
    *
    * @param string $url        URL.
    * @param array  $parameters Query string parameters.
    *
    * @return string
    */
    protected function buildUrlQuery($url, $parameters = [])
    {
        if (!empty($parameters)) {
            if (false !== strpos($url, '?')) {
                $url .= '&' . urldecode(http_build_query($parameters));
            } else {
                $url .= '?' . urldecode(http_build_query($parameters));
            }
        }
        return $url;
    }

    /**
    * Sends HTTP request.
    *
    * @param string $method Method (GET, POST, etc.)
    * @param string $url Request URL
    * @param array $body Request body
    * @param array $headers Request headers
    * @return Response
    * @throws Exception
    */
    public function send($method, $url, $body = null, $headers = [])
    {
        [$url, $options] = $this->buildRequest($method, $url, $body, $headers);

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            $status_line = implode(',', $http_response_header);
            preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
            $status = $match[1];

            // If the status code not in 2xx or 3xx, throw an exception.
            if (strpos($status, '2') !== 0 && strpos($status, '3') !== 0) {
                throw new \Exception("Unexpected response status: {$status} while fetching {$url}\n" . $status_line);
            }
        }

        return new Response($result, $http_response_header);
    }
}

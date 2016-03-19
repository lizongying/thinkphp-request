<?php

namespace Test\Service;


class HttpClientService
{

    /**
     * Respose A Http Request
     *
     * @param string $url
     * @param array $post
     * @param int $limit
     * @param string $cookie
     * @param integer $timeout
     * @param bool $block
     * @return string $return
     */
    public function httpRequest($url, $post = [], $limit = 0, $cookie = '', $timeout = 15, $block = TRUE)
    {
        $matches = parse_url($url);

        !isset($matches['host']) && $matches['host'] = '';
        !isset($matches['path']) && $matches['path'] = '';
        !isset($matches['query']) && $matches['query'] = '';
        !isset($matches['port']) && $matches['port'] = '';

        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;

//        如果请求为post
        if (count($post)) {
            $post = http_build_query($post);
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen($post) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }

        $fp = fsockopen($host, $port, $errno, $errstr, $timeout);

        if (!$fp) return ''; else {
            $header = $content = [];
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            fwrite($fp, $out);
            $status = stream_get_meta_data($fp);

//            未超时
            if (!$status['timed_out'])
            {

//                页头
                while (!feof($fp))
                {
                    $h = fgets($fp);
                    $header[] = $h;
                    if ($h && ($h == "\r\n" || $h == "\n"))
                    {
                        break;
                    }
                }

//                内容
                $stop = false;
                while (!feof($fp) && !$stop)
                {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $content .= $data;
                    if ($limit)
                    {
                        $limit -= strlen($data);
                        $stop = $limit <= 0;
                    }
                }
            }
            fclose($fp);

            $return = ['request'=>$out,'header'=>$header,'content'=>$content];
            return $return;
        }
    }
} 
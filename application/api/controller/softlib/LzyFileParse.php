<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use DOMDocument;
use DOMXPath;
use fast\Http;

class LzyFileParse extends Api
{
    protected $noNeedLogin = ['*'];
    protected string $baseUrl = 'https://www.lanzoui.com/';

    /**
     * 显示资源列表
     *
     * @return void
     */
    public function index(): void
    {
        $url = $this->request->param('url');
        $results = $this->getParams($url);
        $base_url = $results['base_url'] ?? '';
        $file_id = $results['file_id'] ?? '';
        $wp_sign = $results['wp_sign'] ?? '';
        $ajaxdata = $results['ajaxdata'] ?? '';

        if (empty($base_url) || empty($file_id)) {
            $this->error('无效的URL');
            return;
        }

        $ajax_url = "{$base_url}/ajaxm.php?file={$file_id}";
        $headers = [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
            "Referer: {$base_url}/{$file_id}"
        ];

        $post_data = [
            "action" => "downprocess",
            "websignkey" => $ajaxdata,
            "signs" => $ajaxdata,
            "sign" => $wp_sign,
            "websign" => "",
            "kd" => 1,
            "ves" => 1
        ];

        $response = Http::post($ajax_url, $post_data, [
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $result = json_decode($response, true);

        if (!isset($result['zt']) || $result['zt'] != 1 || empty($result['dom']) || empty($result['url'])) {
            $this->error('获取下载链接失败');
            return;
        }

        $direct_url = "{$result['dom']}/file/{$result['url']}";
        $headers = [
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8",
            "accept-language: zh-CN,zh;q=0.9",
            "sec-ch-ua: \"Chromium\";v=\"122\", \"Not(A:Brand\";v=\"24\", \"Microsoft Edge\";v=\"122\"",
            "sec-ch-ua-mobile: ?0",
            "sec-ch-ua-platform: \"Windows\"",
            "upgrade-insecure-requests: 1",
            "cookie: down_ip=1"
        ];

        $redirect_response = Http::get($direct_url, [], [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_FOLLOWLOCATION => false,
        ]);

        if (preg_match('/Location:\s*(.+)\s*/i', $redirect_response, $matches)) {
            $download_url = trim($matches[1]);
            $this->success('ok', ['url' => $download_url]);
        } else {
            $this->error('无法获取最终下载地址');
        }
    }

    /**
     * 获取参数
     * @param string $url
     */
    public function getParams(string $url): array
    {
        // 第一次请求
        $html = Http::get($url);

        // 提取 file_id（从 ?f= 到 & 之间）
        preg_match('/\?f=([^&]+)&/', $html, $matches);
        $file_id = $matches[1] ?? '';

        // 解析 iframe 的 src
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $iframe_node = $xpath->query("//iframe[contains(@class, 'n_downlink')]")->item(0);
        $url_temp = $iframe_node ? $iframe_node->getAttribute('src') : null;

        // 构建 base_url 和完整跳转 URL
        $url_parts = parse_url($url);
        $base_url = $url_parts['scheme'] . '://' . $url_parts['host'];
        $real_url = $base_url . $url_temp;

        // 第二次请求获取签名参数
        $html2 = Http::get($real_url);

        preg_match("/wp_sign\s*=\s*'([^']+)'/", $html2, $wp_sign_matches);
        $wp_sign = $wp_sign_matches[1] ?? '';
        preg_match("/ajaxdata\s*=\s*'([^']+)'/", $html2, $ajaxdata_matches);
        $ajaxdata = $ajaxdata_matches[1] ?? '';

        return [
            'base_url' => $base_url,
            'file_id' => $file_id,
            'wp_sign' => $wp_sign,
            'ajaxdata' => $ajaxdata
        ];
    }
}

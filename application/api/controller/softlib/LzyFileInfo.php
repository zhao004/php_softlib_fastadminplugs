<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use app\common\exception\UploadException;
use DOMDocument;
use DOMXPath;
use fast\Http;

class LzyFileInfo extends Api
{
    protected $noNeedLogin = ['*'];
    protected string $baseUrl = 'https://www.lanzoui.com/';

    /**
     * 显示资源列表
     *
     * @return void
     * @throws UploadException
     */
    public function index(): void
    {
        $url = $this->request->param('url');

        // 发起 GET 请求
        $html = Http::get($url);
        // 使用 DOMDocument 和 DOMXPath 解析 HTML
        //"ext-libxml": "*"
        libxml_use_internal_errors(true);
        //ext-dom": "*"
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // 判断内容是否包含"文件链接失效"
        $error_node = $xpath->query("//div[@class='off']");
        if ($error_node->length > 0) {
            $error_message = trim($error_node->item(0)->textContent);
            if (strpos($error_message, '文件链接失效') !== false) {
                $this->error('文件链接失效');
            } else {
                $this->error('无法获取文件信息，请检查链接是否正确');
            }
        }

        //文件图标
        $nodes = $xpath->query("//div[@class='n_box_ico']/span/img");
        foreach ($nodes as $img) {
            $src = $img->getAttribute('src');
            $file_icon = $src;
        }

        // 文件名
        $file_name_node = $xpath->query("//div[@id='filenajax']")->item(0);
        $file_name = $file_name_node ? trim($file_name_node->textContent) : '';

        // 文件类型/时间
        $file_infos = $xpath->query("//span[contains(@class, 'n_file_infos')]");
        $count = $file_infos->length;
        $file_type = $count > 0 ? trim($file_infos->item($count - 1)->textContent) : '';
        $file_time = $count > 1 ? trim($file_infos->item(0)->textContent) : '';

        // 文件大小
        $file_size_node = $xpath->query("//div[contains(@class, 'n_filesize')]")->item(0);
        $file_size = $file_size_node ? str_replace('大小：', '', trim($file_size_node->textContent)) : '';

        // 文件描述
        $file_desc_node = $xpath->query("//div[contains(@class, 'n_box_des')]")->item(0);
        $file_desc = $file_desc_node ? trim($file_desc_node->textContent) : '';
        $file_image = '';

        // 匹配 [图直链...] 或 <image>...</image> 或 <img src="...">
        if (strpos($file_desc, '[图直链') !== false) {
            $file_image = preg_match('/\[图直链(.*?)]/', $file_desc, $matches) ? $matches[1] : '';
            $file_desc = trim(str_replace("[图直链{$file_image}]", '', $file_desc));
        }
        if (strpos($file_desc, '<image>') !== false) {
            $file_image = preg_match('/<image>(.*?)<\/image>/', $file_desc, $matches) ? $matches[1] : '';
            $file_desc = trim(str_replace("<image>{$file_image}</image>", '', $file_desc));
        }
        if (strpos($file_desc, '<img src="') !== false) {
            $file_image = preg_match('/<img src="(.*?)"/', $file_desc, $matches) ? $matches[1] : '';
            $file_desc = trim(str_replace("<img src=\"{$file_image}\">", '', $file_desc));
        }
        $this->success('ok', [
            'file_icon' => $file_icon,
            'file_name' => $file_name,
            'file_time' => $file_time,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'file_desc' => $file_desc,
            'file_image' => $file_image,
        ]);
    }

    public function saveBase64Image($base64_image_content)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2]; // 获取图片类型
            $new_file = ROOT_PATH . 'public/uploads/' . date('Ymd') . '/';
            if (!file_exists($new_file)) {
                mkdir($new_file, 0700, true);
            }
            $filename = uniqid() . ".{$type}";
            $file_path = $new_file . $filename;
            echo $file_path;
            // 保存文件
            file_put_contents($file_path, base64_decode(str_replace($result[1], '', $base64_image_content)));
            // 返回相对路径保存到数据库
            return '/uploads/' . date('Ymd') . '/' . $filename;
        }
        return false;
    }
}
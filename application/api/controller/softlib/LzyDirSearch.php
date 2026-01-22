<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use fast\Http;

class LzyDirSearch extends Api
{
    protected $noNeedLogin = ['*'];
    protected string $baseUrl = 'https://www.lanzoui.com/';

    /**
     * 显示资源列表
     * @return void
     */
    public function index(): void
    {
        $url = $this->request->param('url');
        $keyWord = $this->request->param('keyword', '', 'trim');
        $sign = $this->getSign($url);
        $postData = [
            'wd' => $keyWord,
            'sign' => $sign,
        ];
        $results = Http::post($this->baseUrl . '/search/s.php', $postData);
        $results = json_decode($results, true);
        if (!is_array($results['item'])) $results['item'] = [];
        if (is_array($results['item'])) {
            foreach ($results['item'] as &$arr) {
                if (is_array($arr)) {
                    $arr['icon'] = 'https://image.woozooo.com/image/ico/' . strval($arr['ico']);
                    $arr['down'] = $this->baseUrl . $arr['id'];
                }
            }
        }
        $this->success($results['msg'], $results['item'] ?? [], $results['zt'] ?? 0);
    }

    /**
     * 获取参数
     * @param string $url
     * @return string
     */
    public function getSign(string $url): string
    {
        $html = Http::get($url);
        preg_match("/'sign':'(.*)'/", $html, $sign);
        return $sign[1] ?? '';
    }
}

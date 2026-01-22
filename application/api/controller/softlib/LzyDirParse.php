<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use fast\Http;
use think\Cache;

class LzyDirParse extends Api
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
        $pgs = $this->request->param('pgs', 1, 'intval');
        $pwd = $this->request->param('pwd', 'password');
        $postData = $this->getParams($url, $pgs, $pwd);
        $results = Http::post($this->baseUrl . 'filemoreajax.php', $postData);
        $results = json_decode($results, true);
        if (!is_array($results['text'])) $results['text'] = [];
        if (is_array($results['text'])) {
            foreach ($results['text'] as &$arr) {
                if (is_array($arr)) {
                    $arr['icon'] = 'https://image.woozooo.com/image/ico/' . strval($arr['ico']);
                    $arr['down'] = $this->baseUrl . $arr['id'];
                }
            }
        }
        $this->success($results['info'], $results['text'], $results['zt']);
    }

    /**
     * 获取参数
     * @param string $url
     * @param int $pgs
     * @param string $pwd
     * @return array
     */
    public function getParams(string $url, int $pgs, string $pwd): array
    {
        if (Cache::has($url)) {
            $results = Cache::get($url);
            $results['pg'] = $pgs;
            $results['pwd'] = $pwd;
            return $results;
        };
        $html = Http::get($url);
        preg_match("/t':(.*),/U", $html, $qt);
        preg_match("/k':(.*),/U", $html, $qk);
        preg_match("/$qt[1] = '(.*)'/U", $html, $t);
        preg_match("/$qk[1] = '(.*)'/U", $html, $k);
        preg_match("/fid':(.*),/U", $html, $fid);
        preg_match("/uid':'(.*)'/U", $html, $uid);
        $results = [
            'lx' => 2,
            'fid' => $fid[1],
            'uid' => $uid[1],
            'rep' => 0,
            't' => $t[1],
            'k' => $k[1],
            'up' => 1,
            'ls' => 1,
            'pg' => $pgs,
            'pwd' => $pwd,
        ];
        Cache::set($url, $results, 120);
        if ($pgs <= 1) return $results;
        // 如果第一次请求的pgs大于1，则需要模拟请求一次第一页
        $resultsOld = $results;
        $results['pg'] = 1;
        Http::post($this->baseUrl . 'filemoreajax.php', $results);
        //随机 等待2-3秒之间,使用毫秒级别的随机等待,以防止请求过快被限制
        usleep(rand(2500000, 3000000));
        return $resultsOld;
    }
}

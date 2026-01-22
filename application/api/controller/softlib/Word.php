<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use fast\Http;

class Word extends Api
{

    protected $noNeedLogin = ['*'];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $json = Http::get('https://open.iciba.com/dsapi/');
        $json = json_decode($json, true);
        $this->success('ok', $json['note']);
        //
    }

}

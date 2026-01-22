<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use think\Request;

class App extends Api
{
    protected $noNeedLogin = ['*'];
    private \app\admin\model\softlib\App $model;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new \app\admin\model\softlib\App();
    }

    /**
     * 显示资源列表
     *
     * @return void
     */
    public function index()
    {
        $result = $this->model
            ->where(['enable_switch' => 1])
            ->order('weigh', 'desc')
            ->field(['title', 'url'])
            ->select();
        $this->success('ok', $result);
        //
    }

}

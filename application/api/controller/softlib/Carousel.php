<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use think\exception\DbException;
use think\Request;

class Carousel extends Api
{

    protected $noNeedLogin = ['*'];
    private \app\admin\model\softlib\Carousel $model;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new \app\admin\model\softlib\Carousel();
    }


    /**
     * 显示资源列表
     *
     * @return void
     * @throws DbException
     */
    public function index(): void
    {
        $result = $this->model
            ->where(['enable_switch' => 1])
            ->order('weigh', 'desc')
            ->field(['type', 'title', 'image', 'url'])
            ->select();
        if (!empty($result) && is_array($result)) {
            foreach ($result as &$item) {
                if (!empty($item['image'])) {
                    $item['image'] = cdnurl($item['image'], true);
                }
            }
        }
        $this->success('ok', $result);
        //
    }
}

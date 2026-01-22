<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;
use think\exception\DbException;

class Referral extends Api
{
    protected $noNeedLogin = ['*'];
    private \app\admin\model\softlib\Referral $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new \app\admin\model\softlib\Referral();
    }


    /**
     * 显示资源列表
     * @return void
     * @throws DbException
     */
    public function index(): void
    {
        $result = $this->model
            ->where(['switch' => 1])
            ->field(['type', 'image', 'title', 'content', 'url'])
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

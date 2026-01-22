<?php

namespace app\api\controller\softlib;

use app\common\controller\Api;

class Config extends Api
{
    protected $noNeedLogin = ['*'];

    /**
     * 显示资源列表
     *
     * @return void
     */
    public function index()
    {
        $config = get_addon_config('softlib');
        if (!empty($config['feedback_group'])) {
            $config['feedback_group'] = cdnurl($config['feedback_group'], true);
        }
        if (!empty($config['feedback_user'])) {
            $config['feedback_user'] = cdnurl($config['feedback_user'], true);
        }
        $this->success('ok', $config);
        //
    }


}

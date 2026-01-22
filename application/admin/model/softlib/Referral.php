<?php

namespace app\admin\model\softlib;

use think\Model;


class Referral extends Model
{


    // 表名
    protected $name = 'softlib_referral';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
//        'type_text'
    ];


    public function getTypeList()
    {
        return ['page' => __('Type page'), 'url' => __('Type url')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


}

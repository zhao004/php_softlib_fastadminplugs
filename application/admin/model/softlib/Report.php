<?php

namespace app\admin\model\softlib;

use think\Model;


class Report extends Model
{


    // 表名
    protected $name = 'softlib_report';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function cat()
    {
        return $this->belongsTo('ReportCat', 'cat_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}

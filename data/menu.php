<?php

$menu = [
    [
        "name" => "softlib",
        "title" => "软件库APP",
        "icon" => "fa fa-list",
        "remark" => "用于管理软件库APP。",
        "ismenu" => 1,
        "sublist" => [
            [
                "name" => "softlib/report_cat",
                "title" => "线报分类",
                "ismenu" => 1,
                "weigh" => 995,
                "sublist" => [
                    [
                        "name" => "softlib/report_cat/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/report_cat/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/report_cat/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/report_cat/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/report_cat/multi",
                        "title" => "批量更新"
                    ]
                ]
            ],
            [
                "name" => "softlib/version",
                "title" => "版本列表",
                "ismenu" => 1,
                "menutype" => "addtabs",
                "weigh" => 996,
                "sublist" => [
                    [
                        "name" => "softlib/version/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/version/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/version/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/version/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/version/multi",
                        "title" => "批量更新"
                    ]
                ]
            ],
            [
                "name" => "softlib/report",
                "title" => "线报列表",
                "ismenu" => 1,
                "menutype" => "addtabs",
                "weigh" => 994,
                "sublist" => [
                    [
                        "name" => "softlib/report/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/report/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/report/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/report/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/report/multi",
                        "title" => "批量更新"
                    ]
                ]
            ],
            [
                "name" => "softlib/carousel",
                "title" => "首页轮播图",
                "ismenu" => 1,
                "menutype" => "addtabs",
                "weigh" => 999,
                "sublist" => [
                    [
                        "name" => "softlib/carousel/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/carousel/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/carousel/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/carousel/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/carousel/multi",
                        "title" => "批量更新"
                    ]
                ]
            ],
            [
                "name" => "softlib/app",
                "title" => "软件列表",
                "ismenu" => 1,
                "menutype" => "addtabs",
                "weigh" => 997,
                "sublist" => [
                    [
                        "name" => "softlib/app/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/app/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/app/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/app/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/app/multi",
                        "title" => "批量更新"
                    ]
                ]
            ],
            [
                "name" => "softlib/referral",
                "title" => "首页推荐",
                "ismenu" => 1,
                "menutype" => "addtabs",
                "weigh" => 998,
                "sublist" => [
                    [
                        "name" => "softlib/referral/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/referral/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/referral/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/referral/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/referral/multi",
                        "title" => "批量更新"
                    ]
                ]
            ],
            [
                "name" => "softlib/access_logs",
                "title" => "访问日志",
                "ismenu" => 1,
                "sublist" => [
                    [
                        "name" => "softlib/access_logs/index",
                        "title" => "查看"
                    ],
                    [
                        "name" => "softlib/access_logs/add",
                        "title" => "添加"
                    ],
                    [
                        "name" => "softlib/access_logs/edit",
                        "title" => "编辑"
                    ],
                    [
                        "name" => "softlib/access_logs/del",
                        "title" => "删除"
                    ],
                    [
                        "name" => "softlib/access_logs/multi",
                        "title" => "批量更新"
                    ]
                ]
            ]
        ]
    ]
];
return $menu;

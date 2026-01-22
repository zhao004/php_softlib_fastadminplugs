define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'softlib/carousel/index' + location.search,
                    add_url: 'softlib/carousel/add',
                    edit_url: 'softlib/carousel/edit',
                    del_url: 'softlib/carousel/del',
                    multi_url: 'softlib/carousel/multi',
                    import_url: 'softlib/carousel/import',
                    table: 'softlib_carousel',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [[{checkbox: true}, {field: 'id', title: __('Id')}, {
                    field: 'type',
                    title: __('Type'),
                    searchList: {"no": __('Type no'), "url": __('Type url')},
                    formatter: Table.api.formatter.label
                }, {field: 'title', title: __('Title'), operate: 'LIKE'}, {
                    field: 'image',
                    title: __('Image'),
                    operate: false,
                    events: Table.api.events.image,
                    formatter: Table.api.formatter.image
                }, {field: 'url', title: __('Url'), operate: 'LIKE', formatter: Table.api.formatter.url}, {
                    field: 'enable_switch',
                    title: __('Enable_switch'),
                    table: table,
                    formatter: Table.api.formatter.toggle
                }, {field: 'weigh', title: __('Weigh'), operate: false}, {
                    field: 'createtime',
                    title: __('Createtime'),
                    operate: 'RANGE',
                    addclass: 'datetimerange',
                    formatter: Table.api.formatter.datetime
                }, {
                    field: 'updatetime',
                    title: __('Updatetime'),
                    operate: 'RANGE',
                    addclass: 'datetimerange',
                    formatter: Table.api.formatter.datetime
                }, {
                    field: 'operate',
                    title: __('Operate'),
                    table: table,
                    events: Table.api.events.operate,
                    formatter: Table.api.formatter.operate
                }]]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        }, add: function () {
            Controller.api.bindevent();
        }, edit: function () {
            Controller.api.bindevent();
        }, api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});

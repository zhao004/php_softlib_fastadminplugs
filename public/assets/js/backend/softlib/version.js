define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'softlib/version/index' + location.search,
                    add_url: 'softlib/version/add',
                    edit_url: 'softlib/version/edit',
                    del_url: 'softlib/version/del',
                    multi_url: 'softlib/version/multi',
                    import_url: 'softlib/version/import',
                    table: 'softlib_version',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [[{checkbox: true}, {field: 'id', title: __('Id')}, {
                    field: 'version', title: __('Version'), operate: 'LIKE', formatter: Table.api.formatter.label,
                }, {field: 'title', title: __('Title'), operate: 'LIKE'}, {
                    field: 'content', title: __('Content'), formatter: Table.api.formatter.content, class: 'autocontent'
                }, {
                    field: 'dow_url', title: __('Dow_url'), formatter: Table.api.formatter.url,
                }, {
                    field: 'forced_switch',
                    title: __('Forced_switch'),
                    table: table,
                    formatter: Table.api.formatter.toggle
                }, {
                    field: 'enable_switch',
                    title: __('Enable_switch'),
                    table: table,
                    formatter: Table.api.formatter.toggle
                }, {
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

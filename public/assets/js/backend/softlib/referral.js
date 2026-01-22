define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'softlib/referral/index' + location.search,
                    add_url: 'softlib/referral/add',
                    edit_url: 'softlib/referral/edit',
                    del_url: 'softlib/referral/del',
                    multi_url: 'softlib/referral/multi',
                    import_url: 'softlib/referral/import',
                    table: 'softlib_referral',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [[{checkbox: true}, {field: 'id', title: __('Id')}, {
                    field: 'type',
                    title: __('Type'),
                    searchList: {"page": __('Type page'), "url": __('Type url')},
                    formatter: Table.api.formatter.label
                }, {
                    field: 'image',
                    title: __('Image'),
                    operate: false,
                    events: Table.api.events.image,
                    formatter: Table.api.formatter.image
                }, {field: 'title', title: __('Title'), operate: 'LIKE'}, {
                    field: 'content',
                    title: __('Content'),
                    operate: 'LIKE',
                    formatter: Table.api.formatter.content,
                    class: 'autocontent'
                }, {
                    field: 'url',
                    title: __('Url'),
                    operate: 'LIKE',
                    formatter: Table.api.formatter.url
                }, {field: 'switch', title: __('Switch'), table: table, formatter: Table.api.formatter.toggle}, {
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

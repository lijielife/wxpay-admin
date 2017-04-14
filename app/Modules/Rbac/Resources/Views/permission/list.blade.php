<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
        <!-- 选项卡start -->
        <div class="tab_wrap">
            <!-- 选项卡内容start -->
            <div class="tab_con">
                <!-- 这里是每个选项卡详细内容 -->
                <form id="jq_search_form_permission" action="/cms/base/permission/datagrid.json" method="post">
                {!! csrf_field() !!}
                <!-- 这里是每个选项卡详细内容 -->
                <div class="pay_box1">
                        <div class="fl w_100">权限名：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="name" name="name" placeholder="模糊查询"/></span></div>
                        <div class="fl w_100">别名：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="slug" name="slug" placeholder="模糊查询"/></span></div>
                        <ul class="pay_btns pb">
                            <li><span  id="jq_search_btn_permission" class="pay_btn btn_blue">查询</span></li>
                        </ul>
                        <div class="clear"></div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_permission" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){

    $.base("base.myeasyui.mydatagrid.permissionGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.permissionGrid.defaults = {
        dialogTitle: '权限列表',
        dialogWidth: 350,
        dialogHeight: 220,
        showAddButton: true,
        showEditButton: true,
        showDeleteButton: true,
        url: '/rbac/permission/datagrid.json',
        forwardAddUrl: '/rbac/permission/add',
        forwardEditUrl: '/rbac/permission/edit',
        expExcelUrl: '/rbac/permission/export',
        deleteUrl: '/rbac/permission/destroy',
        resetPwdUrl: '/rbac/permission/password',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        columns: [[
            {
                width: '5%',
                title: 'ID',
                field: 'id',
                checkbox: true
            },
            {
                width: '12.5%',
                title: '所属菜单',
                field: 'menus',
                formatter: function(value, row, index){
                    return value[0] ? value[0].name : '无';
                }
            },
            {
                width: '12.5%',
                title: '权限名',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '别名',
                field: 'slug'
            },
            {
                width: '12.5%',
                title: '描述',
                field: 'description'
            },
            {
                width: '12.5%',
                title: '模型',
                field: 'model'
            },
            {
                width: '20%',
                title: '创建日期',
                field: 'created_at'
            },
            {
                width: '20%',
                title: '更新日期',
                field: 'updated_at'
            }
        ]]
    };

    $(this)['permissionGrid']({className: 'permission'});
    // console.log($(this)['permissionGrid']);
    // console.log($.data(this, 'permissionGrid'));
    $.data(this, 'permissionGrid').init();
});
</script>

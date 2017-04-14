<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
        <!-- 选项卡start -->
        <div class="tab_wrap">
            <!-- 选项卡内容start -->
            <div class="tab_con">
                <!-- 这里是每个选项卡详细内容 -->
                <form id="jq_search_form_user" action="/cms/base/user/datagrid.json" method="post">
                {!! csrf_field() !!}
                <!-- 这里是每个选项卡详细内容 -->
                <div class="pay_box1">
                        <div class="fl w_100">邮箱：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="email" name="email" placeholder="模糊查询"/></span></div>
                        <!-- <div class="fl w_100">所属商户：</div>
                        <div class="fl w_150">
                            <input type="text" id="user_merchant" name="orgId" class="inputxt" />
                        </div> -->
                        <ul class="pay_btns pb">
                            <li><span  id="jq_search_btn_user" class="pay_btn btn_blue">查询</span></li>
                            <!-- <li><span id="jq_exp_btn_user" class="pay_btn btn_white">导出</span>
                            <a href="#" target="_blank" style="display: none;"><span id="jq_exp_btn_user_s"> </span></a>
                            </li>
                            <li>
                              <a id="userExportHelpIcon" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-help'"> </a>
                            </li> -->
                        </ul>
                        <div class="clear"></div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_user" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    $.base("base.myeasyui.mydatagrid.userGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.userGrid.defaults = {
        dialogTitle: '管理用户列表',
        dialogWidth: 350,
        dialogHeight: 220,
        showAddButton: true,
        showEditButton: true,
        showDeleteButton: true,
        url: '/rbac/user/datagrid.json',
        forwardAddUrl: '/rbac/user/add',
        forwardEditUrl: '/rbac/user/edit',
        expExcelUrl: '/rbac/user/export',
        deleteUrl: '/rbac/user/destroy',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        // fitColumns: true,
        columns: [[
            {
                width: '5%',
                title: 'ID',
                field: 'id',
                checkbox: true
            },
            {
                width: '12.5%',
                title: '姓名',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '邮箱',
                field: 'email'
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

    $(this)['userGrid']({className: 'user'});
    $.data(this, 'userGrid').init();
});
</script>

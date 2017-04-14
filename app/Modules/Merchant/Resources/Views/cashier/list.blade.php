<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
        <!-- 选项卡start -->
        <div class="tab_wrap">
            <!-- 选项卡内容start -->
            <div class="tab_con">
                <!-- 这里是每个选项卡详细内容 -->
                <form id="jq_search_form_cashier" action="/merchant/cashier/datagrid.json" method="post">
                {!! csrf_field() !!}
                <!-- 这里是每个选项卡详细内容 -->
                <div class="pay_box1">
                        <div class="fl w_100">收银员：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="name" name="name" placeholder="模糊查询"/></span></div>
                        <ul class="pay_btns pb">
                            <li><span  id="jq_search_btn_cashier" class="pay_btn btn_blue">查询</span></li>
                        </ul>
                        <div class="clear"></div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_cashier" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    $.base("base.myeasyui.mydatagrid.cashierGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.cashierGrid.defaults = {
        dialogTitle: '收银员列表',
        dialogWidth : 350,
        dialogHeight : 400,
        @permission('cashier.add')
        showAddButton: true,
        forwardAddUrl: '/merchant/cashier/add',
        @endpermission
        @permission('cashier.edit')
        showEditButton: true,
        forwardEditUrl: '/merchant/cashier/edit',
        @endpermission
        @permission('cashier.delete')
        showDeleteButton: true,
        deleteUrl: '/merchant/cashier/destroy',
        deleteBtnName : '删除&nbsp;<span id="deleteHelpIcon", class="icon-help">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
        deleteTips:["收银员删除后无法恢复，历史交易数据也将会清空，请确认是否删除。 ", "请选中要删除的记录。"],
        @endpermission
        url: '/merchant/cashier/datagrid.json',
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
                width: '11%',
                title: '收银员姓名',
                field: 'name'
            },
            {
                width: '11%',
                title: '性别',
                field: 'sex',
            },
            {
                width: '11%',
                title: '手机号码&nbsp;<a id="cashierPhoneHelpIcon" href="javascript:void(0)" class="icon-help">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>',
                field: 'mobile',
            },
            {
                width: '11%',
                title: '所属商户',
                field: 'merchant',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '11%',
                title: '所属部门',
                field: 'department_name',
            },
            {
                width: '11%',
                title: '邮箱',
                field: 'email',
            },
            {
                width: '11%',
                title: '职务',
                field: 'duty',
            },
            {
                width: '11%',
                title: '是否启用',
                field: 'enabled',
            },
            {
                width: '11%',
                title: '创建日期',
                field: 'created_at'
            },
            {
                width: '11%',
                title: '更新日期',
                field: 'updated_at'
            }
        ]]
    };

    $(this)['cashierGrid']({className: 'cashier'});
    $.data(this, 'cashierGrid').init();

    $("#deleteHelpIcon").tooltip({
        position: 'top',
        content: '<span style="font-size: 12px;line-height: 1.5;color: #333;">温馨提示：收银员删除后，公众号无法登录交易，历史交易数据也将会清空</span>',
        onShow: function(){
            $(this).tooltip('tip').css({
                backgroundColor: 'rgba(255,255,255,0.8)',
                borderColor: '#ccc'
            });
        }
    });

    $("#cashierPhoneHelpIcon").tooltip({
        position: 'top',
        content: '<span style="font-size: 12px;line-height: 1.5;color: #333;">支持用此账号登陆公众号收款</span>',
        onShow: function(){
            $(this).tooltip('tip').css({
                backgroundColor: 'rgba(255,255,255,0.8)',
                borderColor: '#ccc'
            });
        }
    });

});
</script>

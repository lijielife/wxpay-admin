<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
        <!-- 选项卡start -->
        <div class="tab_wrap">
            <!-- 选项卡内容start -->
            <div class="tab_con">
                <!-- 这里是每个选项卡详细内容 -->
                <form id="jq_search_form_bank" action="/bank/datagrid.json" method="post">
                {!! csrf_field() !!}
                <!-- 这里是每个选项卡详细内容 -->
                <div class="pay_box1">
                        <div class="fl w_100">银行名称：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="name" name="name" placeholder="模糊查询"/></span></div>
                        <ul class="pay_btns pb">
                            <li><span  id="jq_search_btn_bank" class="pay_btn btn_blue">查询</span></li>
                        </ul>
                        <div class="clear"></div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_bank" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    $.base("base.myeasyui.mydatagrid.bankGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.bankGrid.defaults = {
        dialogTitle: '银行列表',
        dialogWidth: 430,
        dialogHeight: 220,
        showAddButton: true,
        showEditButton: true,
        showDeleteButton: true,
        url: '/bank/datagrid.json',
        forwardAddUrl: '/bank/add',
        forwardEditUrl: '/bank/edit',
        expExcelUrl: '/bank/export',
        deleteUrl: '/bank/destroy',
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
                title: '银行名称',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '代号',
                field: 'slug'
            },
            {
                width: '12.5%',
                title: '备注',
                field: 'remark'
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

    $(this)['bankGrid']({className: 'bank'});
    $.data(this, 'bankGrid').init();
});
</script>

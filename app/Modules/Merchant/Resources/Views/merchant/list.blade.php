<div data-options="region:'center',fit:false,border:false" style="height:100%;">
    <div class="easyui-layout" data-options="fit:true,border:false">
        <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
            <div class="easyui-layout" data-options="fit:true,border:false">
                <div data-options="region:'center',split:false,border:false,split:false" style="height:140px; overflow: hidden;">
                    <table id="jq_datagrid_merchant" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
                <div id="layout_merchant_pay_conf" data-options="region:'east',title:'商户支付配置信息',border:true,split:true,headerCls:'left-panel-header'" style="width:35%;border-color: #dddddd;">
                    <table id="jq_datagrid_merchantPayConf" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    var merchantFuncTree;

    $.base("base.myeasyui.mydatagrid.merchantGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.merchantGrid.defaults = {
        dialogTitle: '商户列表',
        dialogWidth: 1100,
        dialogHeight: 540,
        editDialogHeight: 300,
        examineDialogHeight: 450,
        activateDialogHeight: 450,
        @permission('merchant.add')
        showAddButton: true,
        forwardAddUrl: '/merchant/add',
        @endpermission
        @permission('merchant.edit')
        showEditButton: true,
        forwardEditUrl: '/merchant/edit',
        @endpermission
        @permission('merchant.delete')
        showDeleteButton: true,
        @endpermission
        @permission('merchant.examine')
        showExamineButton : true,
        forwardExamineUrl: '/merchant/examine',
        @endpermission
        @permission('merchant.activate')
        showActivateButton : true,
        forwardActivateUrl : '/merchant/activate',
        @endpermission
        url: '/merchant/datagrid.json',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        onClickRow : function (index, row) {
            var gridOptions = $('#jq_datagrid_merchantPayConf').datagrid('options');
            gridOptions.url = '/merchant/payment/datagrid.json';
            $('#jq_datagrid_merchantPayConf').datagrid('load', {merchant_id : row.id, _token: '{{ csrf_token() }}'});

            var panelEast = $('#layout_merchant_pay_conf').panel();
            panelEast.panel('setTitle', '商户【' + row.name + '】的支付类型配置信息');
        },
        columns: [[
            {
                width: '5%',
                title: 'ID',
                field: 'id',
                checkbox: true
            },
            {
                width: '12.5%',
                title: '商户名称',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '商户类型',
                field: 'type'
            },
            {
                width: '12.5%',
                title: '所属渠道',
                field: 'channel',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '12.5%',
                title: '所属大商户',
                field: 'parent',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '12.5%',
                title: '审核状态',
                field: 'examine_status'
            },
            {
                width: '12.5%',
                title: '激活状态',
                field: 'activate_status'
            },
            {
                width: '18%',
                title: '创建日期',
                field: 'created_at'
            },
            {
                width: '18%',
                title: '更新日期',
                field: 'updated_at'
            }
        ]]
    };

    $(this)['merchantGrid']({className: 'merchant'});
    $.data(this, 'merchantGrid').init();

    //------------------------------------------------------------------------------------//

    $.base("base.myeasyui.mydatagrid.merchantPayConfGrid", {
        init: function() {
            this._super("_init");
        },
        'addFun.toggleClass': function (options) {
            return function (opts) {
                var selectRow = $('#jq_datagrid_merchant').datagrid('getSelected');
                var merchantId = 0;
                if (selectRow) {
                    merchantId = selectRow.id;
                }
                if (!merchantId) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '请选择商户所在的行!',
                        icon: 'warning',
                        time: 3
                    });
                    return;
                }
                var dialog = util.createWindow({
                    title : '添加' + (opts.dialogTitle || ''),
                    href : opts.forwardAddUrl + '?merchant_id=' + merchantId,
                    width : opts.dialogWidth,
                    height : opts.dialogHeight,
                    button : [{
                        name : '保存',
                        focus : true,
                        callback : function() {
                            util.createWindow.openner_dataGrid = options.self.grid;
                            var addForm = util.createWindow[options.addFormIdPrefix + options.className];
                            addForm.submitForm();
                            return false;
                        }
                    }]
                });
            };
        },
        'editFun.toggleClass': function (options) {
            return function (opts) {
                var selectRow = $('#jq_datagrid_merchantPayConf').datagrid('getSelected');
                var merchantPaymentId = 0;
                if (selectRow) {
                    merchantPaymentId = selectRow.id;
                }
                if (!merchantPaymentId) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '请选择商户所在的行!',
                        icon: 'warning',
                        time: 3
                    });
                    return;
                }
                var dialog = util.createWindow({
                    title : '编辑' + (opts.dialogTitle || ''),
                    href : opts.forwardEditUrl + '?merchant_payment_id=' + merchantPaymentId,
                    width : opts.dialogWidth,
                    height : opts.dialogHeight,
                    button : [{
                        name : '保存',
                        focus : true,
                        callback : function() {
                            util.createWindow.openner_dataGrid = options.self.grid;
                            var editForm = util.createWindow[options.editFormIdPrefix + options.className];
                            console.log(editForm);
                            editForm.submitForm();
                            return false;
                        }
                    }]
                });
            };
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.merchantPayConfGrid.defaults = {
        dialogTitle: '商户支付类型',
        dialogWidth: 1100,
        dialogHeight: 450,
        editDialogHeight: 300,
        @permission('merchant.payment.add')
        showAddButton: true,
        forwardAddUrl: '/merchant/payment/add',
        @endpermission
        @permission('merchant.payment.edit')
        showEditButton: true,
        forwardEditUrl: '/merchant/payment/edit',
        @endpermission
        @permission('merchant.payment.activate')
        showActivateButton : true,
        forwardActivateUrl : '/merchant/payment/activate',
        @endpermission
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        onClickRow : function (rowData) {

        },
        columns: [[
            {
                width: '30%',
                title: '支付类型',
                field: 'payment',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '30%',
                title: '结算费率(‰)',
                field: 'billing_rate',
                formatter: function(value, row, index) {
                    return value * 1000 + '‰';
                }
            },
            {
                width: '30%',
                title: '激活状态',
                field: 'activate_status'
            }
        ]]
    };

    $(this)['merchantPayConfGrid']({className: 'merchantPayConf'});
    $.data(this, 'merchantPayConfGrid').init();
});
</script>

<div data-options="region:'center',fit:false,border:false" style="height:100%;">
    <div class="easyui-layout" data-options="fit:true,border:false">
        <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
            <div class="easyui-layout" data-options="fit:true,border:false">
                <div data-options="region:'center',split:false,border:false,split:false" style="height:140px; overflow: hidden;">
                    <table id="jq_datagrid_channel" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
                <div id="layout_channel_pay_conf" data-options="region:'east',title:'渠道支付配置信息',border:true,split:true,headerCls:'left-panel-header'" style="width:35%;border-color: #dddddd;">
                    <table id="jq_datagrid_channelPayConf" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    var channelFuncTree;

    $.base("base.myeasyui.mydatagrid.channelGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.channelGrid.defaults = {
        dialogTitle: '渠道列表',
        dialogWidth: 780,
        dialogHeight: 540,
        editDialogHeight: 300,
        examineDialogHeight: 450,
        activateDialogHeight: 450,
        @permission('channel.add')
        showAddButton: true,
        forwardAddUrl: '/channel/add',
        @endpermission
        @permission('channel.edit')
        showEditButton: true,
        forwardEditUrl: '/channel/edit',
        @endpermission
        @permission('channel.delete')
        showDeleteButton: true,
        deleteUrl: '/channel/destroy',
        @endpermission
        @permission('channel.examine')
        showExamineButton : true,
        forwardExamineUrl : '/channel/examine',
        @endpermission
        @permission('channel.activate')
        showActivateButton : true,
        forwardActivateUrl : '/channel/activate',
        @endpermission
        url: '/channel/datagrid.json',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        onClickRow : function (index, row) {
            var gridOptions = $('#jq_datagrid_channelPayConf').datagrid('options');
            gridOptions.url = '/channel/payment/datagrid.json';
            $('#jq_datagrid_channelPayConf').datagrid('load', {channel_id : row.id, _token: '{{ csrf_token() }}'});

            var panelEast = $('#layout_channel_pay_conf').panel();
            panelEast.panel('setTitle', '渠道【' + row.name + '】的支付类型配置信息');
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
                title: '渠道名称',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '渠道类型',
                field: 'type'
            },
            {
                width: '12.5%',
                title: '上级渠道',
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

    $(this)['channelGrid']({className: 'channel'});
    $.data(this, 'channelGrid').init();

    //------------------------------------------------------------------------------------//

    $.base("base.myeasyui.mydatagrid.channelPayConfGrid", {
        init: function() {
            this._super("_init");
        },
        'addFun.toggleClass': function (options) {
            return function (opts) {
                var selectRow = $('#jq_datagrid_channel').datagrid('getSelected');
                var channelId = 0;
                if (selectRow) {
                    channelId = selectRow.id;
                }
                if (!channelId) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '请选择渠道所在的行!',
                        icon: 'warning',
                        time: 3
                    });
                    return;
                }
                var dialog = util.createWindow({
                    title : '添加' + (opts.dialogTitle || ''),
                    href : opts.forwardAddUrl + '?channel_id=' + channelId,
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
                var selectRow = $('#jq_datagrid_channelPayConf').datagrid('getSelected');
                var channelPaymentId = 0;
                if (selectRow) {
                    channelPaymentId = selectRow.id;
                }
                if (!channelPaymentId) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '请选择渠道所在的行!',
                        icon: 'warning',
                        time: 3
                    });
                    return;
                }
                var dialog = util.createWindow({
                    title : '添加' + (opts.dialogTitle || ''),
                    href : opts.forwardEditUrl + '?channel_payment_id=' + channelPaymentId,
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
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.channelPayConfGrid.defaults = {
        dialogTitle: '渠道支付类型',
        dialogWidth : 780,
        dialogHeight : 200,
        @permission('channel.payment.add')
        showAddButton: true,
        forwardAddUrl: '/channel/payment/add',
        @endpermission
        @permission('channel.payment.edit')
        forwardEditUrl: '/channel/payment/edit',
        showEditButton: true,
        @endpermission
        @permission('channel.payment.activate')
        showActivateButton : true,
        forwardActivateUrl : '/channel/payment/activate',
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
                width: '20%',
                title: '支付类型',
                field: 'payment',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '20%',
                title: '结算费率(‰)',
                field: 'billing_rate',
                formatter: function(value, row, index) {
                    return value * 1000 + '‰';
                }
            },
            {
                width: '20%',
                title: '费率类型',
                field: 'rate_type'
            },
            {
                width: '20%',
                title: '经营商品种类',
                field: 'product_type'
            },
            {
                width: '20%',
                title: '激活状态',
                field: 'activate_status'
            }
        ]]
    };

    $(this)['channelPayConfGrid']({className: 'channelPayConf'});
    $.data(this, 'channelPayConfGrid').init();
});
</script>

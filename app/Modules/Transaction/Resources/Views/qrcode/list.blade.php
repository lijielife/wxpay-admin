<div data-options="region:'center',fit:false,border:false" style="height:100%;">
    <div class="easyui-layout" data-options="fit:true,border:false">
        <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
            <!-- 选项卡start -->
            <div class="tab_wrap">
                <!-- 选项卡内容start -->
                <div class="tab_con">
                    <!-- 这里是每个选项卡详细内容 -->
                    <form id="jq_search_form_department" action="/qrcode/batch/datagrid.json" method="post">
                    {!! csrf_field() !!}
                    <!-- 这里是每个选项卡详细内容 -->
                    <div class="pay_box1">
                            <div class="fl w_100">渠道：</div>
                            <div class="fl w_150"><input type="text" id="channel_id" name="channel_id" placeholder="精确查询"/></div>
                            <ul class="pay_btns pb">
                                <li><span  id="jq_search_btn_qrcode" class="pay_btn btn_blue">查询</span></li>
                            </ul>
                            <div class="clear"></div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
        <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
            <div class="easyui-layout" data-options="fit:true,border:false">
                <div data-options="region:'center',split:false,border:false,split:false" style="height:140px; overflow: hidden;">
                    <table id="jq_datagrid_qrcode" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
                <div id="layout_qrcode_pay_conf" data-options="region:'east',title:'二维码列表',border:true,split:true,headerCls:'left-panel-header'" style="width:45%;border-color: #dddddd;">
                    <table id="jq_datagrid_qrcodePayConf" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    var channelIdTextbox = $('#channel_id').extTextbox({
        prompt: '请选择所属渠道',
        url : '/channel/datagrid.json',
        idField : 'id',
        showField : 'name',
        searchFields : [
            { label : '渠道名称', name : 'name' },
            { label : '渠道ID', name : 'id' }
        ],
        method: 'get',
        columns : [ [
            {title:'渠道ID', field:'id', width:100},
            {title:'渠道名称', field:'name', width:100},
            {title:'渠道类型', field:'type', width:100},
            {title:'上级渠道', field:'parent.name', width:100}
        ] ]
    });

    var qrcodeFuncTree;

    $.base("base.myeasyui.mydatagrid.qrcodeGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.qrcodeGrid.defaults = {
        dialogTitle: '商户列表',
        dialogWidth: 340,
        dialogHeight: 340,
        @permission('qrcode.batch.add')
        showAddButton: true,
        forwardAddUrl: '/qrcode/add',
        @endpermission
        url: '/qrcode/batch/datagrid.json',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        onClickRow : function (index, row) {
            var gridOptions = $('#jq_datagrid_qrcodePayConf').datagrid('options');
            gridOptions.url = '/qrcode/datagrid.json';
            $('#jq_datagrid_qrcodePayConf').datagrid('load', {batch_id : row.id, _token: '{{ csrf_token() }}'});

            var panelEast = $('#layout_qrcode_pay_conf').panel();
            panelEast.panel('setTitle', '渠道【' + row.channel.name + '】的固定二维码');
        },
        columns: [[
            {
                width: '10%',
                title: '批次ID',
                field: 'id'
            },
            {
                width: '25%',
                title: '所属渠道',
                field: 'channel',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '15%',
                title: '操作员',
                field: 'operator',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '15%',
                title: '数量',
                field: 'num'
            },
            {
                width: '20%',
                title: '备注',
                field: 'remark'
            },
            {
                width: '18%',
                title: '创建日期',
                field: 'created_at'
            }
        ]]
    };

    $(this)['qrcodeGrid']({className: 'qrcode'});
    $.data(this, 'qrcodeGrid').init();

    //------------------------------------------------------------------------------------//

    $.base("base.myeasyui.mydatagrid.qrcodePayConfGrid", {
        init: function() {
            this._super("_init");
        },
        'extToolbar.toggleClass' : function (options) {
            var self = this;
            options = $.extend({}, this.options, options);
            var buttons = [];
            buttons.push({text : '收款码', iconCls : 'icon-qrcode', size : 'large', handler : function(){self.showDetail.call(self, options);}});
            buttons.push({id : 'jq_' + options.className + '_unBind_btn',text : '解绑', iconCls : 'icon-unbind', size : 'large', handler : function(){self.unBind.call(self, options);}});
            buttons.push({id : 'jq_' + options.className + '_export_btn',text : '导出', iconCls : 'icon-redo', size : 'large', html:'dddd',handler : function(){self.exportQrInfo.call(self, options);}});
            return buttons;
        },
        exportQrInfo : function (options) {
            options = $.extend({}, this.options, options);
            var qrBatchGrid = $('#jq_datagrid_qrcode');
            var selectRow = qrBatchGrid.datagrid('getSelected');
            if(!selectRow){
                winTop.$.dialog({
                    title: '消息',
                    width: 150,
                    content: '请选择批次!',
                    icon: 'warning',
                    time: 3
                });
                return false;
            }
             var id = selectRow[qrBatchGrid.datagrid('options').entityPk];
             location.href='/qrcode/export?batch_id=' + id;
        },
        unBind : function (options) {
            options = $.extend({}, this.options, options);
            var selectRow = this.grid.datagrid('getSelected');
             if (selectRow) {
                console.log(selectRow.status);
                if(selectRow.status == '未绑定'){
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '已绑定的二维码才能解绑！',
                        icon: 'warning',
                        time: 3
                    });
                    return;
                 }
                 var id = selectRow[options.entityPk];
                 util.createWindow($.extend({
                     title : '解绑',
                     href : '/qrcode/unbind' + '?' + options.entityPk + '=' + id,
                     width : 200,
                     height : 100,
                     resizable : options.dialogResizable,
                     button : [ {
                         name : '解绑',
                         focus : true,
                         callback : function() {
                             util.createWindow.openner_dataGrid = $('#jq_datagrid_qrcodePayConf').datagrid();
                             var addForm = util.createWindow['jq_unbind_form_qrcode'];
                             addForm.submitForm();
                             $('#jq_datagrid_qrcodePayConf').datagrid('reload');
                             return false;
                        }
                    } ]
                 }, options));
             } else {
                util.dialog({
                    title: '消息',
                    width: 150,
                    content: '请选择行!',
                    icon: 'warning',
                    time: 3
                });
            }

        },
        showDetail : function (options) {
            options = $.extend({}, this.options, options);
            var selectRow = this.grid.datagrid('getSelected');
             if (selectRow) {
                 var id = selectRow[options.entityPk];
                 util.createWindow($.extend({
                     title : '收款码' + (options.dialogTitle || ''),
                     content : '<img src="/download?path=/storage/qrcode/'+selectRow.batch_id + '/' + selectRow.serial_no + '.png" width="340" height="340"/>',
                     width : options.dialogWidth,
                     height : options.dialogHeight,
                     resizable : options.dialogResizable,
                     button : []
                 }, options));
             } else {
                winTop.$.dialog({
                    title: '消息',
                    width: 150,
                    content: '请选择行!',
                    icon: 'warning',
                    time: 3
                });
            }
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.qrcodePayConfGrid.defaults = {
        dialogTitle: '二维码列表',
        dialogWidth : 350,
        dialogHeight : 360,
        editDialogHeight: 300,
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
                title: '收款标识',
                field: 'serial_no'
            },
            {
                width: '25%',
                title: '绑定商户',
                field: 'merchant',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '15%',
                title: '绑定收银员',
                field: 'cashier',
                formatter: function(value, row, index) {
                    return value ? value.name : '';
                }
            },
            {
                width: '18%',
                title: '绑定时间',
                field: 'binded_at'
            },
            {
                width: '15%',
                title: '绑定状态',
                field: 'status'
            }
        ]]
    };

    $(this)['qrcodePayConfGrid']({className: 'qrcodePayConf'});
    $.data(this, 'qrcodePayConfGrid').init();

    $('#jq_qrcodePayConf_export_btn').tooltip({
        position: 'right',
        content: '<span style="color:#fff">提示：仅支持导出未绑定的收款码</span>',
        onShow: function(){
            $(this).tooltip('tip').css({
                backgroundColor: '#666',
                borderColor: '#666'
            });
        }
    });
});
</script>

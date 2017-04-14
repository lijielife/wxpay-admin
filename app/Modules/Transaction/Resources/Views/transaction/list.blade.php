<div data-options="region:'center',fit:false,border:false" style="height:100%;">
    <div class="easyui-layout" data-options="fit:true,border:false" id="orderLayout">
        <div data-options="region:'north',split:false,border:false,split:false" style="overflow: hidden;">
            <!-- 选项卡start -->
            <div class="tab_wrap" style="margin-bottom: 0;">
                <!-- 选项卡内容start -->
                <div class="tab_con">
                    <form id="jq_search_form_order" action="/transaction/datagrid.json" method="post">
                    <!-- 这里是每个选项卡详细内容 -->
                    <div class="pay_box1">
                        <div class="fl w_100">交易时间：</div>
                        <div class="fl time_box"><input type="text" id="startTime" name="startTime" /></div>
                        <div class="fl w_34">至</div>
                        <div class="fl time_box"><input type="text" id="endTime" name="endTime" name="startTime" /></div>
                        <ul class="pay_btns pb">
                            <li><span class="pay_btn btn_white jq_time_box_control previous">前一天</span></li>
                            <li><span class="pay_btn btn_white jq_time_box_control next">后一天</span></li>
                        </ul>
                        <div class="clear"></div>
                    </div>

                    <div class="pay_box2">
                        <div class="fl w_100">所属渠道：</div>
                        <div class="fl w_150">
                            <input type="text" id="channel_id" name="channel_id" class="inputxt" />
                        </div>
                        <div class="fl w_100">所属商户：</div>
                        <div class="fl w_150">
                               <input type="text" id="merchant_id" name="merchant_id" class="inputxt" />
                        </div>
                        <div class="fl w_100">支付类型：</div>
                        <div class="fl w_150">
                             <input type="text" id="payment_id" name="payment_id" boxValue="" boxText="" class="inputxt"/>
                        </div>
                        <div class="fl w_100">交易状态：</div>
                        <div class="fl w_150">
                            <span class="sel_box">
                                <select id="status" name="status" class="inputxt easyui-combobox" data-options="panelHeight:100,height:32,width:153">
                                  <option value="">--全部--</option>
                                  <option value="not">未支付</option>
                                  <option value="success" selected="selected">支付成功</option>
                                  <option value="fail">支付失败</option>
                               </select>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="pay_box2">
                        <div class="fl w_100">交易金额：</div>
                        <div class="fl w_100"  style="width:65px;">
                            <span class="int_sp"><input type="text" id="total_fee_start" name="total_fee_start" /></span>
                        </div>
                        <span style="float:left; color:#ccc;margin-top:3px;">&nbsp;—&nbsp;</span>
                        <div class="fl w_100" style="width:65px; ">
                            <span class="int_sp"><input type="text" id="total_fee_end" name="total_fee_end" /></span>
                        </div>
                        @role('super.admin')
                        <div class="fl w_100">操作员：</div>
                        <div class="fl w_150">
                            <input type="text" id="user_id" name="user_id" placeholder="精确查询"/>
                        </div>
                        @endrole
                        <div class="fl w_100">设备号：</div>
                         <div class="fl w_150">
                           <span class="int_sp"><input type="text" id="device_info" name="device_info" placeholder="模糊查询"/></span>
                        </div>
                        <div class="fl w_100">第三方商户号：</div>
                            <div class="fl w_150">
                            <span class="int_sp"><input type="text" id="out_trade_no" name="out_trade_no"  placeholder="精确查询"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="pay_box2">
                        <ul class="pay_btns pb" style="margin-left: 100px;">
                           <li><span id="jq_search_btn_order" class="pay_btn btn_blue">查询</span></li>
                        </ul>
                    </div>
                </form>
                </div>
                <!-- 私有云系统，去掉下面描述提示 -->
                <p style="padding: 10px 0 0 29px;color: #BD4848;clear:both;">只支持3天的流水交易查询，若需要查询更多交易信息，请到文件管理—文件下载中下载对账单。</p>
            </div>
        </div>
        <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px; height:350px">
        <table id="jq_datagrid_order" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px;"></table>
        </div>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    var date = new Date();
    $('#startTime').datetimebox({
        value: date.getFullYear() + '-' + (date.getMonth() + 1 ) + '-' + date.getDate() + ' ' + '00:00:00',
        height: 32,
        width: 185
    });
    $('#endTime').datetimebox({
        value: date.getFullYear() + '-' + (date.getMonth() + 1 ) + '-' + date.getDate() + ' ' + '23:59:59',
        height: 32,
        width: 185
    });

    $('.previous').click(function(){
        var date = new Date($('#startTime').datetimebox('getValue'));
        date.setDate(date.getDate() - 1);
        $('#startTime').datetimebox('setValue', date.getFullYear() + '-' + (date.getMonth() + 1 ) + '-' + date.getDate() + ' ' + '00:00:00');
        $('#endTime').datetimebox('setValue', date.getFullYear() + '-' + (date.getMonth() + 1 ) + '-' + date.getDate() + ' ' + '23:59:59');
    });
    $('.next').click(function(){
        var date = new Date($('#startTime').datetimebox('getValue'));
        date.setDate(date.getDate() + 1);
        $('#startTime').datetimebox('setValue', date.getFullYear() + '-' + (date.getMonth() + 1 ) + '-' + date.getDate() + ' ' + '00:00:00');
        $('#endTime').datetimebox('setValue', date.getFullYear() + '-' + (date.getMonth() + 1 ) + '-' + date.getDate() + ' ' + '23:59:59');
    });

    function addDate(days) {

        return ;
    }

    $('#payment_id').extTextbox({
        prompt: '请选择支付类型',
        url : '/payment/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '支付类型名称', name : 'name' }
        ],
        columns : [ [
            {title:'支付类型名称', field:'name', width:200},
            {title:'简称', field:'slug', width:200}
        ] ]
    });

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

    var parentIdTextbox = $('#merchant_id').extTextbox({
        prompt: '请选择所属商户',
        url : '/merchant/datagrid.json',
        idField : 'id',
        showField : 'name',
        searchFields : [
            { label : '商户名称', name : 'name' }
        ],
        method: 'get',
        columns : [ [
            {title:'商户ID', field:'id', width:100},
            {title:'商户名称', field:'name', width:100}
        ] ]
    });

    $('#user_id').extTextbox({
        prompt: '请选择操作员',
        url : '/rbac/user/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '编号', name : 'name' },
            { label : '邮箱', name : 'email' },
        ],
        columns : [ [
            {title:'编号', field:'name', width:200},
            {title:'邮箱', field:'email', width:200}
        ] ]
    });

    $.base("base.myeasyui.mydatagrid.orderGrid", {
        init : function() {
            this._super("_init");
            this['resetPagination']();
        },
        'resetPagination' : function(options) {
            var pager = this.grid.datagrid('getPager');
            var self = this;
            pager.pagination({
                displayMsg: '',
                layout:['prev','next'],
                onSelectPage:function(pageNumber, pageSize){
                    var loadOptions = self.grid.datagrid('options').queryParams;
                    loadOptions.total = $(this).pagination('options').total;
                    loadOptions.page = pageNumber;
                    loadOptions.pageNumber = pageNumber;
                    loadOptions.pageSize = pageSize;
                    $(this).pagination('options').page = pageNumber;
                    $(this).pagination('options').rows = pageSize;

                    self.grid.datagrid('load', loadOptions);

                    $(this).pagination('refresh',{
                        total: loadOptions.total,
                        pageNumber: pageNumber
                    });

                    $(this).pagination('loaded');
                }
            });
        },
        'search_fun' : function(options) {
            options = $.extend({}, this.options, options);
            var self = this;
            $('#' + options.searchBtnIdPrefix + options.className).bind('click', function() {
                var startTime = $('#startTime').datetimebox('getValue');
                var endTime = $('#endTime').datetimebox('getValue');
                if ($.trim(startTime) == '' ) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '请选择交易开始时间',
                        icon: 'warning',
                        time: 3
                    });
                    return false;
                }
                if ($.trim(endTime) == '' ) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '请选择交易结束时间',
                        icon: 'warning',
                        time: 3
                    });
                    return false;
                }
                var startTime = new Date(startTime).getTime();
                var endTime = new Date(endTime).getTime();
                if (endTime - startTime < 0) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '交易结束时间必须大于开始时间',
                        icon: 'warning',
                        time: 3
                    });
                    return false;
                }
                if (endTime - startTime > 3 * 24 * 60 * 60 * 1000) {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '交易时间间隔大于3天',
                        icon: 'warning',
                        time: 3
                    });
                    return false;
                }
                if (!options.autoLoadDataFlag) {
                    var gridOptions = self.grid.datagrid('options');
                    gridOptions.url = self.options.url;
                }
                self.grid.datagrid('load', util.serializeForm($('#' + options.searchFormIdPrefix + options.className)));
            });
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);


    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.orderGrid.defaults = {
        dialogTitle : '订单查询',
        dialogWidth : 550,
        dialogHeight : 400,
        showOrderDetailButton : true,
        url : '/transaction/datagrid.json',
        autoLoadDataFlag: false,
        hideJumpButton : true,
        onClickRow : function (rowIndex, rowData) {

        },
        onBeforeLoad : function (param) {
            var orgType = 0;

            var gridOpts = $(this).datagrid('options');
            var panel = $(this).datagrid("getPanel");
            var imgSrc = "/assets/images/no_data.png";
            if (orgType == 2 && gridOpts.url == null) {
                panel.find('.datagrid-body')
                    .html('<div class="no_data" style="width:100%; text-align:center;"><div class="tips_wrap"><img src="' + imgSrc + '" width="140" height="140"><p>请选择查询条件进行查询！</p></br><p>订单流水只能查询自己或下属商户流水信息，不支持多层级查询</p></div></div>');
            } else if (gridOpts.url == null) {
                panel.find('.datagrid-body')
                    .html('<div class="no_data" style="width:100%; text-align:center;"><div class="tips_wrap"><img src="' + imgSrc + '" width="140" height="140"><p>请选择查询条件进行查询！</p></div></div>');
            }
            return true;
        },
        entityPk : 'id',
        frozenColumns : [ [] ],
        columns : [ [
            {
                width : '10%',
                title : '交易时间',
                field : 'created_at'
            },
            {
                width : '20%',
                title : '商户订单号'+'</br>' +'平台订单号',
                field : 'out_trade_no',
                formatter:function(value,row,index){
                    return row.out_trade_no + '</br>' + (row.transaction_id ? row.transaction_id : ' - ');
                }
            },
            {
                width : '10%',
                title : '商户名称',
                field : 'merchant',
                formatter:function(value,row,index){
                    return value.name;
                }
            },
            {
                width : '6%',
                title : '支付类型',
                field : 'payment_id',
                formatter:function(value,row,index){
                    return value == 1 ? '微信支付' : '';
                }
            },
            {
                width : '6%',
                title : '支付方式',
                field : 'trade_type'
            },
            {
                width : '6%',
                title : '交易状态',
                field : 'result_code',
                formatter:function(value,row,index){
                    return value == 'SUCCESS' ? '<font color="green">支付成功</font>' : (!row.result_code && !row.return_code ? '未支付' : '<font color="red">支付失败</font>');
                }
            },
            {
                width : '100px',
                title : '交易金额',
                field : 'total_fee',
                align : 'right',
                formatter:function(value,row){
                    return  value / 100;
                }
            },
            {
                width : '100px',
                title : '币种',
                field : 'fee_type'
            }
        ] ]
    };

    $(this)['orderGrid']({className : 'order'});
    $.data(this, 'orderGrid').init();
});
</script>

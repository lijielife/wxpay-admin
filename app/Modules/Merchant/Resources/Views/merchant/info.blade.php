<div  data-options="region:'center',fit:false,border:false" style="padding: 0px 10px 20px 10px;">
        <!-- 基本信息 -->
    <div class="detailDov">
        <div class="d_Title" style='margin-top: 10px; height: 40px;line-height: 40px;background: #fafafa;border: 1px solid #eee;border-bottom: 0px;padding: 0 30px;text-align: left;color: #333;font-size: 14px;font-weight: bold;'>基本信息</div>
        <div class="detail_table basic_info" >
            <table class="table" cellspacing="0" cellpadding="0"  style="border: 1px solid #eee;border-top: 0px;">
                <tbody>
                    <tr>
                        <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">商户名称</span>{{ $merchant->name }}</td>
                        <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">商户简称</span>{{ $merchant->slug }}</td>
                    </tr>
                     <tr>
                        <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">商户类型</span>{{ $merchant->type }}</td>
                        <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">商户编号</span>{{ $merchant->serial_no }}</td>
                    </tr>
                     <tr>
                        <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">商户经营类型</span>{{ $merchant->product_type }}</td>
                        <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">行业类别</span>{{ $merchant->industry->name }}</td>
                    </tr>
                    <tr>
                        <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">所属渠道</span>{{ $merchant->channel->name }}</td>
                        <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">客服电话</span>{{ $merchant->service_tel }}</td>
                    </tr>
                    <tr>
                        <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">负责人手机</span>{{ $merchant->manager_mobile }}</td>
                        <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">邮箱</span>{{ $merchant->email }}</td>
                    </tr>
                    <tr>
                        <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">省份</span>{{ $merchant->provinceInfo->name }}</td>
                        <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">城市</span>{{ $merchant->cityInfo->name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d_Title" style='margin-top:10px;height: 40px;line-height: 40px;background: #fafafa;border: 1px solid #eee;padding: 0 30px;text-align: left;color: #333;font-size: 14px;font-weight: bold;'>支付通道信息</div>

    <div class="easyui-layout" data-options="fit:false,border:true" style="height:315px;border: 1px solid #eee;border-top:0px">
        <div id="body_layout_merchant_info" data-options="fit:true,border:false">
            <table id="jq_datagrid_merchantInfo" data-options="fit:true,border:false" style="width:100%; "></table>
        </div>
    </div>
</div>
<script type="text/javascript">
    require(['util', 'easyui.widget.extend'], function(util){
        $.base("base.myeasyui.mydatagrid.merchantInfoGrid", {
            init : function() {
                this._super("_init");
            }
        }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

        /**
         * 以下是具体的配置,可覆盖父类的配置
         */
        $.base_myeasyui_mydatagrid.merchantInfoGrid.defaults = {
            url : '/merchant/payment/datagrid.json?merchant_id={{ $merchant->id }}',
            entityPk : 'id',
            autoLoadDataFlag : true,  // 是否自动加载数据
            columns : [ [
                {
                    width : '12%',
                    align : 'center',
                    title : '支付类型',
                    field : 'payment',
                    formatter: function(value, row, index) {
                        return value ? value.name : '';
                    }
                },
                {
                    width : '10%',
                    align : 'center',
                    title : '结算费率（‰）',
                    field : 'billing_rate'
                },
                {
                    width : '10%',
                    align : 'center',
                    title : '单日限额',
                    field : 'daily_trading_limit'
                },
                {
                    width : '10%',
                    align : 'center',
                    title : '单笔最小限额',
                    field : 'single_min_limit'
                },
                {
                    width : '10%',
                    align : 'center',
                    title : '单笔最大限额',
                    field : 'single_max_limit'
                },
                {
                    width : '12%',
                    align : 'center',
                    title : '第三方商户号',
                    field : 'merchant_no'
                },
                {
                    width : '14%',
                    align : 'center',
                    title : '银行卡号',
                    field : 'account',
                    formatter: function (value, row, index) {
                        return value ? value[0].card_no : '';
                    }
                },
                {
                    width : '10%',
                    align : 'center',
                    title : '开户人',
                    field : 'account_holder',// Datagrid不能存在2个一样的field?
                    formatter: function (value, row, index) {
                        return row.account ? row.account[0].account_holder : '';
                    }
                },
                {
                    width : '12%',
                    align : 'center',
                    title : '开户银行',
                    field : 'bank_name',// Datagrid不能存在2个一样的field?
                    formatter: function (value, row, index) {
                        return row.account ? row.account[0].bank.name : '';
                    }
                }
            ] ]
        };

        $('#body_layout_merchant_info').layout({fit:true, border:false});
        $(this)['merchantInfoGrid']({className : 'merchantInfo'});
        $.data(this, 'merchantInfoGrid').init();
    });
</script>

<div  data-options="region:'center',fit:false,border:false" style="padding: 0px 10px 20px 10px;">
        <!-- 基本信息 -->
                <div class="detailDov">
                    <div class="d_Title" style='margin-top: 10px; height: 40px;line-height: 40px;background: #fafafa;border: 1px solid #eee;border-bottom: 0px;padding: 0 30px;text-align: left;color: #333;font-size: 14px;font-weight: bold;'>基本信息</div>
                    <div class="detail_table basic_info" >
                        <table class="table" cellspacing="0" cellpadding="0"  style="border: 1px solid #eee;border-top: 0px;">
                            <tbody>
                                <tr>
                                    <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">所属渠道</span>{{ $channel->parent ? $channel->parent->name : '' }}</td>
                                    <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">渠道名称</span>{{ $channel->name }}</td>
                                </tr>
                                 <tr>
                                    <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">省份</span>{{ $channel->provinceInfo->name }}</td>
                                    <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">城市</span>{{ $channel->cityInfo->name }}</td>
                                </tr>
                                 <tr>
                                    <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">地址</span>{{ $channel->address }}</td>
                                    <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">邮箱</span>{{ $channel->email }}</td>
                                </tr>
                                <tr>
                                    <td width="50%" style="margin-top:15px;text-align: left;border-top: 1px solid #eee;border-right: 1px solid #eee;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">负责人</span>{{ $channel->manager }}</td>
                                    <td style="border-top: 1px solid #eee;text-align: left;margin-top: 15px;"><span class="table_labesls" style="display: inline-block;padding-left: 20px;width: 155px;">电话</span>{{ $channel->tel }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


    <div class="d_Title" style='margin-top:10px;height: 40px;line-height: 40px;background: #fafafa;border: 1px solid #eee;padding: 0 30px;text-align: left;color: #333;font-size: 14px;font-weight: bold;'>支付通道信息</div>

    <div class="easyui-layout" data-options="fit:false,border:true" style="height:315px;border: 1px solid #eee;border-top:0px">
        <div id="body_layout_channel_info" data-options="fit:true,border:false" >
            <table id="jq_datagrid_channelInfo" data-options="fit:true,border:false" style="width:100%; "></table>
        </div>
    </div>
</div>
<script type="text/javascript">
    require(['util', 'easyui.widget.extend'], function(util){

        $.base("base.myeasyui.mydatagrid.channelInfoGrid", {
            init : function() {
                this._super("_init");
            }
        }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

        /**
         * 以下是具体的配置,可覆盖父类的配置
         */
        $.base_myeasyui_mydatagrid.channelInfoGrid.defaults = {
            showExportButton : false,
            url : '/channel/payment/datagrid.json?channel_id={{ $channel->id }}',
            entityPk : 'id',
            autoLoadDataFlag : true,  // 是否自动加载数据
            columns : [ [
                {
                    width : '16%',
                    align : 'center',
                    title : '支付类型',
                    field : 'payment',
                    formatter: function(value, row, index) {
                        return value ? value.name : '';
                    }
                },
                {
                    width : '12%',
                    align : 'center',
                    title : '结算费率（‰）',
                    field : 'billing_rate'
                },
                {
                    width : '12%',
                    align : 'center',
                    title : '费率类型',
                    field : 'rate_type'
                },
                {
                    width : '20%',
                    align : 'center',
                    title : '银行卡号',
                    field : 'card_no',
                    formatter: function (value, row, index) {
                        return '{{ $channel->account[0]->card_no }}';
                    }
                },
                {
                    width : '20%',
                    align : 'center',
                    title : '开户人',
                    field : 'account_holder',
                    formatter: function (value, row, index) {
                        return '{{ $channel->account[0]->account_holder }}';
                    }
                },
                {
                    width : '20%',
                    align : 'center',
                    title : '开户银行',
                    field : 'bank_name',
                    formatter: function (value, row, index) {
                        return '{{ $channel->account[0]->bank->name }}';
                    }
                }
            ] ]
        };
        $('#body_layout_channel_info').layout({fit:true, border:false});
        $(this)['channelInfoGrid']({className : 'channelInfo'});
        $.data(this, 'channelInfoGrid').init();
    });
</script>

<div class="validform business_info" style="padding: 0 10px;">
    <form id="jq_add_form_channelPayConf" action="/channel/payment/save" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $channelPayment->id }}"/>
    <input type="hidden" name="channel_id" value="{{ $channelPayment->channel_id }}"/>
        <div class="info_title">基础配置</div>
        <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">渠道：</td>
                <td style="width:205px;">
                    <input type="text" id="channelName" value="{{ $channelPayment->channel->name }}" class="inputxt" disabled="disabled"/>
                </td>
                <td style="width:20px;" class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">支付类型：</td>
                <td style="width:205px;">
                    <input type="text" id="payment_id" name="payment_id" class="inputxt" datatype="*" nullmsg="请选择支付类型" boxValue="{{ $channelPayment->payment->id }}" boxText="{{ $channelPayment->payment->name }}"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">费率类型：</td>
                <td>
                    <select id="rate_type" name="rate_type" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:100,height:32,editable: false,width:202,onSelect: function(rec){$('#rate_type').val(rec.value);}" datatype="*">
                       <option value="fixed">固定费率</option><option value="cost">成本费率</option>
                    </select>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">结算费率(‰)：</td>
                <td>
                    <input type="text" id="billing_rate" name="billing_rate" value="{{ $channelPayment->billing_rate*1000 }}" class="inputxt easyui-numberbox" data-options="precision:2,min:0,suffix:'‰',height:32" datatype="isDecimals2" nullmsg="请输入结算费率" errormsg="结算费率只能是数字，且小数点最多两位"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">商品经营类型：</td>
                <td>
                    <select id="product_type" name="product_type" class="inputxt easyui-combobox" style="width:202px;" datatype="*" nullmsg="请选择商品经营类型" data-options="panelHeight:100,height:32,editable: false,width:202,onSelect: function(rec){$('#product_type').val(rec.value);}" >
                       <option value="">------请选择------</option>
                       <option value="all">全部</option><option value="entity">实体</option><option value="virtual">虚拟</option>
                    </select>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;"></td>
                <td>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util) {
    util.createWindow['jq_add_form_channelPayConf'] = $('#jq_add_form_channelPayConf').Validform({confirmBeforeSubmit: true});

    $('#jq_add_form_channelPayConf input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    $('#rate_type').combobox('select', '{{ $channelPayment->attr('rate_type') }}');
    $('#product_type').combobox('select', '{{ $channelPayment->attr('product_type') }}');

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
});
</script>

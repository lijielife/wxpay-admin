<div class="validform business_info">
    <form id="jq_unbind_form_qrcode" action="/qrcode/unbind" method="post">
    <input type="hidden" name="id" value="{{ $qrcode->id }}" />
    <div class="info_title">解绑</div>
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:90px; text-align: right;">收款码编号：</td>
                <td style="width:205px;">
                    {{ $qrcode->serial_no }}
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">商户名称：</td>
                <td>
                    {{ empty($qrcode->merchant) ? '该二维码尚未绑定' : $qrcode->merchant->name }}
                </td>
            </tr>
            <tr>
                <td style="text-align: right;"></td>
                <td>
                    {{ empty($qrcode->merchant) ? '' : $qrcode->merchant->serial_no }}
                </td>
            </tr>
            <tr>
            <td style="text-align: center;color:red;" colspan="2">注：解绑后该二维码无法进行交易，您确定要解绑吗？</td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_unbind_form_qrcode'] = $('#jq_unbind_form_qrcode').Validform();
});
</script>

<div class="validform" style="width: 430px;">
    <form id="jq_add_form_bank" action="/bank/save" method="post">
        {!! csrf_field() !!}
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:99px; text-align: right;">银行名称：</td>
                <td>
                    <input type="text" id="name" name="name" class="inputxt" datatype="s4-24" nullmsg="请输入银行名称" errormsg="银行名称必须是4~24位"/>
                </td>
                <td class="need" style="width:110px;">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">代号：</td>
                <td>
                    <input type="text" id="slug" name="slug" class="inputxt" nullmsg="请输入代号"/>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">备注：</td>
                <td>
                    <input type="text" id="remark" name="remark" class="inputxt"/>
                </td>
                <td class="need"></td>
            </tr>
    </form>
</div>
<script type="text/javascript">
require(['util', 'valid_form', 'valid_form_datatype', 'easyui'], function(util){
    $('#jq_add_form_bank input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    util.createWindow['jq_add_form_bank'] = $('#jq_add_form_bank').Validform();
});
</script>

<div class="validform business_info" style="height: 540px; overflow-y: auto; padding: 0 10px;">
    <form id="jq_add_form_channel" action="/channel/save" method="post">
        {!! csrf_field() !!}
        <div class="info_title">基础资料</div>
        <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">所属渠道：</td>
                <td style="width:205px;">
                    <input type="text" id="jq_cms_channel_parent_channel_id" name="pid" class="inputxt"/>
                </td>
                <td style="width:20px;"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">渠道名称：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" class="inputxt" datatype="*1-32" nullmsg="请填写渠道名称" errormsg="渠道名称长度在1~32位之间！"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">省份：</td>
                <td>
                    <input type="text" id="province" name="province" class="inputxt easyui-combobox" style="width:202px;" datatype="*" nullmsg="请选择省份"
                        data-options="height:32,width:202,valueField:'id',textField:'name',
                                        url:'/region/cascade?level=0',
                                        method: 'get',
                                        editable: false,
                                        onSelect: function(rec){
                                            $('#province').val(rec.name);
                                            $('#city').combobox({
                                                url:'/region/cascade?level=1&pid='+rec.id,
                                                method: 'get'
                                            })}" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">城市：</td>
                <td>
                    <input type="text" id="city" name="city" class="inputxt easyui-combobox" style="width:202px;" datatype="*" nullmsg="请选择城市"
                        data-options="height:32,width:202,editable: false,valueField:'id',textField:'name',onSelect: function(rec){
                                                                        $('#city').val(rec.name);
                                                                    }" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">地址：</td>
                <td>
                    <input type="text" id="address" name="address" class="inputxt" datatype="*" nullmsg="请输入地址"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">邮箱：</td>
                <td>
                    <input type="text" id="email" name="email" class="inputxt" datatype="*" nullmsg="请输入邮箱"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">负责人：</td>
                <td>
                    <input type="text" id="manager" name="manager" class="inputxt" datatype="*" nullmsg="请输入负责人"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">电话：</td>
                <td>
                    <input type="text" id="tel" name="tel" class="inputxt" datatype="*" nullmsg="请输入电话"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">备注：</td>
                <td>
                    <input type="text" id="remark" name="remark" class="inputxt"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">渠道类型：</td>
                <td>
                    <select id="type" name="type" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:100,editable: false,height:32,width:202,onSelect: function(rec){
                                                                            $('#type').val(rec.value);
                                                                        }" nullmsg="请选择渠道类型">
                       <option value="">------请选择------</option>
                       <option value="company">公司</option><option value="salesman">业务员</option>
                    </select>
                </td>
                <td class="need">*</td>
            </tr>

        </table>
        <div class="info_title" style="vertical-align: middle;">
            <label style="vertical-align: middle;">录入结算账户资料</label>
            <input id="jq_back_account_add_checkbox" type="checkbox" name="input_account_info_flag" value="true" style="vertical-align: middle;display: none"/>
        </div>
        <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">开户银行：</td>
                <td style="width:205px;">
                    <input type="text" id="jq_channel_bank_id" name="billing_account[bank_id]" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" nullmsg="请选择开户银行" />
                </td>
                <td style="width:20px;" class="need jq_back_account_input_class_need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">银行卡号：</td>
                <td style="width:205px;">
                    <input type="text" id="card_no" name="billing_account[card_no]" disabled="disabled" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" nullmsg="请输入银行卡号"/>
                </td>
                <td style="width:20px;" class="need jq_back_account_input_class_need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">开户人：</td>
                <td>
                    <input type="text" id="account_holder" name="billing_account[account_holder]" disabled="disabled" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" nullmsg="请输入开户人"/>
                </td>
                <td class="need jq_back_account_input_class_need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">帐户类型：</td>
                <td>
                    <select id="account_type" name="billing_account[account_type]" class="inputxt easyui-combobox jq_back_account_input_class jq_back_account_input_notnull_class" style="width:202px;" data-options="disabled:true,panelHeight:100,editable: false,height:32,width:202,onSelect: function(rec){
                                                                            $('#account_type').val(rec.value);
                                                                        }" nullmsg="请选择账户类型">
                       <option value="">------请选择------</option>
                       <option value="company">企业</option><option value="person">个人</option>
                    </select>
                </td>
                <td class="need jq_back_account_input_class_need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">开户支行名称：</td>
                <td>
                    <input type="text" id="branch_name" name="billing_account[branch_name]" disabled="disabled" class="inputxt jq_back_account_input_class"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">手机号码：</td>
                <td>
                    <input type="text" id="mobile" name="billing_account[mobile]" disabled="disabled" class="inputxt jq_back_account_input_class"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">证件类型：</td>
                <td>
                    <select id="cert_type" name="billing_account[cert_type]" class="inputxt easyui-combobox" style="width:202px;" data-options="disabled:true,panelHeight:100,editable: false,height:32,width:202,onSelect: function(rec){
                                                                            $('#cert_type').val(rec.value);
                                                                        }">
                       <option value="">------请选择------</option>
                       <option value="idcard">身份证</option><option value="passport">护照</option>
                    </select>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">证件号码：</td>
                <td>
                    <input type="text" id="cert_no" name="billing_account[cert_no]" disabled="disabled" class="inputxt jq_back_account_input_class"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">支行所在省：</td>
                <td>
                    <input type="text" id="backAccountProvince" name="billing_account[province]" class="inputxt easyui-combobox" style="width:202px;" nullmsg="请选择支行所在省份"
                        data-options="height:32,width:202,valueField:'id',textField:'name',
                                        url:'/region/cascade?level=0',
                                        method: 'get',
                                        disabled: true,
                                        editable: false,
                                        onSelect: function(rec){
                                            $('#backAccountProvince').val(rec.id);
                                            $('#backAccountCity').combobox({
                                                url:'/region/cascade?level=1&pid='+rec.id,
                                                disabled: false,
                                                method: 'get'
                                        })}" />
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">支行所在市：</td>
                <td>
                    <input type="text" id="backAccountCity" disabled="disabled" name="billing_account[city]" class="inputxt easyui-combobox" style="width:202px;" nullmsg="请选择支行所在市"
                        data-options="height:32,width:202,valueField:'id',textField:'name',
                                        editable: false,
                                        disabled: true,onSelect: function(rec){
                                                                    $('#backAccountCity').val(rec.id);
                                                                }" />
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>

    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_add_form_channel'] = $('#jq_add_form_channel').Validform({confirmBeforeSubmit: true});

    $('#jq_add_form_channel input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    var channelIdTextbox = $('#jq_cms_channel_parent_channel_id').extTextbox({
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

    var bankIdTextbox = $('#jq_channel_bank_id').extTextbox({
        prompt: '请选择开户银行',
        disabled: true,
        url : '/bank/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '银行名称', name : 'name' }
        ],
        columns : [ [
            {title:'银行名称', field:'name', width:200},
            {title:'银行英文缩写', field:'slug', width:200}
        ] ]
    });

    $('#jq_back_account_add_checkbox').click(function() {
        if ($(this).get(0).checked) {
            $('.jq_back_account_input_class').removeAttr('disabled');
            $('.jq_back_account_input_notnull_class').attr('datatype', '*');
            $('.jq_back_account_input_class_need').html('*');
            $('#account_type').combobox('enable');
            $('#cert_type').combobox('enable');
            bankIdTextbox.textbox('enable');
            $('#backAccountProvince').combobox('enable');
            $('#backAccountCity').combobox('enable');
        } else {
            $('.jq_back_account_input_class').removeAttr('datatype').attr('disabled', 'disabled').val('');
            $('.jq_back_account_input_class_need').html('');
            bankIdTextbox.textbox('disable');
            bankIdTextbox.textbox('clear');
            $('#account_type').combobox('disable');
            $('#account_type').combobox('clear');
            $('#cert_type').combobox('disable');
            $('#cert_type').combobox('clear');
            $('#backAccountProvince').combobox('disable');
            $('#backAccountProvince').combobox('clear');
            $('#backAccountCity').combobox('disable');
            $('#backAccountCity').combobox('clear');
        }
    }).click();
});
</script>

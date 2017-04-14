<div class="validform">
    <form id="jq_edit_form_cashier" action="/merchant/cashier/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $cashier->id }}">
        <input type="hidden" name="user_id" value="{{ $cashier->adminAccount->id }}">
        <table style="table-layout:fixed; width: 100%;">
            <!-- <tr>
                <td style="width:90px; text-align: right;">员工编号：</td>
                <td style="width:205px;">
                    <input type="text" id="serial_no" name="serial_no" value="{{ $cashier->serial_no }}" class="inputxt" datatype="s0-24"/>
                </td>
            </tr> -->
            <tr>
                <td style="width:90px; text-align: right;">员工姓名：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" class="inputxt" value="{{ $cashier->name }}" datatype="*1-32"  nullmsg="请填写员工姓名" errormsg="员工姓名长度在2~32位之间！"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>

            </tr>
            <tr>
                <td style="text-align: right;">员工性别：</td>
                <td>
                    <select id="sex" name="sex" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:50,height:32,width:202,onSelect: function(rec){
                                                                            $('#sex').val(rec.value);
                                                                        }" datatype="*" nullmsg="请选择性别">
                           <option value="male">男</option><option value="female">女</option>
                        </select>
                </td>
                </tr>
                <tr>
                <td style="text-align: right;">所属商户：</td>
                <td>
                    <input type="text" id="merchant_id" name="merchant_id" boxValue="{{ $cashier->merchant->id }}" boxText="{{ $cashier->merchant->name }}" class="inputxt" datatype="*" nullmsg="请选择所属商户" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">部门名称：</td>
                <td>
                    <input type="text" id="department_name" name="department_name" value="{{ $cashier->department_name }}" class="inputxt"  datatype="s0-64"/>
                </td>
                </tr>
                <tr>
                <td style="text-align: right;">员工职务：</td>
                <td>
                    <input type="text" id="duty" name="duty" value="{{ $cashier->duty }}" class="inputxt" datatype="s0-64"/>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">手机号码：</td>
                <td>
                    <input type="text" id="mobile" name="mobile" value="{{ $cashier->mobile }}" class="inputxt" datatype="n1-11" nullmsg="请输入手机号码" errormsg="手机号码长度在1~11位之间！"/>
                </td>
                 <td class="need">*</td>
                </tr>
            <tr>
                <td style="text-align: right;">邮箱：</td>
                <td>
                    <input type="text" id="email" name="email" value="{{ $cashier->email }}" class="inputxt" datatype="e" nullmsg="请输入邮箱"/>
                </td>
                 <td class="need">*</td>
                </tr>
                <tr>
                <td style="text-align: right;">身份证：</td>
                <td>
                    <input type="text" id="identity_card" name="identity_card" value="{{ $cashier->identity_card }}" class="inputxt" datatype="s0-128"/>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">是否启用：</td>
                <td>
                    <select id="enabled" name="enabled" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:80,height:32,width:202,onSelect: function(rec){
                                                                        $('#enabled').val(rec.value);
                                                                    }" datatype="*" nullmsg="请选择是否启用">
                       <option value="0">不启用</option><option value="1">启用</option>
                    </select>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                </tr>
                <tr>
                <td style="text-align: right;">备注：</td>
                <td>
                    <input type="text" id="remark" name="remark" value="{{ $cashier->remark }}" class="inputxt" datatype="s0-256"/>
                </td>
            </tr>
            <tr>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_edit_form_cashier'] = $('#jq_edit_form_cashier').Validform({confirmBeforeSubmit: true});
    $('#jq_edit_form_cashier input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    var merchantTextbox = $('#merchant_id').extTextbox({
        prompt: '请选择所属商户',
        url: '/merchant/datagrid.json?activate=success&type=normal|direct|chain',
        idField: 'id',
        showField: 'name',
        searchFields: [
            { label: '商户名称', name : 'name' }
        ],
        columns: [[
            {title:'商户名称', field:'name', width:200},
            {title:'商户类型', field:'type', width:200}
        ]]
    });

    $('#sex').combobox('select', '{{ $cashier->attr('sex', true) }}');
    $('#enabled').combobox('select', '{{ $cashier->attr('enabled', true) }}');
    merchantTextbox.textbox('disable');
});
</script>

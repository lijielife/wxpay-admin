<div class="validform">
    <form id="jq_add_form_cashier" action="/merchant/cashier/save" method="post">
        {!! csrf_field() !!}
        <table style="table-layout:fixed; width: 100%;">
            <!-- <tr>
                <td style="width:90px; text-align: right;">员工编号：</td>
                <td style="width:205px;">
                    <input type="text" id="serial_no" name="serial_no" class="inputxt" datatype="s0-24"/>
                </td>
            </tr> -->
            <tr>
                <td style="width:90px; text-align: right;">员工姓名：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" class="inputxt"  datatype="*1-32"  nullmsg="请填写员工姓名" errormsg="员工姓名长度在2~32位之间！"/>
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
                    <input type="text" id="merchant_id" name="merchant_id" class="inputxt" datatype="*" nullmsg="请选择所属商户" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">部门名称：</td>
                <td>
                    <input type="text" id="department_name" name="department_name" class="inputxt"  datatype="s0-64"/>
                </td>
                </tr>
                <tr>
                <td style="text-align: right;">员工职务：</td>
                <td>
                    <input type="text" id="duty" name="duty" class="inputxt" datatype="s0-64"/>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">手机号码：</td>
                <td>
                    <input type="text" id="mobile" name="mobile" class="inputxt" datatype="n1-11" nullmsg="请输入手机号码" errormsg="手机号码长度在1~11位之间！"/>
                </td>
                 <td class="need">*</td>
                </tr>
            <tr>
                <td style="text-align: right;">邮箱：</td>
                <td>
                    <input type="text" id="email" name="email" class="inputxt" datatype="e" nullmsg="请输入邮箱"/>
                </td>
                 <td class="need">*</td>
                </tr>
                <tr>
                <td style="text-align: right;">身份证：</td>
                <td>
                    <input type="text" id="identity_card" name="identity_card" class="inputxt" datatype="s0-128"/>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">是否启用：</td>
                <td>
                    <select id="enabled" name="enabled" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:80,height:32,width:202,onSelect: function(rec){
                                                                        $('#enabled').val(rec.value);
                                                                    }" datatype="*" nullmsg="请选择是否启用">
                       <option value="false">不启用</option><option value="true">启用</option>
                    </select>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                </tr>
                <tr>
                <td style="text-align: right;">备注：</td>
                <td>
                    <input type="text" id="remark" name="remark" class="inputxt" datatype="s0-256"/>
                </td>
            </tr>
            <tr>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_add_form_cashier'] = $('#jq_add_form_cashier').Validform({confirmBeforeSubmit: true});
    $('#jq_add_form_cashier input').focus(function() {
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
});
</script>

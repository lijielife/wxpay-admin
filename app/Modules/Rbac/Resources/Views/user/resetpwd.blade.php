<div class="validform">
    <form id="jq_user_pwd_reset_form" action="/rbac/user/resetpwd" method="post">
        {!! csrf_field() !!}
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:80px; text-align: right;">旧密码：</td>
                <td style="width:205px;">
                    <input type="password" id="password" name="password" class="inputxt" datatype="*" nullmsg="请输入旧密码！"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">新密码：</td>
                <td>
                    <input type="password" id="new_password" name="new_password" class="inputxt" datatype="s8-18" nullmsg="请输入新密码！" errormsg="密码必须是8~18位"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">确认密码：</td>
                <td>
                    <input type="password" id="repeat_password" name="repeat_password" class="inputxt" datatype="s8-18" errormsg="您两次输入的账号新密码不一致！" nullmsg="请再输入一次新密码！" recheck="new_password" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>
        <input id="userName" type="hidden" value="xiaofang2016"/>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_user_pwd_reset_form'] = $('#jq_user_pwd_reset_form').Validform();
    $('#jq_user_pwd_reset_form input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });
});
</script>

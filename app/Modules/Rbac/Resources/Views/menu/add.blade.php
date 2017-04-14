<div class="validform">
    <form id="jq_add_form_menu" action="/rbac/menu/save" method="post">
        {!! csrf_field() !!}
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:90px; text-align: right;">父级菜单：</td>
                <td>
                    <select id="pid" name="pid" class="inputxt easyui-combobox" data-options="panelHeight:150,height:32,width:202,onSelect: function(rec){$('#pid').val(rec.value);}">
                        <option value="0">顶级菜单</option>
                        @foreach($menus as $menu)
                        <option value="{{ $menu['id'] }}">{{ $menu['html'].$menu['name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="need" style="width:43px;">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">菜单名：</td>
                <td>
                    <input type="text" id="name" name="name" class="inputxt" datatype="*" nullmsg="请输入菜单名称"/>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">图标类：</td>
                <td>
                    <input type="text" id="icon" name="icon" class="inputxt"/>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">状态：</td>
                <td>
                    <select id="state" name="state" class="inputxt easyui-combobox" data-options="panelHeight:48,height:32,width:202,onSelect: function(rec){$('#state').val(rec.value);}">
                        <option value="closed">收缩</option>
                        <option value="open">展开</option>
                    </select>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">链接：</td>
                <td>
                    <input type="text" id="url" name="url" class="inputxt" datatype="*" ignore="ignore" nullmsg="请输入菜单链接"/>
                </td>
                <td class="need">*</td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'valid_form', 'valid_form_datatype'], function(util){
    util.createWindow['jq_add_form_menu'] = $('#jq_add_form_menu').Validform();
    $('#jq_add_form_menu input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });
});
</script>

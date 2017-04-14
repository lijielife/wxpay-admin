<div class="validform">
    <form id="jq_edit_form_permission" action="/rbac/permission/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $permission->id }}">
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:90px; text-align: right;">父级菜单：</td>
                <td>
                    <select id="mid" name="mid" class="inputxt easyui-combobox" data-options="panelHeight:150,height:32,width:202,onSelect: function(rec){$('#pid').val(rec.value);}">
                        <option value="0">顶级菜单</option>
                        @foreach($menus as $m)
                        <option value="{{ $m['id'] }}" @if($mid == $m['id']) selected @endif>{{ $m['html'].$m['name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="need" style="width:43px;">*</td>
            </tr>
            <tr>
                <td style="width:90px; text-align: right;">权限名：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" class="inputxt" value="{{ $permission->name }}" datatype="s4-24" nullmsg="请输入权限名" errormsg="权限名必须是4~24位"/>
                </td>
                <td class="need" style="width:43px;">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">别名：</td>
                <td>
                    <input type="text" id="slug" name="slug" class="inputxt" value="{{ $permission->slug }}" datatype="s4-24" nullmsg="请输入别名"  errormsg="别名必须是4~24位"/>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">描述：</td>
                <td>
                    <input type="text" id="description" name="description" class="inputxt" value="{{ $permission->description }}"/>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">模型：</td>
                <td>
                    <input type="text" id="model" name="model" class="inputxt" placeholder="模型命名空间" value="{{ $permission->model }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'valid_form', 'valid_form_datatype'], function(util){
    util.createWindow['jq_edit_form_permission'] = $('#jq_edit_form_permission').Validform();
    $('#jq_edit_form_permission input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });
});
</script>

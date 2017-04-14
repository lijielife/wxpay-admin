<div class="validform">
    <form id="jq_edit_form_department" action="/merchant/department/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $department->id }}">
        <table style="table-layout:fixed; ">
            <tr>
                <td style="text-align: right;">部门名称：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" value="{{ $department->name }}" class="inputxt" style="width:196px;" datatype="*1-64"  nullmsg="请输入部门名称" errormsg="部门名称长度在4~50位之间！"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">所属大商户：</td>
                    <td style="width:180px;">
                    <input type="text" id="merchant_id" name="merchant_id" boxValue="{{ $department->merchant->id }}" boxText="{{ $department->merchant->name }}" class="inputxt" datatype="*" nullmsg="请选择所属大商户"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">父部门：</td>
                <td>
                    <select id="jq_department_combotree" name="pid" class="easyui-combotree"/></select>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_edit_form_department'] = $('#jq_edit_form_department').Validform({confirmBeforeSubmit: true});
    $('#jq_edit_form_department input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    var parentMerchantTextbox = $('#merchant_id').extTextbox({
        prompt: '请选择所属大商户',
        url: '/merchant/datagrid.json?type=heavy',
        idField: 'id',
        showField: 'name',
        onSelected: function(row) {
            var $combotree = $('#jq_department_combotree');
            $combotree.combotree('setValue', '');
            $combotree.combotree('reload', '/merchant/department.json?merchant_id=' + row.id);
        },
        searchFields: [
            { label : '大商户名称', name : 'name' }
        ],
        columns: [ [
            {title:'大商户ID', field:'id', width:200},
            {title:'大商户名称', field:'name', width:200}
        ] ]
    });


    $('#jq_department_combotree').combotree({
        url: '/merchant/department.json?merchant_id={{ $department->merchant_id }}&dept_id={{ $department->id }}',
        parentField : 'pid',
        //required : true,
        method: 'get',
        height: 32,
        width: 202,
        value: '{{ !$department->pid ? '---请选择上级部门---' : $department->pid }}',
        onChange : function (newValue, oldValue) {
            var $tree = $(this).combotree('tree');  // 获取树对象
            var node = $tree.tree('getSelected');       // 获取选择的节点
            if (node) {
                $('#pid').val(node.id);
            }
        },
        onLoadSuccess: function() {
            // $(this).combotree('setValue', );
        }
    });

});
</script>

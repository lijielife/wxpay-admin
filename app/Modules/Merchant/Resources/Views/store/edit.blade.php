<div class="validform">
    <form id="jq_edit_form_store" action="/merchant/store/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $merchant->id }}">
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:80px; text-align: right;">所属大商户：</td>
                <td style="width:205px;">
                    <input type="text" id="pid" name="pid" boxValue="{{ $merchant->pid }}" boxText="{{ $merchant->parent ? $merchant->parent->name:'' }}" class="inputxt" datatype="*" nullmsg="请选择大商户"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">部门：</td>
                <td>
                    <input id="jq_department_combotree" name="department_id" class="easyui-combotree"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">门店名称：</td>
                <td>
                    <input type="text" id="name" name="name" value="{{ $merchant->name }}" class="inputxt" datatype="*4-50" nullmsg="请输入门店名称" errormsg="商户名称长度在4~50位之间！"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">门店类型：</td>
                <td>
                    <select id="type" name="type" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:50,height:32,width:202" datatype="*" nullmsg="请选择门店类型">
                       <option value="direct">直营商户</option><option value="chain">加盟商户</option>
                    </select>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>
        <p id="merchantTypeDesc" class="acp-tips" />
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_edit_form_store'] = $('#jq_edit_form_store').Validform();
    $('#jq_edit_form_store input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    var parentMerchantTextbox = $('#pid').extTextbox({
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
        parentField : 'pid',
        //required : true,
        method: 'get',
        height: 32,
        width: 202,
        value: '{{ $merchant->department_id }}',
        onChange : function (newValue, oldValue) {
            var $tree = $(this).combotree('tree');  // 获取树对象
            var node = $tree.tree('getSelected');       // 获取选择的节点
            if (node) {
                $('#pid').val(node.id);
            }
        }
    });

    $("#type").combobox({
        onSelect:function (rec) {
            $("#type").val(rec.value)
            updateMerchantTypeDesc()
        }
    });
    $('#type').combobox('select', '{{ $merchant->attr('type', true) }}');
    $('#jq_department_combotree').combotree('reload', '/merchant/department.json?merchant_id={{ $merchant->pid }}');

    updateMerchantTypeDesc();

    function updateMerchantTypeDesc() {
        /** 机构类型-商户类型：直营商户 */
        var ORG_TYPE_MCH_DIRECT = 'direct';
        /** 机构类型-商户类型：加盟商户 */
        var ORG_TYPE_MCH_LEAGUE = 'chain';
        var merchantTypeDesc = new Array();
        merchantTypeDesc[ORG_TYPE_MCH_DIRECT] = "直营门店指由总公司直接经营的连锁店";
        merchantTypeDesc[ORG_TYPE_MCH_LEAGUE] = "加盟门店指由总公司招募加盟主经营的连锁店";

        var selected = $("#type").val();

        var desc = merchantTypeDesc[selected];
        if (desc) {
            $("#merchantTypeDesc").text("温馨提示：" + desc);
        } else{
            $("#merchantTypeDesc").text("");
        }
    }
});
</script>

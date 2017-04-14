<div class="validform" style="width: 430px;">
    <form id="jq_add_form_industry" action="/industry/save" method="post">
        {!! csrf_field() !!}
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:99px; text-align: right;">行业名称：</td>
                <td>
                    <input type="text" id="name" name="name" class="inputxt" datatype="s2-24" nullmsg="请输入行业名称" errormsg="行业名称必须是2~24位"/>
                </td>
                <td class="need" style="width:110px;">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">所属行业：</td>
                <td>
                    <input type="text" id="pid" name="pid" class="inputxt"/>
                </td>
                <td class="need"></td>
            </tr>
            <tr>
                <td style="text-align: right;">经营类型：</td>
                <td>
                    <select id="product_type" name="product_type" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:100,height:32,editable: false,width:202,onSelect: function(rec){
                                                                                                    $('#product_type').val(rec.value);
                                                                                                }" datatype="*" nullmsg="请选择经营类型">
                                               <option value="">------请选择------</option>
                                               <option value="all">全部</option>
                                               <option value="entity">实体</option>
                                               <option value="virtual">虚拟</option>
                                            </select>
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
    $('#jq_add_form_industry input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    util.createWindow['jq_add_form_industry'] = $('#jq_add_form_industry').Validform();

    $('#pid').extTextbox({
        prompt: '请选择所属行业类别',
        url : '/industry/datagrid.json',
        idField : 'id',
        showField : 'name',
        searchFields : [
            { label : '行业名称', name : 'name' },
        ],
        method: 'get',
        columns : [ [
            {title:'行业名称', field:'name', width:100},
            {title:'上级行业', field:'parent', width:100, formatter: function(value, row, index) {
                    return value ? value.name : '';
            }},
        ] ]
    });
});
</script>

<div class="validform business_info" style="height: 300px; overflow-y: auto; padding: 0 10px;">
    <form id="jq_edit_form_region" action="/region/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $region->id }}">
        <div class="info_title">基础资料</div>
        <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">区域名称：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" value="{{ $region->name }}" class="inputxt" datatype="*" nullmsg="请输入区域名称"/>
                </td>
                <td class="need" style="width:20px;">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">区域简称：</td>
                <td style="width:205px;">
                    <input type="text" id="short_name" name="short_name" value="{{ $region->short_name }}" class="inputxt" datatype="*" nullmsg="请输入区域简称"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">上级区域：</td>
                <td>
                    <input type="text" id="pid" name="pid" class="inputxt" boxValue="{{ $region->pid }}" boxText="{{ $region->parent ? $region->parent->name:'' }}"/>
                </td>
                <td></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">邮编：</td>
                <td>
                    <input type="text" id="zip_code" name="zip_code" value="{{ $region->zip_code }}" class="inputxt" datatype="*" nullmsg="请填写邮编"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">城市编码：</td>
                <td>
                    <input type="text" id="city_code" name="city_code" value="{{ $region->city_code }}" class="inputxt" datatype="*" nullmsg="请填写城市编码"" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">区域编码：</td>
                <td>
                    <input type="text" id="area_code" name="area_code" value="{{ $region->area_code }}" class="inputxt" datatype="*" nullmsg="请填写区域编码" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">拼音：</td>
                <td>
                    <input type="text" id="pinyin" name="pinyin" value="{{ $region->pinyin }}" class="inputxt"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">经度：</td>
                <td>
                    <input type="text" id="lng" name="lng" value="{{ $region->lng }}" class="inputxt"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">纬度：</td>
                <td>
                    <input type="text" id="lat" name="lat" value="{{ $region->lat }}" class="inputxt"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_edit_form_region'] = $('#jq_edit_form_region').Validform({confirmBeforeSubmit: true});

    $('#jq_edit_form_region input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    var bankIdTextbox = $('#pid').extTextbox({
        prompt: '请选择上级区域',
        url : '/region/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '区域名称', name : 'name' }
        ],
        columns : [ [
            {title:'区域名称', field:'name', width:200},
            {title:'区域全称', field:'merge_name', width:200}
        ] ]
    });
});
</script>

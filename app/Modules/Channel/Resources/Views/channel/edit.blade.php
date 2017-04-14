<div class="validform business_info" style="height: 100%; overflow-y: auto; padding: 0 10px;">
    <form id="jq_edit_form_channel" action="/channel/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $channel->id }}">
        <div class="info_title">基础资料</div>
        <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">所属渠道：</td>
                <td style="width:205px;">
                    <input type="text" id="jq_cms_channel_parent_channel_id" name="pid" class="inputxt" boxValue="{{ $channel->pid }}" boxText="{{ $channel->parent ? $channel->parent->name:'' }}"/>
                </td>
                <td style="width:20px;"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">渠道名称：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" value="{{ $channel->name }}" class="inputxt" datatype="*1-32" nullmsg="请填写渠道名称" errormsg="渠道名称长度在1~32位之间！"/>
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
                                        onChange: function(rec){
                                            $('#province').val(rec);
                                            $('#city').combobox({
                                                url:'/region/cascade?level=1&pid='+rec,
                                                method: 'get',
                                                loadFilter: function(data) {
                                                    var cityValue = {{ $channel->city }};
                                                    $('#city').val(cityValue);
                                                    $.each(data, function() {
                                                        if (this.id == cityValue) {
                                                            this.selected = true;
                                                        }
                                                    });
                                                    return data;
                                                }
                                            })}" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">城市：</td>
                <td>
                    <input type="text" id="city" name="city" class="inputxt easyui-combobox" style="width:202px;" datatype="*" nullmsg="请选择城市"
                        data-options="height:32,width:202,editable: false,valueField:'id',textField:'name',onChange: function(rec){
                                                                        $('#city').val(rec);
                                                                    }" />
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">地址：</td>
                <td>
                    <input type="text" id="address" name="address" value="{{ $channel->address }}" class="inputxt" datatype="*" nullmsg="请输入地址"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">邮箱：</td>
                <td>
                    <input type="text" id="email" name="email" value="{{ $channel->email }}" class="inputxt" datatype="*" nullmsg="请输入邮箱"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">负责人：</td>
                <td>
                    <input type="text" id="manager" name="manager" value="{{ $channel->manager }}" class="inputxt" datatype="*" nullmsg="请输入负责人"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">电话：</td>
                <td>
                    <input type="text" id="tel" name="tel" value="{{ $channel->tel }}" class="inputxt" datatype="*" nullmsg="请输入电话"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">备注：</td>
                <td>
                    <input type="text" id="remark" name="remark" value="{{ $channel->remark }}" class="inputxt"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">渠道类型：</td>
                <td>
                    <select id="type" name="type" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:100,editable: false,height:32,width:202,
                            onChange: function(rec){
                                $('#type').val(rec.value);
                            }" nullmsg="请选择渠道类型">
                       <option value="">------请选择------</option>
                       <option value="company">公司</option><option value="salesman">业务员</option>
                    </select>
                </td>
                <td class="need">*</td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    util.createWindow['jq_edit_form_channel'] = $('#jq_edit_form_channel').Validform({confirmBeforeSubmit: true});

    $('#jq_edit_form_channel input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    $('#province').combobox('select', '{{ $channel->province }}');
    $('#type').combobox('select', '{{ $channel->attr('type', true) }}');

    var channelIdTextbox = $('#jq_cms_channel_parent_channel_id').extTextbox({
        prompt: '请选择所属渠道',
        url : '/channel/datagrid.json',
        idField : 'id',
        showField : 'name',
        readonly: true,
        disabled: true,
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
});
</script>

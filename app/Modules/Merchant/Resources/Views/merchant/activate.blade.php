<div class="validform business_info" style="height: 520px; overflow-y: auto; padding: 0 10px;">
    <div class="info_title">基础资料</div>
    <table style="table-layout:fixed; width: 100%;" cellspacing="10">
        <tr>
            <td style="width:100px; text-align: right;">商户类型：</td>
            <td style="width:200px;">
                <select id="type" name="type" class="inputxt" style="width:202px;" datatype="*" nullmsg="请选择商户类型">
                   <option value="">------请选择------</option>
                   <option value="heavy">大商户</option>
                   <option value="direct">直营店</option>
                   <option value="normal">普通商户</option>
                </select>
            </td>
            <td style="width:5px;" class="need">*</td>
            <td style="width:1px;"><div class="Validform_checktip"></div></td>
            <td style="width:100px; text-align: right;">所属渠道：</td>
            <td style="width:200px;">
                <input type="text" id="channel_id" name="channel_id" class="inputxt" boxValue="{{ $merchant->channel_id }}" boxText="{{ $merchant->channel ? $merchant->channel->name:'' }}" datatype="*" nullmsg="请选择所属渠道"/>
            </td>
            <td style="width:5px;" class="need">*</td>
            <td style="width:1px;"><div class="Validform_checktip"></div></td>
            <td style="width:100px; text-align: right;">所属大商户：</td>
            <td style="width:200px;">
                <input type="text" id="pid" name="pid" class="inputxt" boxValue="{{ $merchant->pid }}" boxText="{{ $merchant->parent ? $merchant->parent->name:'' }}" datatype="*" nullmsg="请选择所属大商户" />
            </td>
            <td style="width:5px;" class="need">*</td>
            <td style="width:1px;"><div class="Validform_checktip"></div></td>
        </tr>
        <tr>
            <td style="width:100px; text-align: right;">商户名称：</td>
            <td style="width:200px;">
                <input type="text" id="name" name="name" class="inputxt" value="{{ $merchant->name }}" datatype="*1-64" nullmsg="请填写商户名称" errormsg="商户名称长度在1~64位之间！"/>
            </td>
            <td style="width:5px;"  class="need">*</td>
            <td style="width:1px;"><div class="Validform_checktip"></div></td>
            <td style="width:100px; text-align: right;">商户简称：</td>
            <td style="width:200px;">
                <input type="text" id="slug" name="slug" class="inputxt" value="{{ $merchant->slug }}" datatype="*1-20" nullmsg="请填写商户简称" errormsg="商户简称长度在1~20位之间！"/>
            </td>
            <td style="width:5px;" class="need">*</td>
            <td>
                <span id="merchantShortNameHelpIcon" class="easyui-linkbutton easyui-tooltip" data-options="plain:true,iconCls:'icon-help'">
                </span>
            </td>
            <td style="width:100px; text-align: right;">经营类型：</td>
            <td style="width:200px;">
                <select id="product_type" name="product_type" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:100,height:32,editable: false,width:202,onSelect: function(rec){
                                                                        $('#product_type').val(rec.value);
                                                                    }" datatype="*" nullmsg="请选择商户经营类型">
                   <option value="">------请选择------</option>
                   <option value="all">全部</option>
                   <option value="entity">实体</option>
                   <option value="virtual">虚拟</option>
                </select>
            </td>
            <td style="width:5px;" class="need">*</td>
            <td style="width:1px;"><div class="Validform_checktip"></div></td>

        </tr>
        <tr>
            <td style="text-align: right;">行业类别：</td>
            <td>
                <input type="text" id="industry_id" name="industry_id" class="inputxt" boxValue="{{ $merchant->industry_id }}" boxText="{{ $merchant->industry ? $merchant->industry->name:'' }}" datatype="*" nullmsg="请选择行业类别"/>
            </td>
            <td class="need">*</td>
            <td><div class="Validform_checktip"></div></td>
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
                                            var cityValue = {{ $merchant->city }};
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
                <input type="text" id="address" name="address" value="{{ $merchant->address }}" class="inputxt"/>
            </td>
            <td class="need"></td>
            <td><div class="Validform_checktip"></div></td>
            <td style="text-align: right;">电话：</td>
            <td>
                <input type="text" id="tel" name="tel" value="{{ $merchant->tel }}" class="inputxt"/>
            </td>
            <td class="need"></td>
            <td><div class="Validform_checktip"></div></td>
            <td style="text-align: right;">邮箱：</td>
            <td>
                <input type="text" id="email" name="email" value="{{ $merchant->email }}" datatype="e" nullmsg="请输入邮箱" class="inputxt"/>
            </td>
            <td class="need">*</td>
            <td><div class="Validform_checktip"></div></td>
        </tr>
        <tr>
            <td style="text-align: right;">网址：</td>
            <td>
                <input type="text" id="website" name="website" value="{{ $merchant->website }}" class="inputxt"/>
            </td>
            <td class="need"></td>
            <td><div class="Validform_checktip"></div></td>
            <td style="text-align: right;">负责人：</td>
            <td>
                <input type="text" id="manager" name="manager" value="{{ $merchant->manager }}" class="inputxt"/>
            </td>
            <td class="need"></td>
            <td><div class="Validform_checktip"></div></td>
            <td style="text-align: right;">负责人身份证：</td>
            <td>
                <input type="text" id="identity_card" name="identity_card" value="{{ $merchant->identity_card }}" class="inputxt"/>
            </td>
            <td class="need"></td>
            <td><div class="Validform_checktip"></div></td>
        </tr>
        <tr>
            <td style="text-align: right;">负责人手机：</td>
            <td>
                <input type="text" id="manager_mobile" name="manager_mobile" value="{{ $merchant->manager_mobile }}" datatype="n11-11" nullmsg="请输入负责人手机" errormsg="手机号码格式不正确" class="inputxt"/>
            </td>
            <td class="need">*</td>
            <td><div class="Validform_checktip"></div></td>
            <td style="text-align: right;">客服电话：</td>
            <td>
                <input type="text" id="service_tel" name="service_tel" value="{{ $merchant->service_tel }}" datatype="n7-15" nullmsg="请输入客服电话" errormsg="客服电话格式不正确" class="inputxt"/>
            </td>
            <td class="need">*</td>
            <td><div class="Validform_checktip"></div></td>
            <td style="text-align: right;">传真：</td>
            <td>
                <input type="text" id="fax" name="fax" class="inputxt" value="{{ $merchant->fax }}"/>
            </td>
            <td class="need"></td>
            <td><div class="Validform_checktip"></div></td>
        </tr>
        <tr>
            <td style="width:100px; ext-align: right;">接口退款是否需要商户审核：</td>
            <td style="width:200px;">
                <select id="interface_refund_audit" name="interface_refund_audit" class="inputxt easyui-combobox" style="width:202px;" data-options="panelHeight:100,height:32,editable: false,width:202,onSelect: function(rec){
                                                                        $('#interface_refund_audit').val(rec.value);
                                                                    }" datatype="*" nullmsg="请选择是否接口退款审核">
                    <option value="0">否</option><option value="1">是</option>
                </select>
            </td>
            <td style="width:5px;" class="need">*</td>
            <td style="width:1px;"><div class="Validform_checktip"></div></td>
        </tr>
    </table>

    <input type="hidden" id="jq_upload_licensePhoto_click_hidden" name="business_licence_pic" value="{{ $merchant->business_licence_pic }}" datatype="*" nullmsg="请上传营业执照">
    <input type="hidden" id="jq_upload_orgPhoto_click_hidden" name="org_code_cert_pic" value="{{ $merchant->org_code_cert_pic }}" datatype="*" nullmsg="请上传组织机构代码证">
    <input type="hidden" id="jq_upload_idcard1_click_hidden" name="identity_card_pic[front]" value="{{ $merchant->identity_card_pic['front'] }}" datatype="*" nullmsg="请上传商户负责人身份证">
    <input type="hidden" id="jq_upload_idcard2_click_hidden" name="identity_card_pic[back]" value="{{ $merchant->identity_card_pic['back'] }}" datatype="*" nullmsg="请上传商户负责人身份证">
    <input type="hidden" id="jq_upload_protocolPhoto1_click_hidden" name="merchant_protocol_pic[]" value="{{ isset($merchant->merchant_protocol_pic[0])?$merchant->merchant_protocol_pic[0]:'' }}">
    <input type="hidden" id="jq_upload_protocolPhoto2_click_hidden" name="merchant_protocol_pic[]" value="{{ isset($merchant->merchant_protocol_pic[1])?$merchant->merchant_protocol_pic[1]:'' }}">
    <input type="hidden" id="jq_upload_protocolPhoto3_click_hidden" name="merchant_protocol_pic[]" value="{{ isset($merchant->merchant_protocol_pic[2])?$merchant->merchant_protocol_pic[2]:'' }}">
    <input type="hidden" id="jq_upload_protocolPhoto4_click_hidden" name="merchant_protocol_pic[]" value="{{ isset($merchant->merchant_protocol_pic[3])?$merchant->merchant_protocol_pic[3]:'' }}">

    <div class="info_upload">
        <div class="info_title">证件上传 &nbsp; &nbsp;<span class="acp-tips" id="uploadTip"></span></div>
        <div class="upload_list">
            <span class="upload_box">
                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_licensePhoto_img_div" title="营业执照">
                @if(!empty($merchant->business_licence_pic))
                <img src="/download?path={{ $merchant->business_licence_pic }}">
                @else
                营业执照
                @endif
                </div>
                <div class="upload_txt jq_upload_click_class" id="jq_upload_licensePhoto_click">+上传</div>
            </span>
            <span class="upload_box">
                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_indentityPhoto_img_div" title="商户负责人身份证">
                @if(!empty($merchant->identity_card_pic['front']))
                <img src="/download?path={{ $merchant->identity_card_pic['front'] }}">
                @else
                商户负责人身份证
                @endif
                </div>
                <div class="upload_txt" id="jq_upload_indentityPhoto_click">+上传</div>
            </span>
            <span class="upload_box">
                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_orgPhoto_img_div" title="组织机构代码证">
                @if(!empty($merchant->org_code_cert_pic))
                <img src="/download?path={{ $merchant->org_code_cert_pic }}">
                @else
                组织机构代码证
                @endif
                </div>
                <div class="upload_txt jq_upload_click_class" id="jq_upload_orgPhoto_click">+上传</div>
            </span>
            <span class="upload_box">
                <div class="upload_img jq_upload_protocol_img_class" id="jq_jq_upload_protocolPhoto_img_div" title="商户协议">
                @if(!empty($merchant->merchant_protocol_pic[0]))
                <img src="/download?path={{ $merchant->merchant_protocol_pic[0] }}">
                @else
                组织机构代码证
                @endif
                </div>
                <div class="upload_txt jq_upload_click_class1" id="jq_upload_protocolPhoto_click">+上传</div>
            </span>
        </div>
    </div>

    <div id="jq_upload_protocolPhoto_div" style="display: none; padding: 20px 20px 0 20px;">
  <div class="business_info">
    <div class="info_upload">
            <div class="info_title">商户协议上传</div>
            <div class="upload_list" id="jq_merchant_protocolPhoto_upload_list">
            <span class="upload_box">
                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto1_img_div" title="商户协议图一">
                    @if(isset($merchant->merchant_protocol_pic[0]))
                    <img src="/download?path={{$merchant->merchant_protocol_pic[0]}}">
                    @else
                    商户协议图一
                    @endif
                </div>
                <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto1_click">+上传</div>
                </span>
            <span class="upload_box">
                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto2_img_div" title="商户协议图二">
                    @if(isset($merchant->merchant_protocol_pic[1]))
                    <img src="/download?path={{$merchant->merchant_protocol_pic[1]}}">
                    @else
                    商户协议图二
                    @endif
                </div>
                <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto2_click">+上传</div>
            </span>
            <span class="upload_box">
                  <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto3_img_div" title="商户协议图三">
                    @if(isset($merchant->merchant_protocol_pic[2]))
                    <img src="/download?path={{$merchant->merchant_protocol_pic[2]}}">
                    @else
                    商户协议图三
                    @endif
                  </div>
                    <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto3_click">+上传</div>
            </span>
            <span class="upload_box">
                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto4_img_div" title="商户协议图四">
                    @if(isset($merchant->merchant_protocol_pic[3]))
                    <img src="/download?path={{$merchant->merchant_protocol_pic[3]}}">
                    @else
                    商户协议图四
                    @endif
                </div>
                <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto4_click">+上传</div>
            </span>
            </div>
        </div>
      </div>
    </div>

    <div id="jq_upload_indentityPhoto_div" style="display: none; padding: 20px 20px 0 20px;">
        <div class="business_info">
            <div class="info_upload">
                <div class="info_title">身份证上传</div>
                <div class="upload_list" id="jq_merchant_indentityPhoto_upload_list">
                    <span class="upload_box">
                        <div class="upload_img jq_upload_img_class" id="jq_jq_upload_idcard1_img_div" title="身份证正面">
                            @if(isset($merchant->identity_card_pic['front']))
                            <img src="/download?path={{$merchant->identity_card_pic['front']}}">
                            @else
                            身份证正面
                            @endif
                        </div>
                        <div class="upload_txt jq_upload_click_class" id="jq_upload_idcard1_click">+上传</div>
                    <input type="file" name="identity_card_pic[]" style="display:none;" />
                    </span>
                    <span class="upload_box">
                        <div class="upload_img jq_upload_img_class" id="jq_jq_upload_idcard2_img_div" title="身份证反面">
                            @if(isset($merchant->identity_card_pic['back']))
                            <img src="/download?path={{$merchant->identity_card_pic['back']}}">
                            @else
                            身份证反面
                            @endif
                        </div>
                        <div class="upload_txt jq_upload_click_class" id="jq_upload_idcard2_click">+上传</div>
                    <input type="file" name="identity_card_pic[]" style="display:none;" />
                    </span>
                </div>
            </div>
        </div>
    </div>
    <form id="jq_edit_form_merchant" action="/merchant/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $merchant->id }}">
        <input type="hidden" id="jq_edit_form_merchant_activateStatus" name="activate_status"/>
        <div style="vertical-align: middle;" class="info_title">
            <label style="vertical-align: middle;">激活</label>
        </div>
        <table cellspacing="10" style="table-layout:fixed; width: 100%;">
            <tbody>
                <tr>
                    <td valign="top" style="width:100px; text-align: right;">激活备注：</td>
                    <td colspan="7" style="width:100%;">
                        <textarea style="width:565px; height: 100px;" name="activate_remark"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['plupload', 'util', 'easyui.widget.extend', 'mutiplupload'], function(plupload, util){
    util.createWindow['jq_edit_form_merchant'] = $('#jq_edit_form_merchant').Validform({confirmBeforeSubmit: true});

    $('#jq_edit_form_merchant input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    $("#merchantShortNameHelpIcon").tooltip({
        position: 'bottom',
        content: '<span style="font-size: 12px;line-height: 1.5;color: #333;">该名称将在支付完成页面向消费者进行展示</span>',
        onShow: function(){
            $(this).tooltip('tip').css({
                backgroundColor: 'rgba(255,255,255,0.8)',
                borderColor: '#ccc'
            });
        }
    });

    var channelIdTextbox = $('#channel_id').extTextbox({
        prompt: '请选择所属渠道',
        url : '/channel/datagrid.json',
        idField : 'id',
        showField : 'name',
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
        ] ],
        onSelected: function(row) {
            if(row) {
                $('#pid').textbox('disable');
                $('#pid').removeAttr('datatype');
                $('#pid').removeAttr('nullmsg');
            }
        },
        clearBtnCallBack: function() {
            $('#pid').textbox('enable');
            $('#pid').attr('datatype', '*');
            $('#pid').attr('nullmsg', '请选择所属大商户');
        }
    });

    var parentIdTextbox = $('#pid').extTextbox({
        prompt: '请选择所属大商户',
        url : '/merchant/datagrid.json?type=heavy',
        idField : 'id',
        showField : 'name',
        searchFields : [
            { label : '大商户名称', name : 'name' }
        ],
        method: 'get',
        columns : [ [
            {title:'大商户ID', field:'id', width:100},
            {title:'大商户名称', field:'name', width:100}
        ] ],
        onSelected: function(row) {
            if(row) {
                $('#channel_id').textbox('disable');
                $('#channel_id').removeAttr('datatype');
                $('#channel_id').removeAttr('nullmsg');
            }
        },
        clearBtnCallBack: function() {
            $('#channel_id').textbox('enable');
            $('#channel_id').attr('datatype', '*');
            $('#channel_id').attr('nullmsg', '请选择所属渠道');
        }
    });

    $('#type').combobox({
        panelHeight: 100,
        editable: false,
        height: 32,
        width: 202,
        onSelect: function(rec){
            $('#type').val(rec.value);
            if (rec.value == 'heavy') {
                $('#pid').textbox('disable');
                $('#pid').removeAttr('datatype');
                $('#pid').removeAttr('nullmsg');
            } else if ((rec.value == 'direct' || rec.value == 'normal') && !$('#channel_id').val()){
                $('#pid').textbox('enable');
                $('#pid').attr('datatype', '*');
                $('#pid').attr('nullmsg', '请选择所属大商户');
            }
        }
    });

    var industryIdTextbox = $('#industry_id').extTextbox({
        prompt: '请选择行业类别',
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

    var bankIdTextbox = $('#jq_merchant_bank_id').extTextbox({
        prompt: '请选择开户银行',
        disabled: true,
        url : '/bank/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '银行名称', name : 'name' }
        ],
        columns : [ [
            {title:'银行名称', field:'name', width:200},
            {title:'银行英文缩写', field:'slug', width:200}
        ] ]
    });

    $('#jq_back_account_add_checkbox').click(function() {
        if ($(this).get(0).checked) {
            $('.jq_back_account_input_class').removeAttr('disabled');
            $('.jq_back_account_input_notnull_class').attr('datatype', '*');
            $('.jq_back_account_input_class_need').html('*');
            $('#account_type').combobox('enable');
            $('#cert_type').combobox('enable');
            bankIdTextbox.textbox('enable');
            $('#backAccountProvince').combobox('enable');
            $('#backAccountCity').combobox('enable');
        } else {
            $('.jq_back_account_input_class').removeAttr('datatype').attr('disabled', 'disabled').val('');
            $('.jq_back_account_input_class_need').html('');
            bankIdTextbox.textbox('disable');bankIdTextbox.textbox('disable');
            bankIdTextbox.textbox('clear');
            $('#account_type').combobox('disable');
            $('#account_type').combobox('clear');
            $('#cert_type').combobox('disable');
            $('#cert_type').combobox('clear');
            $('#backAccountProvince').combobox('disable');
            $('#backAccountProvince').combobox('clear');
            $('#backAccountCity').combobox('disable');
            $('#backAccountCity').combobox('clear');
        }
    }).click();

    $("#jq_upload_protocolPhoto_click").click("click", function() {
        $.dialog({
                padding: 0,
                title: "上传商户协议",
                content: document.getElementById("jq_upload_protocolPhoto_div"),
                opacity: .1,
                lock: !0,
                cache: !1,
                cancelVal: "确定",
                cancel: function() {},
                close: function() {}
        });
    });
    $("#jq_upload_indentityPhoto_click").on("click", function() {
        $.dialog({
            padding: 0,
            title: "上传负责人身份证",
            content: document.getElementById("jq_upload_indentityPhoto_div"),
            opacity: .1,
            lock: !0,
            cache: !1,
            cancelVal: "确定",
            cancel: function() {},
            close: function() {}
        });
    });

    $('#jq_upload_licensePhoto_click,#jq_upload_orgPhoto_click,#jq_upload_protocolPhoto1_click,#jq_upload_protocolPhoto2_click,#jq_upload_protocolPhoto3_click,#jq_upload_protocolPhoto4_click,#jq_upload_idcard1_click,#jq_upload_idcard2_click').mutiupload({
        url: '/upload?type=image',//这里有个坑，如果修改为'/upload/?type=image'，Laravel读取到的方法是GET，APATCH下存在,NGINX下没问题
        multipart_params: {
          _token: '{{ csrf_token() }}'
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'//pluoload默认不带X-Requested-With头，需要手动写上，否则laravel不认为是ajax请求
        },
        success: function(uploader, file, result, browseBtn) {
            $('#' + browseBtn.attr('id') + '_hidden').val(result.data);
        }
    });

    $('#type').combobox('select', '{{ $merchant->attr('type', true) }}');
    $('#product_type').combobox('select', '{{ $merchant->attr('product_type', true) }}');
    $('#province').combobox('select', '{{ $merchant->province }}');
    $('#interface_refund_audit').combobox('select', '{{ $merchant->interface_refund_audit }}');
});
</script>

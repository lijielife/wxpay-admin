<div class="validform business_info" style="height: 520px; overflow-y: auto; padding: 0 10px;">
    <form id="jq_add_form_merchant" action="/merchant/save" method="post">
        {!! csrf_field() !!}
        <div class="info_title">基础资料</div>
            <table style="table-layout:fixed; width: 100%;" cellspacing="10">
                <tr>
                    <td style="width:100px; text-align: right;">商户类型：</td>
                    <td style="width:200px;">
                        <select id="type" name="type" class="inputxt" style="width:202px;" datatype="*" nullmsg="请选择商户类型">
                           <option value="">------请选择------</option>
                           <option value="heavy">大商户</option>
                           <option value="normal">普通商户</option>
                        </select>
                    </td>
                    <td style="width:5px;" class="need">*</td>
                    <td style="width:1px;"><div class="Validform_checktip"></div></td>
                    <td style="width:100px; text-align: right;">所属渠道：</td>
                    <td style="width:200px;">
                        <input type="text" id="channel_id" name="channel_id" class="inputxt" datatype="*" nullmsg="请选择所属渠道"/>
                    </td>
                    <td style="width:5px;" class="need">*</td>
                    <td style="width:1px;"><div class="Validform_checktip"></div></td>
                    <td style="width:100px; text-align: right;">所属大商户：</td>
                    <td style="width:200px;">
                        <input type="text" id="pid" name="pid" class="inputxt" datatype="*" nullmsg="请选择所属大商户" />
                    </td>
                    <td style="width:5px;" class="need">*</td>
                    <td style="width:1px;"><div class="Validform_checktip"></div></td>
                </tr>
                <tr>
                    <td style="width:100px; text-align: right;">商户名称：</td>
                    <td style="width:200px;">
                        <input type="text" id="name" name="name" class="inputxt" datatype="*4-50" nullmsg="请填写商户名称" errormsg="商户名称长度在4~50位之间！"/>
                    </td>
                    <td style="width:5px;"  class="need">*</td>
                    <td style="width:1px;"><div class="Validform_checktip"></div></td>
                    <td style="width:100px; text-align: right;">商户简称：</td>
                    <td style="width:200px;">
                        <input type="text" id="slug" name="slug" class="inputxt" datatype="*1-20" nullmsg="请填写商户简称" errormsg="商户简称长度在1~20位之间！"/>
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
                        <input type="text" id="industry_id" name="industry_id" class="inputxt" datatype="*" nullmsg="请选择行业类别"/>
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
                                        onSelect: function(rec){
                                            $('#province').val(rec.name);
                                            $('#city').combobox({
                                                url:'/region/cascade?level=1&pid='+rec.id,
                                                method: 'get'
                                            })}" />
                    </td>
                    <td class="need">*</td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">城市：</td>
                    <td>
                        <input type="text" id="city" name="city" class="inputxt easyui-combobox" style="width:202px;" datatype="*" nullmsg="请选择城市"
                            data-options="height:32,width:202,editable: false,valueField:'id',textField:'name',onSelect: function(rec){
                                                                        $('#city').val(rec.name);
                                                                    }" />
                    </td>
                    <td class="need">*</td>
                    <td><div class="Validform_checktip"></div></td>
                </tr>
                <tr>

                    <td style="text-align: right;">地址：</td>
                    <td>
                        <input type="text" id="address" name="address" class="inputxt"/>
                    </td>
                    <td class="need"></td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">电话：</td>
                    <td>
                        <input type="text" id="tel" name="tel" class="inputxt"/>
                    </td>
                    <td class="need"></td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">邮箱：</td>
                    <td>
                        <input type="text" id="email" name="email" datatype="e" nullmsg="请输入邮箱" class="inputxt"/>
                    </td>
                    <td class="need">*</td>
                    <td><div class="Validform_checktip"></div></td>
                </tr>
                <tr>
                    <td style="text-align: right;">网址：</td>
                    <td>
                        <input type="text" id="website" name="website" class="inputxt"/>
                    </td>
                    <td class="need"></td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">负责人：</td>
                    <td>
                        <input type="text" id="manager" name="manager" class="inputxt"/>
                    </td>
                    <td class="need"></td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">负责人身份证：</td>
                    <td>
                        <input type="text" id="identity_card" name="identity_card" class="inputxt"/>
                    </td>
                    <td class="need"></td>
                    <td><div class="Validform_checktip"></div></td>
                </tr>
                <tr>
                    <td style="text-align: right;">负责人手机：</td>
                    <td>
                        <input type="text" id="manager_mobile" name="manager_mobile" datatype="n11-11" nullmsg="请输入负责人手机" errormsg="手机号码格式不正确" class="inputxt"/>
                    </td>
                    <td class="need">*</td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">客服电话：</td>
                    <td>
                        <input type="text" id="service_tel" name="service_tel" datatype="n7-15" nullmsg="请输入客服电话" errormsg="客服电话格式不正确" class="inputxt"/>
                    </td>
                    <td class="need">*</td>
                    <td><div class="Validform_checktip"></div></td>
                    <td style="text-align: right;">传真：</td>
                    <td>
                        <input type="text" id="fax" name="fax" class="inputxt"/>
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

            <input type="hidden" id="jq_upload_licensePhoto_click_hidden" name="business_licence_pic" datatype="*" nullmsg="请上传营业执照">
            <input type="hidden" id="jq_upload_orgPhoto_click_hidden" name="org_code_cert_pic" datatype="*" nullmsg="请上传组织机构代码证">
            <input type="hidden" id="jq_upload_idcard1_click_hidden" name="identity_card_pic[front]" datatype="*" nullmsg="请上传商户负责人身份证">
            <input type="hidden" id="jq_upload_idcard2_click_hidden" name="identity_card_pic[back]" datatype="*" nullmsg="请上传商户负责人身份证">
            <input type="hidden" id="jq_upload_protocolPhoto1_click_hidden" name="merchant_protocol_pic[]">
            <input type="hidden" id="jq_upload_protocolPhoto2_click_hidden" name="merchant_protocol_pic[]">
            <input type="hidden" id="jq_upload_protocolPhoto3_click_hidden" name="merchant_protocol_pic[]">
            <input type="hidden" id="jq_upload_protocolPhoto4_click_hidden" name="merchant_protocol_pic[]">

            <div class="info_upload">
                <div class="info_title">证件上传 &nbsp; &nbsp;<span class="acp-tips" id="uploadTip"></span></div>
                <div class="upload_list">
                    <span class="upload_box">
                        <div class="upload_img jq_upload_img_class" id="jq_jq_upload_licensePhoto_img_div" title="营业执照">营业执照</div>
                        <div class="upload_txt jq_upload_click_class" id="jq_upload_licensePhoto_click">+上传</div>
                    </span>
                    <span class="upload_box">
                        <div class="upload_img jq_upload_img_class" id="jq_jq_upload_indentityPhoto_img_div" title="商户负责人身份证">商户负责人身份证</div>
                        <div class="upload_txt" id="jq_upload_indentityPhoto_click">+上传</div>
                    </span>
                    <span class="upload_box">
                        <div class="upload_img jq_upload_img_class" id="jq_jq_upload_orgPhoto_img_div" title="组织机构代码证">组织机构代码证</div>
                        <div class="upload_txt jq_upload_click_class" id="jq_upload_orgPhoto_click">+上传</div>
                    </span>
                    <span class="upload_box">
                        <div class="upload_img jq_upload_protocol_img_class" id="jq_jq_upload_protocolPhoto_img_div" title="商户协议">商户协议</div>
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
                          <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto1_img_div" title="商户协议图一">商户协议图一</div>
                            <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto1_click">+上传</div>
                        </span>
                        <span class="upload_box">
                          <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto2_img_div" title="商户协议图二">商户协议图二</div>
                            <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto2_click">+上传</div>
                        </span>
                        <span class="upload_box">
                          <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto3_img_div" title="商户协议图三">商户协议图三</div>
                            <div class="upload_txt jq_upload_click_class" id="jq_upload_protocolPhoto3_click">+上传</div>
                        </span>
                        <span class="upload_box">
                          <div class="upload_img jq_upload_img_class" id="jq_jq_upload_protocolPhoto4_img_div" title="商户协议图四">商户协议图四</div>
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
                                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_idcard1_img_div" title="身份证正面">身份证正面</div>
                                <div class="upload_txt jq_upload_click_class" id="jq_upload_idcard1_click">+上传</div>
                            <input type="file" name="identity_card_pic[]" style="display:none;" />
                            </span>
                            <span class="upload_box">
                                <div class="upload_img jq_upload_img_class" id="jq_jq_upload_idcard2_img_div" title="身份证反面">身份证反面</div>
                                <div class="upload_txt jq_upload_click_class" id="jq_upload_idcard2_click">+上传</div>
                            <input type="file" name="identity_card_pic[]" style="display:none;" />
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info_title" style="vertical-align: middle;">
                <label style="vertical-align: middle;">录入结算账户资料</label>
                <input id="jq_back_account_add_checkbox" type="checkbox" name="input_account_info_flag" value="true" style="vertical-align: middle;"/>
            </div>
            <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">开户银行：</td>
                <td style="width:200px;">
                    <input type="text" id="jq_merchant_bank_id" name="billing_account[bank_id]" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" nullmsg="请选择开户银行" />
                </td>
                <td style="width:5px;" class="need jq_back_account_input_class_need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">银行卡号：</td>
                <td style="width:200px;">
                    <input type="text" id="card_no" name="billing_account[card_no]" disabled="disabled" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" nullmsg="请输入银行卡号"/>
                </td>
                <td style="width:5px;" class="need jq_back_account_input_class_need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">开户人：</td>
                <td style="width:200px;">
                    <input type="text" id="account_holder" name="billing_account[account_holder]" disabled="disabled" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" nullmsg="请输入开户人"/>
                </td>
                <td style="width:5px;"  class="need jq_back_account_input_class_need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">帐户类型：</td>
                <td>
                    <select id="account_type" name="billing_account[account_type]" class="inputxt easyui-combobox jq_back_account_input_class jq_back_account_input_notnull_class" style="width:202px;" data-options="disabled:true,panelHeight:100,editable: false,height:32,width:202,onSelect: function(rec){
                                                                            $('#account_type').val(rec.value);
                                                                        }" nullmsg="请选择账户类型">
                       <option value="">------请选择------</option>
                       <option value="company">企业</option><option value="person">个人</option>
                    </select>
                </td>
                <td class="need jq_back_account_input_class_need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">开户支行名称：</td>
                <td>
                    <input type="text" id="branch_name" name="billing_account[branch_name]" disabled="disabled" class="inputxt jq_back_account_input_class"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">手机号码：</td>
                <td>
                    <input type="text" id="mobile" name="billing_account[mobile]" disabled="disabled" class="inputxt jq_back_account_input_class"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">证件类型：</td>
                <td>
                    <select id="cert_type" name="billing_account[cert_type]" class="inputxt easyui-combobox" style="width:202px;" data-options="disabled:true,panelHeight:100,editable: false,height:32,width:202,onSelect: function(rec){
                                                                            $('#cert_type').val(rec.value);
                                                                        }">
                       <option value="">------请选择------</option>
                       <option value="idcard">身份证</option><option value="passport">护照</option>
                    </select>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">证件号码：</td>
                <td>
                    <input type="text" id="cert_no" name="billing_account[cert_no]" disabled="disabled" class="inputxt jq_back_account_input_class"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">支行所在省：</td>
                <td>
                    <input type="text" id="backAccountProvince" name="billing_account[province]" class="inputxt easyui-combobox" style="width:202px;" nullmsg="请选择支行所在省份"
                        data-options="height:32,width:202,valueField:'id',textField:'name',
                                        url:'/region/cascade?level=0',
                                        method: 'get',
                                        disabled: true,
                                        editable: false,
                                        onSelect: function(rec){
                                            $('#backAccountProvince').val(rec.id);
                                            $('#backAccountCity').combobox({
                                                url:'/region/cascade?level=1&pid='+rec.id,
                                                disabled: false,
                                                method: 'get'
                                        })}" />
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">支行所在市：</td>
                <td>
                    <input type="text" id="backAccountCity" name="billing_account[city]" class="inputxt easyui-combobox" style="width:202px;" nullmsg="请选择支行所在市"
                        data-options="height:32,width:202,valueField:'id',textField:'name',
                                        editable: false,
                                        disabled: true,onSelect: function(rec){
                                                                    $('#backAccountCity').val(rec.id);
                                                                }" />
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>
        </form>
</div>
<script type="text/javascript">
require(['plupload', 'util', 'easyui.widget.extend', 'mutiplupload'], function(plupload, util){
    util.createWindow['jq_add_form_merchant'] = $('#jq_add_form_merchant').Validform({confirmBeforeSubmit: true});

    $('#jq_add_form_merchant input').focus(function() {
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
            bankIdTextbox.textbox('disable');
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

});
</script>

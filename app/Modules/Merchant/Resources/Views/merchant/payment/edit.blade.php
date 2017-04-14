<div class="validform business_info" style="height: 450px; overflow-y: auto; padding: 0 10px;">
    <form id="jq_edit_form_merchantPayConf" action="/merchant/payment/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $merchantPayment->id }}"/>
        <input type="hidden" name="merchant_id" value="{{ $merchantPayment->merchant_id }}"/>
        <input type="hidden" id="billing_account_id" name="billing_account_id" value="{{ $merchantPayment->account->first()->id }}"/>
        <div class="info_title">基础配置</div>
        <table style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">商户：</td>
                <td style="width:200px;">
                    <input type="text" id="merchantName" value="{{ $merchantPayment->merchant->name }}" class="inputxt" disabled="disabled"/>
                </td>
                <td style="width:5px;" class="need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">支付类型：</td>
                <td style="width:200px;">
                    <input type="text" id="payment_id" name="payment_id" boxValue="{{ $merchantPayment->payment->id }}" boxText="{{ $merchantPayment->payment->name }}" class="inputxt" datatype="*" nullmsg="请选择支付类型"/>
                </td>
                <td style="width:5px;" class="need">*</td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">结算费率(‰)：</td>
                <td style="width:200px;">
                    <input type="text" id="billing_rate" name="billing_rate" value="{{ $merchantPayment->billing_rate*1000 }}" class="inputxt easyui-numberbox" data-options="precision:2,min:0,suffix:'‰',height:32" datatype="isDecimals2" nullmsg="请输入结算费率" errormsg="结算费率只能是数字，且小数点最多两位"/>
                </td>
                <td style="width:5px" class="need">*</td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="width:100px; text-align: right;">商户日交易限额：</td>
                <td style="width:200px;">
                    <input type="text" id="daily_trading_limit" name="daily_trading_limit" value="{{ $merchantPayment->daily_trading_limit }}" class="inputxt easyui-numberbox" data-options="precision:2,min:0,prefix:'￥',height:32" datatype="isDecimals2" nullmsg="请输入日交易限额" errormsg="日交易限额只能是数字，且小数点最多两位"/>
                </td>
                <td style="width:5px" class="need">*</td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">单笔最大限额：</td>
                <td>
                    <input type="text" id="single_max_limit" name="single_max_limit" value="{{ $merchantPayment->single_max_limit }}" class="inputxt easyui-numberbox" data-options="precision:2,min:0,prefix:'￥',height:32" datatype="isDecimals2" nullmsg="请输入单笔最大限额" errormsg="单笔最大限额只能是数字，且小数点最多两位"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">单笔最小限额：</td>
                <td>
                    <input type="text" id="single_min_limit" name="single_min_limit" value="{{ $merchantPayment->single_min_limit }}" class="inputxt easyui-numberbox" data-options="precision:2,min:0,prefix:'￥',height:32" datatype="isDecimals2" nullmsg="请输入单笔最小限额" errormsg="单笔最小限额只能是数字，且小数点最多两位"/>
                </td>
                <td class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
        </table>
        <div class="info_title">支付信息</div>
        <table cellspacing="10" style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:100px; text-align: right;">商户号：</td>
                <td style="width:200px; ">
                    <input type="text" id="merchant_no" name="merchant_no" value="{{ $merchantPayment->merchant_no }}" class="inputxt" nullmsg="请输入商户号"/>
                </td>
                <td style="width:5px;" class="need">*</td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;"></td>
                <td style="width:200px; "></td>
                <td style="width:5px;" class="need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;"></td>
                <td style="width:200px; "></td>
                <td style="width:5px;" class="need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
            </tr>
        </table>
            <div class="info_title">
                结算账户资料
                <label style="vertical-align: middle;">关联已有卡</label>
                <input id="jq_link_account_checkbox" type="checkbox" name="jq_link_account_checkbox" value="true" style="vertical-align: middle;" checked="true"/>
            </div>
            <table id="new_account_form" style="table-layout:fixed; width: 100%;" cellspacing="10">
            <tr>
                <td style="width:100px; text-align: right;">开户银行：</td>
                <td style="width:200px;">
                    <input type="text" id="jq_merchant_bank_id" name="billing_account[bank_id]" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" boxValue="{{ $merchantPayment->account->first()->bank()->first()->id }}" boxText="{{ $merchantPayment->account->first()->bank()->first()->name }}" nullmsg="请选择开户银行" />
                </td>
                <td style="width:5px;" class="need jq_back_account_input_class_need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">银行卡号：</td>
                <td style="width:200px;">
                    <input type="text" id="card_no" name="billing_account[card_no]" disabled="disabled" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class" value="{{ $merchantPayment->account->first()->card_no }}" nullmsg="请输入银行卡号"/>
                </td>
                <td style="width:5px;" class="need jq_back_account_input_class_need"></td>
                <td style="width:1px;"><div class="Validform_checktip"></div></td>
                <td style="width:100px; text-align: right;">开户人：</td>
                <td style="width:200px;">
                    <input type="text" id="account_holder" name="billing_account[account_holder]" disabled="disabled" class="inputxt jq_back_account_input_class jq_back_account_input_notnull_class"  value="{{ $merchantPayment->account->first()->account_holder }}" nullmsg="请输入开户人"/>
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
                    <input type="text" id="branch_name" name="billing_account[branch_name]" disabled="disabled" class="inputxt jq_back_account_input_class" value="{{ $merchantPayment->account->first()->branch_name }}"/>
                </td>
                <td class="need"></td>
                <td><div class="Validform_checktip"></div></td>
                <td style="text-align: right;">手机号码：</td>
                <td>
                    <input type="text" id="mobile" name="billing_account[mobile]" disabled="disabled" class="inputxt jq_back_account_input_class" value="{{ $merchantPayment->account->first()->mobile }}"/>
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
                    <input type="text" id="cert_no" name="billing_account[cert_no]" disabled="disabled" class="inputxt jq_back_account_input_class" value="{{ $merchantPayment->account->first()->cert_no }}"/>
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
                                        onChange: function(rec){
                                            $('#backAccountProvince').val(rec);
                                            $('#backAccountCity').combobox({
                                                url:'/region/cascade?level=1&pid='+rec,
                                                method: 'get',
                                                loadFilter: function(data) {
                                                    var cityValue = {{ $merchantPayment->account->first()->city }};
                                                    $('#backAccountCity').val(cityValue);
                                                    $.each(data, function() {
                                                        if (this.id == cityValue) {
                                                            this.selected = true;
                                                        }
                                                    });
                                                    return data;
                                                }
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
require(['util', 'easyui.widget.extend'], function(util) {
    util.createWindow['jq_edit_form_merchantPayConf'] = $('#jq_edit_form_merchantPayConf').Validform({confirmBeforeSubmit: true});

    $('#jq_edit_form_merchantPayConf input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    $('#payment_id').extTextbox({
        prompt: '请选择支付类型',
        url : '/payment/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '支付类型名称', name : 'name' }
        ],
        columns : [ [
            {title:'支付类型名称', field:'name', width:200},
            {title:'简称', field:'slug', width:200}
        ] ]
    });

    var billingAccountTextbox = $('#jq_billing_account_id').extTextbox({
        prompt: '请选择现有结算账户',
        disabled: true,
        url : '/bank/datagrid.json',
        idField : 'id',
        showField : 'name',
        method: 'get',
        searchFields : [
            { label : '银行名称', name : 'bank_name' },
            { label : '开户人', name : 'account_holder' },
            { label : '卡号', name : 'card_no' }
        ],
        columns : [ [
            {title:'银行名称', field:'name', width:200},
            {title:'银行英文缩写', field:'slug', width:200}
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

    var ennableAccountForm = function() {
        $('.jq_back_account_input_class').removeAttr('disabled').val('');
        $('.jq_back_account_input_notnull_class').attr('datatype', '*');
        $('.jq_back_account_input_class_need').html('*');
        $('#account_type').combobox('enable');
        $('#account_type').combobox('clear');
        $('#cert_type').combobox('enable');
        $('#cert_type').combobox('clear');
        bankIdTextbox.textbox('enable');
        bankIdTextbox.textbox('clear');
        $('#backAccountProvince').combobox('enable');
        $('#backAccountProvince').combobox('clear');
        $('#backAccountCity').combobox('enable');
        $('#backAccountCity').combobox('clear');
    }

    var disableAccountForm = function() {
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

    var setAccountInfo = function(row) {
        bankIdTextbox.textbox('setValue', row.bank_id);
        bankIdTextbox.textbox('setText', row.bank_name);
        $('#jq_merchant_bank_id_ext').val(row.bank_id);

        $('#card_no').val(row.card_no);
        $('#account_holder').val(row.account_holder);
        $('#account_type').combobox('select', row.account_type);
        $('#branch_name').val(row.branch_name);
        $('#mobile').val(row.mobile);
        $('#cert_type').combobox('select', row.cert_type);
        $('#cert_no').val(row.cert_no);
        $('#backAccountProvince').combobox('select', row.province);

        if (row.city) {
            $('#backAccountCity').combobox({
                url: '/region/cascade?level=1&pid='+row.province,
                disabled: true,
                loadFilter: function(data) {
                    $.each(data, function() {
                        if (this.id == row.city) {
                            this.selected = true;
                        }
                    });
                    return data;
                }
            });
        }
    }

    var alertAccountSearchPanel = function() {
        disableAccountForm();

        $.extWidget.SearchPanel({
            dialogTitle:'选择结算账户',
            url: '/merchant/accounts/{{ $merchantPayment->merchant_id }}',
            method: 'get',
            queryParams: {},
            targetId : 'billing_account_id',
            idField : 'id',
            showField : 'card_no',
            searchPanelWidth : 550,
            searchPanelHeight : 365,
            callbackFunc : function(row) {

            },
            searchFields : [
                { label : '开户人', name : 'account_holder' }
            ],
            columns : [ [
                {title:'银行名称', field:'bank_name', width:100},
                {title:'银行卡号', field:'card_no', width:100},
                {title:'开户人', field:'account_holder', width:100}
            ] ],
            queryBtnClickCallback : function() {
                return {};
            },
            onSelected: function(row) {
                $('#billing_account_id').val(row.id);
                if (row) {
                    setAccountInfo(row);
                } else {
                    $('#jq_link_account_checkbox').attr('checked', false);
                }
            }
        });
    };

    $('#jq_link_account_checkbox').bind('click', function() {
        if ($(this).is(':checked')) {
            alertAccountSearchPanel();
        } else {
            $('#billing_account_id').val('');
            ennableAccountForm();
        }
    });

    $('#account_type').combobox('select', '{{ $merchantPayment->account->first()->account_type }}');
    $('#cert_type').combobox('select', '{{ $merchantPayment->account->first()->cert_type }}');
    $('#backAccountProvince').combobox('select', '{{ $merchantPayment->account->first()->province }}');

});
</script>

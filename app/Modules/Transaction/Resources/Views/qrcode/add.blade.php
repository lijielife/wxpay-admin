<div class="validform business_info">
    <form id="jq_add_form_qrcode" action="/qrcode/make" method="post">
        {!! csrf_field() !!}
        <input type="hidden" id="jq_upload_qrLogo_hidden" name="merchant_logo"/>
        <table style="table-layout:fixed; ">
            <tr>
                <td style="text-align: right;">所属渠道：</td>
                    <td style="width:180px;">
                    <input type="text" id="channel_id" name="channel_id" boxValue="" boxText="" class="inputxt" datatype="*" nullmsg="请选择二维码所属渠道"/>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;">生成数量：</td>
                <td style="width:205px;">
                    <label><input name="num" type="radio" value="10" />10</label>
                    <label><input name="num" type="radio" value="20" />20</label>
                    <label><input name="num" type="radio" value="30" />30</label>
                    <label><input name="num" type="radio" value="50" />50</label>
                </td>
                <td style="width:20px;" class="need">*</td>
                <td><div class="Validform_checktip"></div></td>
            </tr>
            <tr>
                <td style="text-align: right;width:30%">LOGO：</td>
                <td style="width:70%">
                    <div class="info_upload">
                        <div style="color: #b2b2b2">注：请上传JPG、PNG格式图片。<br/>尺寸：100*100px</div>
                        <div class="upload_list" style="margin:0;text-align:left;">
                            <span class="upload_box" style="width: 100px; ">
                                <div class="upload_img" id="jq_upload_qrLogo_img_div" title="PC端图片" style="width: 100px; height: 100px">LOGO图片</div>
                                <div class="upload_txt jq_upload_click_class" id="jq_upload_qrLogo">+上传</div>
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">备注：</td>
                <td>
                    <textarea name="remark" style="width:192px; height: 90px;" maxlength="256"></textarea>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
require(['plupload', 'util', 'easyui.widget.extend', 'mutiplupload'], function(plupload, util){
    util.createWindow['jq_add_form_qrcode'] = $('#jq_add_form_qrcode').Validform({confirmBeforeSubmit: true});
    $('#jq_add_form_qrcode input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    var channelIdTextbox = $('#channel_id').extTextbox({
        prompt: '请选择二维码所属渠道',
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
        ] ]
    });

    $('#jq_upload_qrLogo').mutiupload({
        url: '/upload?type=image',//这里有个坑，如果修改为'/upload/?type=logo'，Laravel读取到的方法是GET，APATCH下存在,NGINX下没问题
        multipart_params: {
          _token: '{{ csrf_token() }}'
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'//pluoload默认不带X-Requested-With头，需要手动写上，否则laravel不认为是ajax请求
        },
        preview: {width: 100, height: 100/*, crop: true*/},
        /*resize: {
            width: 100,
            height: 100,
            crop: true,
            quality: 60,
            preserve_headers: false
        },*/
        success: function(uploader, file, result, browseBtn) {
            $('#' + browseBtn.attr('id') + '_hidden').val(result.data);
        }
    });

});
</script>

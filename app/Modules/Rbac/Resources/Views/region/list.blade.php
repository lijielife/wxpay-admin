<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
        <!-- 选项卡start -->
        <div class="tab_wrap">
            <!-- 选项卡内容start -->
            <div class="tab_con">
                <!-- 这里是每个选项卡详细内容 -->
                <form id="jq_search_form_region" action="/region/datagrid.json" method="post">
                {!! csrf_field() !!}
                <!-- 这里是每个选项卡详细内容 -->
                <div class="pay_box1">
                        <div class="fl w_100">地区名称：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="name" name="name" placeholder="模糊查询"/></span></div>
                        <!-- <div class="fl w_100">所属商户：</div>
                        <div class="fl w_150">
                            <input type="text" id="region_merchant" name="orgId" class="inputxt" />
                        </div> -->
                        <ul class="pay_btns pb">
                            <li><span  id="jq_search_btn_region" class="pay_btn btn_blue">查询</span></li>
                            <!-- <li><span id="jq_exp_btn_region" class="pay_btn btn_white">导出</span>
                            <a href="#" target="_blank" style="display: none;"><span id="jq_exp_btn_region_s"> </span></a>
                            </li>
                            <li>
                              <a id="regionExportHelpIcon" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-help'"> </a>
                            </li> -->
                        </ul>
                        <div class="clear"></div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_region" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    $.base("base.myeasyui.mydatagrid.regionGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.regionGrid.defaults = {
        dialogTitle: '行政区划列表',
        dialogWidth: 780,
        dialogHeight: 300,
        showAddButton: true,
        showEditButton: true,
        showDeleteButton: true,
        url: '/region/datagrid.json',
        forwardAddUrl: '/region/add',
        forwardEditUrl: '/region/edit',
        expExcelUrl: '/region/export',
        deleteUrl: '/region/destroy',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        // fitColumns: true,
        columns: [[
            {
                width: '5%',
                title: 'ID',
                field: 'id',
                checkbox: true
            },
            {
                width: '12.5%',
                title: '地名',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '简称',
                field: 'short_name'
            },
            {
                width: '16%',
                title: '全称',
                field: 'merge_name'
            },
            {
                width: '12.5%',
                title: '邮编',
                field: 'zip_code'
            },
            {
                width: '12.5%',
                title: '区号',
                field: 'city_code'
            },
            {
                width: '12.5%',
                title: '区域码',
                field: 'area_code'
            },
            // {
            //     width: '12.5%',
            //     title: '经度',
            //     field: 'lng'
            // },
            // {
            //     width: '12.5%',
            //     title: '纬度',
            //     field: 'lat'
            // },
            {
                width: '12.5%',
                title: '创建日期',
                field: 'created_at'
            },
            {
                width: '12.5%',
                title: '更新日期',
                field: 'updated_at'
            }
        ]]
    };

    $(this)['regionGrid']({className: 'region'});
    $.data(this, 'regionGrid').init();
});
</script>

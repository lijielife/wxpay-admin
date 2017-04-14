<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',split:false,border:false,split:false" style="height:80px; overflow: hidden;">
        <!-- 选项卡start -->
        <div class="tab_wrap">
            <!-- 选项卡内容start -->
            <div class="tab_con">
                <!-- 这里是每个选项卡详细内容 -->
                <form id="jq_search_form_store" action="/merchant/store/datagrid.json" method="post">
                {!! csrf_field() !!}
                <!-- 这里是每个选项卡详细内容 -->
                <div class="pay_box1">
                        <div class="fl w_100">门店名称：</div>
                        <div class="fl w_150"><span class="int_sp"><input type="text" id="name" name="name" placeholder="模糊查询"/></span></div>
                        <ul class="pay_btns pb">
                            <li><span  id="jq_search_btn_store" class="pay_btn btn_blue">查询</span></li>
                        </ul>
                        <div class="clear"></div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_store" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
	$.base("base.myeasyui.mydatagrid.storeGrid", {
		init : function() {
			this._super("_init");
		}
	}, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

	/**
	 * 以下是具体的配置,可覆盖父类的配置
	 */
	$.base_myeasyui_mydatagrid.storeGrid.defaults = {
		dialogTitle : '门店',
		dialogWidth : 320,
		dialogHeight : 190,
	    QRCodeButton : true,
		url : '/merchant/store/datagrid.json',
        @permission('store.add')
        showAddButton : true,
		forwardAddUrl : '/merchant/store/add',
        @endpermission
        @permission('store.edit')
        showEditButton : true,
		forwardEditUrl : '/merchant/store/edit',
        @endpermission
        @permission('store.delete')
        showDeleteButton: true,
		deleteUrl : '/merchant/store/destroy',
        @endpermission
		entityPk : 'id',
		// onDblClickRowEditFlag : false,
		columns : [ [
			{
				width : '14%',
				title : '门店名称 ',
				field : 'name'
			},
			{
				width : '14%',
				title : '所属大商户',
				field : 'parent',
				formatter: function(value, row, index) {
				    return value ? value.name : '';
				}
			},
			{
				width : '14%',
				title : '所属部门',
				field : 'department',
				formatter: function(value, row, index) {
				    return value ? value.name : '';
				}
			},
			{
	            width : '14%',
	            title : '秘钥',
	            field : 'secret_key'
	        },
			{
	            width : '14%',
	            title : '审核状态',
	            field : 'examine_status'
	        },
			{
	            width : '14%',
	            title : '激活状态',
	            field : 'activate_status'
	        }
		] ]
	};

	$(this)['storeGrid']({className: 'store'});
	$.data(this, 'storeGrid').init();
});
</script>

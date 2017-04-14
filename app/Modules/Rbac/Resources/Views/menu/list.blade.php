<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
        <table id="jq_datagrid_menu" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    $.base("base.myeasyui.mydatagrid.menuGrid", {
        init: function() {
            this._super("_init");
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.menuGrid.defaults = {
        dialogTitle: '菜单列表',
        dialogWidth: 350,
        dialogHeight: 220,
        showAddButton: true,
        showEditButton: true,
        showDeleteButton: true,

        /*树表格参数*/
        type: 'treegrid',
        idField:'id',
        treeField:'name',

        url: '/rbac/menu/datagrid.json',
        forwardAddUrl: '/rbac/menu/add',
        forwardEditUrl: '/rbac/menu/edit',
        expExcelUrl: '/rbac/menu/export',
        deleteUrl: '/rbac/menu/destroy',
        resetPwdUrl: '/rbac/menu/password',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        onLoadSuccess: function(row, data){
            if (data.length > 0) {
                //只有删除所有的子目录才能删除父目录
                for (var i = 0; i < data.length; i++) {
                    if (data[i].children && data[i].children.length > 0) {
                        // $("input[type='checkbox'][name='id'][value='"+data[i].id+"']").attr('disabled', true);
                        $("input[type='checkbox'][name='id'][value='"+data[i].id+"']").remove();
                        for (var j = 0; j < data[i].children.length; j++) {
                            if (data[i].children[j].children && data[i].children[j].children.length > 0) {
                                // $("input[type='checkbox'][name='id'][value='"+data[i].children[j].id+"']").attr('disabled', true);
                                $("input[type='checkbox'][name='id'][value='"+data[i].children[j].id+"']").remove();
                            }
                        }
                    }
                }
            }
        },
        columns: [[
            {
                width: '5',
                title: 'ID',
                field: 'id',
                checkbox: true
            },
            {
                width: '25%',
                title: '菜单名',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '状态',
                field: 'state',
                formatter: function(value, row, index){
                    return value == 'open' ? '展开' : '收缩';
                }
            },
            {
                width: '12.5%',
                title: '图标',
                field: 'icon',
                formatter: function(value, row, index){
                    return '<i class="'+value+'" style="font-size: 20px;"></i>';
                }
            },
            {
                width: '12.5%',
                title: '链接',
                field: 'url'
            },
            {
                width: '18%',
                title: '创建日期',
                field: 'created_at'
            },
            {
                width: '18%',
                title: '更新日期',
                field: 'updated_at'
            }
        ]]
    };

    $(this)['menuGrid']({className: 'menu'});
    // console.log($(this)['menuGrid']);
    // console.log($.data(this, 'menuGrid'));
    $.data(this, 'menuGrid').init();
});
</script>

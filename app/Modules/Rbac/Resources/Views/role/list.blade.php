<div data-options="region:'center',fit:false,border:false" style="height:100%;">
    <div class="easyui-layout" data-options="fit:true,border:false">
        <div data-options="region:'center',fit:false,border:false" style="padding: 10px 10px 0 10px;">
            <div class="easyui-layout" data-options="fit:true,border:false">
                <div data-options="region:'center',split:false,border:false,split:false" style="height:140px; overflow: hidden;">
                    <table id="jq_datagrid_role" data-options="fit:true,border:true" style="border: #b3d1f4 solid 1px; "></table>
                </div>
                <div data-options="region:'east',title:'权限列表',border:true,split:true,headerCls:'left-panel-header'" style="width:300px;border-color: #dddddd;">
                    <ul id="role_func_tree"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui.widget.extend'], function(util){
    var roleFuncTree;

    $.base("base.myeasyui.mydatagrid.roleGrid", {
        init: function() {
            this._super("_init");
            var that = this;

            roleFuncTree = $('#role_func_tree').tree({
                parentField : 'pid',
                onBeforeLoad : function(node, param) {},
                onLoadSuccess : function(node, data) {
                    $.messager.progress('close');

                    var rootNodes = $('#role_func_tree').tree('getRoots');
                    for (var k=0; k < rootNodes.length; k++) {
                        if ($('#role_func_tree').tree('isLeaf',  rootNodes[k].target)) {
                            continue;
                        }
                        $('#role_func_tree').tree('expand', rootNodes[k].target);
                        var nodes = $('#role_func_tree').tree('getChildren', rootNodes[k].target);
                        var del = false;
                        for (var i = 0; i < nodes.length; i++) {
                            if ($('#role_func_tree').tree('isLeaf', nodes[i].target) && nodes[i]['attr'] && nodes[i]['attr']['is_permission'] == true) {
                                var el = nodes[i].target;
                                $(el).parent().parent().css('margin-bottom', '4px');
                                if (!$(el).parent().attr('style')) {
                                    $(el).parent().attr('style', 'display:inline');
                                    $(el).attr('style', 'display:inline');
                                    if (del) {
                                        var els = $(el).children();
                                        for (var j = 0; j < els.length; j++) {
                                            var cls = $(els[j]).attr('class');
                                            if (cls == 'tree-indent') {
                                                $(els[j]).remove();
                                            } else if (cls.indexOf('tree-line') != -1) {
                                                $(els[j]).attr('class', cls.replace(' tree-line', ''));
                                            }
                                        }
                                    }
                                }
                                del = true;
                            } else {
                                del = false;
                            }
                        }
                    }
                }
            });
        }
    }, $.base_myeasyui.mydatagrid.prototype, $.base_myeasyui.mydatagrid.defaults);

    /**
     * 以下是具体的配置,可覆盖父类的配置
     */
    $.base_myeasyui_mydatagrid.roleGrid.defaults = {
        dialogTitle: '角色列表',
        dialogWidth: 350,
        dialogHeight: 220,
        showAddButton: true,
        showEditButton: true,
        showDeleteButton: true,

        /*树表格参数*/
        type: 'treegrid',
        idField:'id',
        treeField:'name',

        url: '/rbac/role/datagrid.json',
        forwardAddUrl: '/rbac/role/add',
        forwardEditUrl: '/rbac/role/edit',
        expExcelUrl: '/rbac/role/export',
        deleteUrl: '/rbac/role/destroy',
        resetPwdUrl: '/rbac/role/password',
        entityPk: 'id',
        frozenColumns: [[]],
        queryParams: {
            _token: '{{ csrf_token() }}'
        },
        checkOnSelect: false,
        onLoadSuccess: function(row, data){
            if (data.length > 0) {
                //只有删除所有的子角色才能删除父角色
                for (var i = 0; i < data.length; i++) {
                    if (data[i].children && data[i].children.length > 0) {
                        // $("input[type='checkbox'][name='id'][value='"+data[i].id+"']").attr('disabled', true);
                        $("input[type='checkbox'][name='id'][value='"+data[i].id+"']").remove();
                    }
                }
            }
        },
        onClickRow : function (rowData) {
            $.ajax({
                url: '/rbac/role/menu-permission-tree.json',
                method: 'post',
                type: 'json',
                data: {rid : rowData.id, showother: 0, _token: '{{ csrf_token() }}'},
                beforeSend: function(xhr) {
                    $.messager.progress({
                        title : '提示',
                        text : '数据处理中，请稍后....'
                    });
                },
                success: function(data) {
                    roleFuncTree.tree('loadData', data);
                }
            });
        },
        columns: [[
            {
                width: '5%',
                title: 'ID',
                field: 'id',
                checkbox: true
            },
            {
                width: '25%',
                title: '角色名',
                field: 'name'
            },
            {
                width: '12.5%',
                title: '别名',
                field: 'slug'
            },
            {
                width: '12.5%',
                title: '描述',
                field: 'description'
            },
            {
                width: '20%',
                title: '创建日期',
                field: 'created_at'
            },
            {
                width: '20%',
                title: '更新日期',
                field: 'updated_at'
            }
        ]]
    };

    $(this)['roleGrid']({className: 'role'});
    $.data(this, 'roleGrid').init();
});
</script>

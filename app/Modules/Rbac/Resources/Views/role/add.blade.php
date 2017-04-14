<div class="easyui-layout validform" data-options="fit:true,border:false" style="width: 450px; height:500px; margin: 10px;">
    <div data-options="region:'north',border:true" style="border:1px solid #ccc;height:200px;padding: 10px;">
        <form id="jq_add_form_role" action="/rbac/role/add" method="post">
        {!! csrf_field() !!}
        <input type="hidden" id="jq_permissions" name="permissionIds"/>
        <table style="table-layout:fixed;">
            <tr>
                <td style="text-align: right;">父角色：</td>
                <td>
                    <select id="pid" name="pid" class="inputxt easyui-combobox" data-options="panelHeight:140,height:32,width:202,onSelect: function(rec){$('#pid').val(rec.value);}">
                        <option value="0">顶级角色</option>
                        @foreach($roles as $role)
                        <option value="{{ $role['id'] }}">{{ $role['html'].$role['name'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="need" style="width:100px;">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">角色名：</td>
                <td>
                    <input type="text" id="name" name="name" class="inputxt" nullmsg="请输入角色名称" datatype="*2-24" />
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">别名：</td>
                <td>
                    <input type="text" id="slug" name="slug" class="inputxt" nullmsg="请输入角色别名" datatype="*2-24" />
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td valign="top" style="text-align: right;">描述：</td>
                <td colspan="3">
                    <textarea name="description" style="width:300px; height: 30px;" datatype="*1-128" ignore="ignore"></textarea>
                </td>
            </tr>
        </table>
    </form>
    </div>
    <div data-options="region:'center',border:false,fit:true" style="padding-top: 10px; padding-bottom: 5px;">
        <input type="checkbox" id="jq_all_privilege" value=""/>&nbsp;全选角色权限
    </div>
    <div data-options="region:'south',border:false" style="border:1px solid #ccc;height:290px;padding: 5px; overflow:scroll;">
        <ul id="ja_privilege_tree"></ul>
    </div>
</div>
<script type="text/javascript">
require(['util', 'easyui', 'valid_form', 'valid_form_datatype'], function(util) {
    var editFlag = 'false';
    var treeQueryParams = {_token: '{{ csrf_token() }}'};
    var formActionUrl = '/rbac/role/save';

    function setSelectedRights() {
        var nodes = $('#ja_privilege_tree').tree('getChecked', ['checked', 'indeterminate']);
        var ids = '';
        $.each(nodes, function() {
            if (this['attr']['is_permission']) { //节点不为模块节点
                ids += this['id'] + ',';
            }
        });
        if(ids != '') {
            ids = ids.substring(0, ids.length - 1);
        } else {
            $.dialog({
                title: '消息',
                width: 150,
                content: '请选择权限',
                icon: 'face-sad',
                time: 3
            });
            return false;
        }
        $('#jq_permissions').val(ids);
        return true;
    }

    var formOptions = {
        url : formActionUrl,
        beforeSubmit : function (param) {
            var selectRightsFlag = setSelectedRights();
            if (selectRightsFlag && $.messager) $.messager.progress();
            return selectRightsFlag;
        }
    };

    util.createWindow['jq_add_form_role'] = $('#jq_add_form_role').Validform(formOptions);
    $('#jq_add_form_role input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    $('#ja_privilege_tree').tree({
        checkbox: function(node) {
            if (node['attr']['is_permission']) { //节点不为模块节点
                return true;
            }
        },
        onlyLeafCheck: true,
        url : '/rbac/role/menu-permission-tree.json',
        queryParams : treeQueryParams,
        parentField : 'pid',
        //lines : true,
        onClick : function(node) {
            var nodes = $('#ja_privilege_tree').tree('getChecked', ['checked', 'indeterminate']);
            //console.info(nodes);
            var nodes2 = $('#ja_privilege_tree').tree('getChecked');
            //console.info(nodes2);
        },
        onBeforeLoad : function(node, param) {
            $.messager.progress({
                title : '提示',
                text : '数据处理中，请稍后....'
            });
        },
        onLoadSuccess : function(node, data) {
            $.messager.progress('close');

            var rootNodes = $('#ja_privilege_tree').tree('getRoots');
            for (var k=0; k < rootNodes.length; k++) {
                if ($('#ja_privilege_tree').tree('isLeaf',  rootNodes[k].target)) {
                    continue;
                }
                $('#ja_privilege_tree').tree('expand', rootNodes[k].target);
                var nodes = $('#ja_privilege_tree').tree('getChildren', rootNodes[k].target);
                var del = true;
                for (var i = 0; i < nodes.length; i++) {
                    if ($('#ja_privilege_tree').tree('isLeaf', nodes[i].target) || nodes[i]['attr'] && nodes[i]['attr']['is_permission'] == true) {
                        var el = nodes[i].target;
                        $(el).parent().parent().css('margin-bottom', '4px');
                        if (!$(el).parent().attr('style')) {
                            if (nodes[i]['attr'] && nodes[i]['attr']['is_permission'] == true) {
                                $(el).parent().attr('style', 'display:inline');
                                $(el).attr('style', 'display:inline');
                            }
                            if (del) {
                                var els = $(el).children();
                                for (var j = 1; j < els.length; j++) {
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

            if (editFlag == 'true') {
                setSelectedRights();
            } else {
                $('#enabled').combobox('select', 'true');
            }
        }
    });

    $('#jq_all_privilege').bind('click', function () {
        //$(this).attr("checked")
        var roots = $('#ja_privilege_tree').tree('getRoots');
        if ($(this).get(0).checked) {
            for (var i=0; i < roots.length; i++) {
                $('#ja_privilege_tree').tree('check', roots[i].target);
            }
        } else {
            for (var i=0; i < roots.length; i++) {
                $('#ja_privilege_tree').tree('uncheck', roots[i].target);
            }

        }
    });
});
</script>

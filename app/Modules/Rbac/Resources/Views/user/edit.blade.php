<div class="validform" style="width: 430px;">
    <form id="jq_edit_form_user" action="/rbac/user/save" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $user->id }}">
        <input type="hidden" name="role_ids" id="role_ids"/>
        <input type="hidden" name="permission_ids" id="permission_ids"/>
        <table style="table-layout:fixed; width: 100%;">
            <tr>
                <td style="width:99px; text-align: right;">姓名：</td>
                <td style="width:205px;">
                    <input type="text" id="name" name="name" class="inputxt" value="{{ $user->name }}" datatype="s2-24" nullmsg="请输入姓名" errormsg="姓名必须是2~24位"/>
                </td>
                <td class="need" style="width:110px;">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">邮箱：</td>
                <td>
                    <input type="text" id="email" name="email" class="inputxt" value="{{ $user->email }}" datatype="e" nullmsg="请输入邮箱"  errormsg="请输入正确的邮箱地址"/>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">密码：</td>
                <td>
                    <input type="password" id="password" name="password" class="inputxt" datatype="s8-18" nullmsg="请输入登陆密码" errormsg="密码必须是8~18位" ignore="ignore" placeholder="若不修改密码则不填"/>
                </td>
                <td class="need">*</td>
            </tr>
            <tr>
                <td style="text-align: right;">重复密码：</td>
                <td>
                    <input type="password" id="repassword" name="repassword" class="inputxt" recheck="password" datatype="s8-18" nullmsg="请重新输入登陆密码" errormsg="密码必须是8~18位" ignore="ignore" placeholder="若不修改密码则不填"/>
                </td>
                <td class="need">*</td>
            </tr>
        </table>
        <p class="acp-tips">每个子角色会继承所有父角色的权限，所以选择了子角色就不用再选择父角色。<br/>也可以不指定角色只分配权限。</p>
        <div class="easyui-tabs" style="height:300px;width:100%">
            <div title="角色" style="padding:10px">
                <ul id="role_tree"></ul>
            </div>
            <div title="权限" style="padding:10px">
                <ul id="menu_permission_tree"></ul>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
require(['util', 'valid_form', 'valid_form_datatype', 'easyui'], function(util){
    $('#jq_edit_form_user input').focus(function() {
        $(this).addClass('textbox-focused');
    }).blur(function() {
        $(this).removeClass('textbox-focused');
    });

    function setSelectedRoles() {
        var nodes = $('#role_tree').tree('getChecked', ['checked', 'indeterminate']);
        var ids = '';
        $.each(nodes, function() {
            ids += this['id'] + ',';
        });
        if(ids != '') {
            ids = ids.substring(0, ids.length - 1);
        } else {
            return false;
        }
        $('#role_ids').val(ids);
        return true;
    }

    function setSelectedPermissions() {
        var nodes = $('#menu_permission_tree').tree('getChecked', ['checked', 'indeterminate']);
        var ids = '';
        $.each(nodes, function() {
            ids += this['id'] + ',';
        });
        if(ids != '') {
            ids = ids.substring(0, ids.length - 1);
        } else {
            return false;
        }
        $('#permission_ids').val(ids);
        return true;
    }

    var formOptions = {
        url : '/rbac/user/save',
        beforeSubmit : function (param) {
            var selectFlag1 = setSelectedRoles();
            var selectFlag2 = setSelectedPermissions();
            if (!selectFlag1 && !selectFlag2) {
                $.dialog({
                    title: '消息',
                    width: 150,
                    content: '请选择角色或权限',
                    icon: 'face-sad',
                    time: 3
                });
                return false;
            }
            if ($.messager) {
                $.messager.progress();
            }
            return true;
        }
    };
    util.createWindow['jq_edit_form_user'] = $('#jq_edit_form_user').Validform(formOptions);

    $('#role_tree').tree({
        checkbox: true,
        cascadeCheck: false,
        url : '/rbac/role/role-tree.json?uid={{ $user->id }}',
        queryParams : {_token: '{{ csrf_token() }}'},
        parentField : 'pid',
        //lines : true,
        onClick : function(node) {
            var nodes = $('#role_tree').tree('getChecked', ['checked', 'indeterminate']);
            //console.info(nodes);
            var nodes2 = $('#role_tree').tree('getChecked');
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

            var rootNodes = $('#role_tree').tree('getRoots');
            for (var k=0; k < rootNodes.length; k++) {
                if ($('#role_tree').tree('isLeaf',  rootNodes[k].target)) {
                    continue;
                }
                $('#role_tree').tree('expand', rootNodes[k].target);
                var nodes = $('#role_tree').tree('getChildren', rootNodes[k].target);
                var del = true;
                for (var i = 0; i < nodes.length; i++) {
                    if ($('#role_tree').tree('isLeaf', nodes[i].target)) {
                        var el = nodes[i].target;
                        $(el).parent().parent().css('margin-bottom', '4px');
                        if (del) {
                            var els = $(el).children();
                            for (var j = 0; j < els.length; j++) {
                                var cls = $(els[j]).attr('class');
                                if (cls.indexOf('tree-file') != -1) {
                                    $(els[j]).attr('style', 'width:2px');
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

    $('#menu_permission_tree').tree({
        checkbox: function(node) {
            if (node['attr']['is_permission']) { //节点不为模块节点
                return true;
            }
        },
        onlyLeafCheck: true,
        url : '/rbac/role/menu-permission-tree.json?uid={{ $user->id }}',
        queryParams : {_token: '{{ csrf_token() }}'},
        parentField : 'pid',
        //lines : true,
        onClick : function(node) {
            var nodes = $('#menu_permission_tree').tree('getChecked', ['checked', 'indeterminate']);
            //console.info(nodes);
            var nodes2 = $('#menu_permission_tree').tree('getChecked');
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

            var rootNodes = $('#menu_permission_tree').tree('getRoots');
            for (var k=0; k < rootNodes.length; k++) {
                if ($('#menu_permission_tree').tree('isLeaf',  rootNodes[k].target)) {
                    continue;
                }
                $('#menu_permission_tree').tree('expand', rootNodes[k].target);
                var nodes = $('#menu_permission_tree').tree('getChildren', rootNodes[k].target);
                var del = true;
                for (var i = 0; i < nodes.length; i++) {
                    if ($('#menu_permission_tree').tree('isLeaf', nodes[i].target) || nodes[i]['attr'] && nodes[i]['attr']['is_permission'] == true) {
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
        }
    });
});
</script>

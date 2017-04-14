requirejs.config({
    baseUrl: '/assets/js',
    urlArgs: 'bust=' +  (new Date()).getTime(),
    paths: {
        jquery: 'jquery-3.0.0.min',
        underscore: 'underscore.min',
        backbone: 'backbone.min',
        plupload: 'plupload/plupload.full.min',
        mutiplupload: 'plupload/mutiplupload',
        easyui: 'easyui/jquery.easyui.min',
        easyui_lang_zh: 'easyui/locale/easyui-lang-zh_CN',
        layer: 'layer/layer.min',
        art_dialog: 'artdialog/jquery.artDialog',
        art_dialog_iframe: 'artdialog/plugins/iframeTools',
        valid_form: 'validform/my-validform-v5.3.2',
        valid_form_datatype: 'validform/validform-datatype'
    },
    shim: {
        jquery: {
            exports: 'jQuery'
        },
        underscore: {
            exports: '_'
        },
        backbone: {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        plupload: {
            exports: 'plupload'
        },
        mutiplupload: {
            deps: ['plupload'],
        },
        easyui: {
            deps: ['jquery'],
            exports: 'easyui'
        },
        easyui_lang_zh: {
            deps: ['easyui'],
            exports: 'easyuiZh'
        },
        layer: {
            deps: ['jquery'],
            exports: 'layer'
        },
        art_dialog: {
            deps: ['jquery'],
            exports: 'artDialog'
        },
        art_dialog_iframe: {
            deps: ['art_dialog'],
            exports: 'artDialogIframe'
        },
        valid_form: {
            deps: ['jquery'],
        },
        valid_form_datatype: {
            deps: ['valid_form'],
        }
    }
});

require(['jquery', 'underscore', 'backbone', 'easyui', 'layer', 'util', 'easyui_lang_zh', 'base', 'valid_form', 'valid_form_datatype'], function($, _, Backbone, easyui, layer, util){
    $.ajaxSetup({
        dataType: 'json',
        cache : false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            // 'X-Requested-With': 'XMLHttpRequest'
        },
        beforeSend: function(xhr, settings) {
            //console.log('ajax beforSend');
        },
        success: function(data, textStatus, jqXHR) {
            this.task && this.task.call(this, data, textStatus, jqXHR);
        },
        error: function(data, status, xhr) {
            //console.log('ajax error');
        },
        complete: function(xhr, statusText) {
            switch (xhr.status) {
                case 200:
                    break;
                case 401:
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '登陆超时！',
                        icon: 'warning',
                        ok: function () {
                            window.location.href = '/login';
                        }
                    });
                    break;
                case 403:
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '缺少权限！',
                        icon: 'warning',
                        time: 3
                    });
                    break;
                case 404:
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '页面不存在',
                        icon: 'warning',
                        time: 3
                    });
                    break;
                case 422:
                    // 忽略422验证失败提示框，这个在validform插件里已有处理
                    break;
                case 500:
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '服务端错误！',
                        icon: 'face-sad',
                        time: 3
                    });
                    break;
                default:
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: statusText,
                        icon: 'face-sad',
                        time: 3
                    });
                    break;
            }
        }
    });

    var indexTabs = $('#index_tabs').tabs({
        fit: true,
        plain : true,
        pill : true,
        border : false,
        cache : false,
        tabHeight : 36,
        onContextMenu : function(e, title) {
            e.preventDefault();
            tabsMenu.menu('show', {
                left : e.pageX,
                top : e.pageY
            }).data('tabTitle', title);
        }
    });

    var tabsMenu = $('#tabs_menu').menu({
        onClick : function(item) {
            var curTabTitle = $(this).data('tabTitle');
            var type = $(item.target).attr('title');

            if (type === 'refresh') {
                indexTabs.tabs('getTab', curTabTitle).panel('refresh');
                return;
            }

            if (type === 'close') {
                var t = indexTabs.tabs('getTab', curTabTitle);
                if (t.panel('options').closable) {
                    indexTabs.tabs('close', curTabTitle);
                }
                return;
            }

            var allTabs = indexTabs.tabs('tabs');
            var closeTabsTitle = [];

            $.each(allTabs, function() {
                var opt = $(this).panel('options');
                if (opt.closable && opt.title != curTabTitle && type === 'closeOther') {
                    closeTabsTitle.push(opt.title);
                } else if (opt.closable && type === 'closeAll') {
                    closeTabsTitle.push(opt.title);
                }
            });

            for (var i = 0; i < closeTabsTitle.length; i++) {
                indexTabs.tabs('close', closeTabsTitle[i]);
            }
        }
    });

    function addTab(params) {
        var t = $('#index_tabs');
        var opts = {
            title : params.title,
            closable : true,
            iconCls : params.iconCls,
            //href : params.url,
            border : false,
            fit : true
        };
        if (params.renderWay == "2") {
            opts.content = '<iframe scrolling="auto" frameborder="0" src="' + params.funcContext + params.url + '" style="width:100%; height:100%;"></iframe>';
        } else {
            opts.href = params.url;
        }
        if (t.tabs('exists', opts.title)) {
            t.tabs('select', opts.title);
        } else {
            t.tabs('add', opts);
        }
    };

    function getMenu() {
        $.ajax({
            type : 'GET',
            url : '/rbac/menu/nav.json',
            task: function (data, textStatus, jqXHR) {
                var menuData = buildTreeData(data);
                $('#jq_menu_ul').empty().append(createMenuTree(menuData));
                bindMenuEvent();

                $.each(data, function() {
                    if (this['attributes'] && this['attributes']['displayFlag']) {
                        indexWestPanelDisplayFlagMap[this['text']] = this['attributes']['displayFlag'];
                    }
                });
            }
        });
    }

    function buildTreeData(data, options) {
        var opt = $.extend({idFiled : 'id', textFiled : 'text', parentField : 'pid'}, options);
        var idFiled = opt.idFiled || 'id';
        var textFiled = opt.textFiled || 'text';
        var parentField = opt.parentField || 'pid';
        var i, l, treeData = [], tmpMap = [];
        for (i = 0, l = data.length; i < l; i++) {
            tmpMap[data[i][idFiled]] = data[i];
        }
        for (i = 0, l = data.length; i < l; i++) {
            if (tmpMap[data[i][parentField]] && data[i][idFiled] != data[i][parentField]) {
                if (!tmpMap[data[i][parentField]]['children'])
                    tmpMap[data[i][parentField]]['children'] = [];
                data[i]['text'] = data[i][textFiled];
                tmpMap[data[i][parentField]]['children'].push(data[i]);
            } else {
                data[i]['text'] = data[i][textFiled];
                treeData.push(data[i]);
            }
        }
        return treeData;
    };

    function createMenuTree(data) {
        if ($.isArray(data)) {
            var treeHtml = '';
            $.each(data, function(i, node) {
                if (node.text != '首页') {
                    treeHtml += '<li class="item">';
                    treeHtml += '    <a href="javascript:void(0);" class="item_name jq_module_li_a_class" id="jq_module_li_a_' + node.id + '"><b class="' + node.attributes.icon + '"></b><span>' + node.text + '</span></a>';
                    if (node.children && $.isArray(node.children)) {
                        treeHtml += createChildrenNode(node.children, node.state, 'sub_item hide', 1);
                    }
                    treeHtml += '</li>';
                }
            });
            return treeHtml;
        } else {
            //console.info("菜单数据格式不对");
        }
    }

    function createChildrenNode(childrenData, state, ulClass, level) {
        var treeHtml = '';
        if ($.isArray(childrenData)) {
            treeHtml += '<ul class="' + ulClass + '">';
            $.each(childrenData, function(i, node) {
                var aClass = 'item_name2 jq_menu_li_a_class_url jq_menu_li_a_class_' + level;
                var tragetUrl = '';
                if (!node.children) {
                    tragetUrl = ' url="' + node.attributes.url + '" renderWay="' + node.attributes.renderWay + '" funcContext="' + node.attributes.funcContext + '" ';
                    aClass = 'item_name2 notriangle jq_menu_li_a_class_url jq_menu_li_a_class_5';
                }
                if (node.children && node.attributes && node.attributes.url) {
                    tragetUrl = ' url="' + node.attributes.url + '" renderWay="' + node.attributes.renderWay + '" funcContext="' + node.attributes.funcContext + '" ';
                    aClass = 'jq_menu_li_a_class_url jq_menu_li_a_class_' + level;
                }
                if (level == 2) {
                    aClass = 'jq_menu_li_a_class_url jq_menu_li_a_class_' + level;
                }
                treeHtml += '   <li>';
                treeHtml += '       <a href="javascript:void(0);" ' + tragetUrl +  ' id="jq_menu_li_a_' + node.id + '" class="' + aClass + '"><i class="' + node.attributes.icon + '"></i>' + node.text + '</a> ';
                if (node.children && $.isArray(node.children)) {
                    treeHtml += createChildrenNode(node.children, node.state, 'sub_item2 hide', 2);
                }
                treeHtml += '   </li>';
            });
            treeHtml += '</ul>';
            return treeHtml;
        } else {
            //console.info("菜单数据格式不对");
        }
    }

    function bindMenuEvent() {
        $('.jq_module_li_a_class').bind('click', function() {
            if($(this).hasClass('on1')){
                $(this).removeClass("on1").next().slideUp(300);
            }else{
                $(".left_nav .on1").removeClass('on1').next().slideUp(300);
                $(this).addClass('on1').next().slideDown(300);
            }
        });
        $(".jq_menu_li_a_class_1").click(function(){
            if($(this).hasClass('on2')){
                $(this).removeClass("on2").next().slideUp(300);
            }else{
                $(".left_nav .on2").removeClass('on2').next().slideUp(300);
                $(this).addClass('on2').next().slideDown(300);
            }
        });

        $(".jq_menu_li_a_class_2").click(function(){
            $(this).parents(".sub_item").find(".on2").removeClass("on2");
            $(this).parents(".sub_item2").prev().addClass("on2");
            $(".sub_item2 a.on3").removeClass("on3");
            $(this).addClass("on3");
        });

        $(".jq_menu_li_a_class_5").click(function(){
            $(".jq_menu_li_a_class_5").removeClass("on3");
            $(this).addClass("on3");
        });

        $('.jq_menu_li_a_class_url').bind('click', function() {
            var url = $(this).attr('url');
            var title = $(this).html();
            if (url && url != '') {
                var renderWay = $(this).attr('renderWay');
                var funcContext = $(this).attr('funcContext');
                addTab({
                    url : url,
                    title : title,
                    iconCls : '',
                    renderWay : (renderWay ? renderWay : 1),
                    funcContext : funcContext
                });
            }
        });
    }

    getMenu();

    $('body').on('focus', '.inputtxt', function() {
        $(this).addClass('textbox-focused');
    }).on('blur', '.inputtxt', function() {
        $(this).removeClass('textbox-focused');
    });

    $('#login_out').bind('click', function() {
        $.dialog.confirm('您是否要退出系统？', function() {
            location.href = "/logout";
         }, function() {});
    });

    $('#update_password').bind('click', function() {
        util.createWindow({
             title : '修改密码',
             href : '/rbac/user/resetpwd',
             width : 320,
             height : 150,
             resizable : false,
             dblclickHide : true,
             esc : false,
             //cancel : false,
             button : [ {
                 name : '修改',
                 focus : true,
                 callback : function() {
                     util.createWindow.openner_dataGrid = self.grid;
                     var updateForm = util.createWindow['jq_user_pwd_reset_form'];
                     if (updateForm) {
                         updateForm.submitForm();
                     } else {
                         $.dialog({
                            title: '消息',
                            width: 150,
                            content: '新增页面Validaform初始化异常，请检查！',
                            icon: 'error',
                            time: 5
                        });
                     }
                     return false;
                 }
             } ]
         });
    });

});

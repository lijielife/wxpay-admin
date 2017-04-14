define(['jquery', 'util', 'art_dialog', 'art_dialog_iframe'], function($, util) {
    $.base = function(name, prototype, parentPrototype, parentDefaults) {
        var parentPrototype = parentPrototype || $.base.prototype;
        var parentDefaults = parentDefaults || $.base.defaults;
        var namespace =  name.split(".").slice(0, name.split(".").length - 1);
        namespace = namespace.toString().replace(/,/g,'_');
        name = name.split(".")[name.split(".").length - 1];

        $.fn[name] = function(options) {
            var isMethodCall = (typeof options == 'string'), args = Array.prototype.slice.call(arguments, 1);

            if (isMethodCall && options.substring(0, 1) == '_') {
                return this;
            }

            if (isMethodCall/* && getter(namespace, name, options, args)*/) {
                var instance = $.data(this[0], name);
                return (instance ? instance[options].apply(instance, args): undefined);
            }

            return this.each(function() {
                var instance = $.data(this, name);

                (!instance && !isMethodCall && $.data(this, name, new $[namespace][name](this, options))/*._init()*/);
                (instance && isMethodCall && $.isFunction(instance[options]) && instance[options].apply(instance, args));
            });
        };

        $[namespace] = $[namespace] || {};
        $[namespace][name] = function(element, options) {
            var self = this;
            this.namespace = namespace;
            this.name = name;
            this.eventPrefix = $[namespace][name].eventPrefix || name;
            this.baseClass = namespace + '-' + name;

            var parentClass = namespace.split("_");
            parentClass.length = parentClass.length - 1;

            if(parentClass.length == 2){ //处理3级继承，3级以上的继承暂时不支持
                //顶层父类的方法引用名
                var _parentTop = parentClass[1];
                var _parentNamespace = parentClass.toString().replace(/,/g,'.');
                //顶层父类的方法
                var _parentTopPrototype = eval('$.'+_parentNamespace).prototype;
                //this[_parentTop] = _parentTopPrototype;
                //调用顶级父类中的方法
                $[namespace][name].prototype._topSuper = function(funcName, options) {
                    _parentTopPrototype[funcName].call(this, options);
                };
                parentDefaults = $.extend({}, _parentTopPrototype, parentDefaults);

                parentDefaults = $.extend({}, eval('$.'+_parentNamespace).defaults, parentDefaults);
            }
            this.options = $.extend({}, parentDefaults, $[namespace][name].defaults, $.metadata && $.metadata.get(element)[name], options);

            this.element = $(element).bind('setData.' + name, function(event, key, value) {
                if (event.target == element) {
                    return self._setData(key, value);
                }
            }).bind('getData.' + name, function(event, key) {
                if (event.target == element) {
                    return self._getData(key);
                }
            }).bind('remove', function() {
                return self.destroy();
            });
        };
        $[namespace][name].prototype = $.extend({}, parentPrototype, prototype);

        //调用二级父类中的方法
        $[namespace][name].prototype._super = function(funcName, options) {
            parentPrototype[funcName].call(this, options);
        },

        $[namespace][name].getterSetter = 'option';

    };

    $.base.prototype = {
        _init: function() {},
        destroy: function() {},
        option: function(key, value) {
            var options = key, self = this;

            if (typeof key == "string") {
                if (value === undefined) {
                    return this._getData(key);
                }
                options = {};
                options[key] = value;
            }

            $.each(options, function(key, value) {
                self._setData(key, value);
            });
        },
        _getData: function(key) {
            return this.options[key];
        },
        _setData: function(key, value) {
            this.options[key] = value;
        },
        enable: function() {
            this._setData('disabled', false);
        },
        disable: function() {
            this._setData('disabled', true);
        },
        _trigger: function(type, event, data) {
            var callback = this.options[type], eventName = (type == this.eventPrefix ? type: this.eventPrefix + type);

            event = $.Event(event);
            event.type = eventName;

            if (event.originalEvent) {
                for (var i = $.event.props.length, prop; i;) {
                    prop = $.event.props[--i];
                    event[prop] = event.originalEvent[prop];
                }
            }

            this.element.trigger(event, data);

            return !($.isFunction(callback) && callback.call(this.element[0], event, data) === false || event.isDefaultPrevented());
        }
    };

    $.base.defaults = {
        disabled: false
    };

    /*---------------------------------------------------------------------------------------------------------------------------------*/
    $.base("base.myeasyui", {
        _init: function() {},
        getClassHandler: function(calssName) {
            return $(this).data(calssName) || {};
        },
        handle: function(options) {
            options = $.extend({
                handlerPrefix: "", // 要调用的处理器方法前缀, 必要的参数
                handler: "toggleClass", // 要调用的处理器方法后缀, 必要的参数
                defaultHandle: function(options) {} // 默认的处理方法, 必要的参数
            }, options);

            if (this.hasHandler(options.handlerPrefix, options.handler)) {
                return this[options.handlerPrefix + "." + options.handler](options);
            } else if (this[options.handler] != undefined) {
                return this[options.handler](options);
            } else {
                return options.defaultHandle.call(this, options); // 缺省的处理
            }
        },
        hasHandler: function(handlerPrefix, handler) {
            return this[handlerPrefix + "." + handler] != undefined;
        }
    }, $.base.prototype, $.base.defaults);

    /**
     * 以下是所有js公共的配置,可被子类覆盖.
     */
    $.base.myeasyui.defaults = {
        tagPrefix: "jq_" // 业务标签前缀(暂时未用到)
    };

    /*---------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * 所有datagrid的父类，继承自base.easyui类
     */
    $.base("base.myeasyui.mydatagrid", {
        grid : {}, // 保存datagrid对象
        getClassName : function () {
            return this.options.className;
        },
        _init : function() {
            this['load_grid']();
            this['search_fun']();
            this['exp_fun']();
        },
        'exp_fun' : function(options) {
            options = $.extend({}, this.options, options);
            var self = this;

            var expQueryParams = self.handle({
                handlerPrefix : 'expQueryParams',
                handler : 'toggleClass',
                defaultHandle : function(opts) {
                    return function (options) {
                        return $('#' + options.searchFormIdPrefix + options.className).serialize();
                    };
                }
            });

            $('#' + options.expBtnIdPrefix + options.className).bind('click', function() {
                var expUrl = options.expExcelUrl + '&' + expQueryParams(options) + '&pageSize=0';
                $(this).next().attr('href', expUrl).click();
                $('#' + options.expBtnIdPrefix + options.className + '_s').click();
            });
        },
        'search_fun' : function(options) {
            options = $.extend({}, this.options, options);
            var self = this;
            $('#' + options.searchBtnIdPrefix + options.className).bind('click', function() {
                if (!options.autoLoadDataFlag) {
                    var gridOptions = self.grid.datagrid('options');
                    gridOptions.url = self.options.url;
                }
                var pager = self.grid.datagrid('getPager');
                var pagerOptions = pager.pagination('options');
                var loadParams = util.serializeForm($('#' + options.searchFormIdPrefix + options.className));
                loadParams.pageSize = pagerOptions.pageSize;
                self.grid.datagrid('load', loadParams);
            });
        },
        'load_grid' : function(options) {
            var self = this;
            options = $.extend({}, this.options, options);

            var queryParams = self.handle({
                handlerPrefix: 'queryParams',
                handler: 'toggleClass',
                defaultHandle: function(options) {
                    return this.options.queryParams;
                }
            });
            options.queryParams = queryParams;

            var addFun = self.handle($.extend({
                handlerPrefix : 'addFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(opts) {
                    return function (options) {
                        util.createWindow($.extend({
                            title : '添加' + (options.dialogTitle || ''),
                            href : options.forwardAddUrl,
                            width : options.dialogWidth,
                            height : options.dialogHeight,
                            resizable : options.dialogResizable,
                            button : [ {
                                name : '保存',
                                focus : true,
                                callback : function() {
                                    if (options.type == 'treegrid') {
                                        util.createWindow.openner_treeGrid = self.grid;
                                    } else {
                                        util.createWindow.openner_dataGrid = self.grid;
                                    }

                                    var addForm = util.createWindow[options.addFormIdPrefix + options.className];
                                    if (addForm) {
                                        addForm.submitForm();
                                    } else {
                                        $.dialog({
                                            title: '消息',
                                            width: 150,
                                            content: '新增页面Validaform初始化异常，请检查！',
                                            icon: 'error',
                                            time: 5
                                        });
                                    }
                                    if ($('.jq_search_panel_class').length > 0) {
                                        $('.jq_search_panel_class').remove();
                                    }
                                    return false;
                                }
                            } ]
                        }, options));
                    };
                }
            }, options));
            var editFun = self.handle($.extend({
                handlerPrefix : 'editFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(opts) {
                    return function (options) {
                        var selectRow = self.grid.datagrid('getSelected');
                        if (selectRow) {
                            var id = selectRow[options.entityPk];
                            util.createWindow($.extend({
                                title : '编辑' + (options.dialogTitle || ''),
                                href : options.forwardEditUrl + '?' + options.entityPk + '=' + id,
                                width : options.editDialogWidth || options.dialogWidth,
                                height : options.editDialogHeight || options.dialogHeight,
                                resizable : options.dialogResizable,
                                button : [ {
                                    name : '编辑',
                                    focus : true,
                                    callback : function() {
                                        if (options.type == 'treegrid') {
                                            util.createWindow.openner_treeGrid = self.grid;
                                        } else {
                                            util.createWindow.openner_dataGrid = self.grid;
                                        }
                                        var editForm = util.createWindow[options.editFormIdPrefix + options.className];
                                        if (editForm) {
                                             editForm.submitForm();
                                        } else {
                                            $.dialog({
                                                title: '消息',
                                                width: 150,
                                                content: '编辑页面Validaform初始化异常，请检查！',
                                                icon: 'error',
                                                time: 5
                                            });
                                        }
                                        if ($('.jq_search_panel_class').length > 0) {
                                            $('.jq_search_panel_class').remove();
                                        }
                                        return false;
                                    }
                                } ]
                            }, options));
                        } else {
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: '请选择要编辑的记录!',
                                icon: 'warning',
                                time: 3
                            });
                        }
                    };
                }
            }, options));

            var detailFun = self.handle($.extend({
                handlerPrefix : 'detailFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(opts) {
                    return function (options) {
                        if (options.type == 'treegrid') {
                            var selectRow = self.grid.treegrid('getSelected');
                        } else {
                            var selectRow = self.grid.datagrid('getSelected');
                        }
                        if (selectRow) {
                            var id = selectRow[options.entityPk];
                            util.createWindow($.extend({
                                title : '详情',
                                href : options.forwardDetailUrl + '?' + options.entityPk + '=' + id,
                                width : options.editDialogWidth || options.dialogWidth,
                                height : options.editDialogHeight || options.dialogHeight,
                                resizable : options.dialogResizable
                            }, options));
                        } else {
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: '请选择要查看详情的记录!',
                                icon: 'warning',
                                time: 3
                            });
                        }
                    };
                }
            }, options));

            var deleteFun = self.handle($.extend({
                handlerPrefix : 'deleteFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(options) {
                    return function (options) {
                        if (options.type == 'treegrid') {
                            var selectRows = self.grid.treegrid('getChecked');
                        } else {
                            var selectRows = self.grid.datagrid('getChecked');
                        }
                        if (selectRows && selectRows.length > 0) {
                            $.dialog.confirm('您是否要废弃当前当前选中的记录？', function() {
                                var ids = [];
                                $.each(selectRows, function(i) {
                                    ids.push(selectRows[i][options.entityPk]);
                                });
                                $.post(options.deleteUrl, $.extend({ids : ids}, options.queryParams), function(result) {
                                    if (result.success) {
                                        $.dialog({
                                            title: '消息',
                                            width: 150,
                                            content: result.msg,
                                            icon: 'face-smile',
                                            time: 3
                                        });

                                        if (options.type == 'treegrid') {
                                            self.grid.treegrid('reload');
                                        } else {
                                            self.grid.datagrid('reload');
                                        }
                                    } else {
                                        $.dialog({
                                            title: '消息',
                                            width: 150,
                                            content: result.msg,
                                            icon: 'face-sad',
                                            time: 3
                                        });
                                    }
                                }, 'JSON');
                            }, function() {});
                        } else {
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: '请选中要废弃的记录!',
                                icon: 'warning',
                                time: 3
                            });
                        }
                    };
                }
            }, options));

            var examineFunCallback = function(options) {
                if (options.type == 'treegrid') {
                    util.createWindow.openner_treeGrid = self.grid;
                } else {
                    util.createWindow.openner_dataGrid = self.grid;
                }
                var editForm = util.createWindow[options.editFormIdPrefix + options.className];
                if (editForm) {
                    editForm.submitForm();
                } else {
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: '编辑页面Validaform初始化异常，请检查！',
                        icon: 'error',
                        time: 5
                    });
                }
                if ($('.jq_search_panel_class').length > 0) {
                    $('.jq_search_panel_class').remove();
                }
                return false;
           };

           var examineFun = self.handle($.extend({
                handlerPrefix : 'examineFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(options) {
                    return function (options) {
                        if (options.type == 'treegrid') {
                            var selectRow = self.grid.treegrid('getSelected');
                        } else {
                            var selectRow = self.grid.datagrid('getSelected');
                        }
                        if (selectRow) {
                            var id = selectRow[options.entityPk];
                            var examineStatus = selectRow[options.examineStatus];
                            if (examineStatus && examineStatus == options.examineStatusPass) {
                                $.dialog({
                                    title: '消息',
                                    width: 150,
                                    content: '审核通过的记录不能再次审核!',
                                    icon: 'warning',
                                    time: 3
                                });
                                return false;
                            }
                            util.createWindow($.extend({
                                title : '审核' + (options.dialogTitle || ''),
                                href : options.forwardExamineUrl + '?' + options.entityPk + '=' + id,
                                width : options.examineDialogWidth || options.dialogWidth,
                                height : options.examineDialogHeight || options.dialogHeight,
                                resizable : options.dialogResizable,
                                button : [{
                                    name : '审核通过',
                                    focus : true,
                                    callback : function() {
                                        $('#' + options.editFormIdPrefix + options.className + '_examineStatus').val('success');
                                        return examineFunCallback(options);
                                    }
                                },
                                {
                                    name : '审核不通过',
                                    focus : false,
                                    callback : function() {
                                        $('#' + options.editFormIdPrefix + options.className + '_examineStatus').val('failed');
                                        return examineFunCallback(options);
                                    }
                                }]
                            }, options));
                         } else {
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: '请选择要审核的记录!',
                                icon: 'warning',
                                time: 3
                            });
                        }
                    };
                }
            }, options));

            var activateFunCallback = function(options) {
                if (options.type == 'treegrid') {
                    util.createWindow.openner_treeGrid = self.grid;
                } else {
                    util.createWindow.openner_dataGrid = self.grid;
                }
                var editForm = util.createWindow[options.editFormIdPrefix + options.className];
                if (editForm) {
                    editForm.submitForm();
                } else {
                    $.dialog({
                       title: '消息',
                       width: 150,
                       content: '编辑页面Validaform初始化异常，请检查！',
                       icon: 'error',
                       time: 5
                    });
                }
                if ($('.jq_search_panel_class').length > 0) {
                    $('.jq_search_panel_class').remove();
                }
                return false;
            };

            var activateFun = self.handle($.extend({
                handlerPrefix : 'activateFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(options) {
                    return function (options) {
                        if (options.type == 'treegrid') {
                            var selectRow = self.grid.treegrid('getSelected');
                        } else {
                            var selectRow = self.grid.datagrid('getSelected');
                        }
                        if (selectRow) {
                            var id = selectRow[options.entityPk];
                            var activateStatus = selectRow[options.activateStatus];
                            /*if (activateStatus && activateStatus == options.activateStatusPass) {
                                 $.dialog({
                                        title: '消息',
                                        width: 150,
                                        content: '已激活的记录不能再次审核!',
                                        icon: 'warning',
                                        time: 3
                                    });
                                 return false;
                             }*/
                             util.createWindow($.extend({
                                title : '激活' + (options.dialogTitle || ''),
                                href : options.forwardActivateUrl + '?' + options.entityPk + '=' + id,
                                width : options.activateDialogWidth || options.dialogWidth,
                                height : options.activateDialogHeight || options.dialogHeight,
                                resizable : options.dialogResizable,
                                button : [ {
                                    name : '激活通过',
                                    focus : true,
                                    callback : function() {
                                        $('#' + options.editFormIdPrefix + options.className + '_activateStatus').val('success');
                                        return activateFunCallback(options);
                                    }
                                },
                                {
                                    name : '激活不通过',
                                    focus : false,
                                    callback : function() {
                                        $('#' + options.editFormIdPrefix + options.className + '_activateStatus').val('failed');
                                        return activateFunCallback(options);
                                    }
                                },
                                {
                                    name : '冻结',
                                    focus : false,
                                    callback : function() {
                                        $('#' + options.editFormIdPrefix + options.className + '_activateStatus').val('freeze');
                                        return activateFunCallback(options);
                                    }
                                } ]
                            }, options));
                         } else {
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: '请选择要激活的记录!',
                                icon: 'warning',
                                time: 3
                            });
                        }
                    };
                 }
           }, options));

           var helpFun = self.handle($.extend({
                handlerPrefix : 'helpFun',
                handler : 'toggleClass',
                self : self,
                defaultHandle : function(opts) {
                    return function (options) {
                        if (options.forwardHelpUrl) {
                            util.createWindow($.extend({
                                title :  (options.dialogTitle || '') + '帮助页',
                                href : options.forwardHelpUrl,
                                width : options.editDialogWidth || options.dialogHelpWidth,
                                height : options.editDialogHeight || options.dialogHelpHeight,
                                resizable : options.dialogResizable
                            }, options));
                        }
                    };
                }
            }, options));

            var extToolbar = self.handle($.extend({
                handlerPrefix : 'extToolbar',
                handler : 'toggleClass',
                defaultHandle : function(options) {
                    return [];
                }
            }, options));

            var toolbar = self.handle($.extend({
                handlerPrefix : 'toolbar',
                handler : 'toggleClass',
                defaultHandle : function(options) {
                    var buttons = [];
                    if (options.showAddButton) {
                        buttons.push({id : 'jq_' + options.className + '_add_btn', text : (options.addBtnName || '新增'), disabled : options.gridAddBtnDisabled, iconCls : 'icon-add', size : 'large', handler : function(){addFun(options);}});
                    }
                    if (options.showEditButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_edit_btn', text : (options.editBtnName || '编辑'), disabled : options.gridEditBtnDisabled,  iconCls : 'icon-edit', size : 'large', handler : function(){editFun(options);}});
                    }
                    if (options.showDetailButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_detail_btn', text : (options.detailBtnName || '详情'), disabled : options.gridDetailButtonDisabled,  iconCls : 'icon-detail', size : 'large', handler : function(){detailFun(options);}});
                    }
                    if (options.showDeleteButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_delete_btn', text : (options.deleteBtnName || '废弃'), disabled : options.gridDeleteBtnDisabled,  iconCls : 'icon-remove', size : 'large', handler : function(){deleteFun(options);}});
                    }
                    if (options.showExamineButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_examine_btn', text : (options.examineBtnName || '审核'), disabled : options.gridExamineBtnDisabled,  iconCls : 'icon-examine', size : 'large', handler : function(){examineFun(options);}});
                    }
                    if (options.showActivateButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_activate_btn', text : (options.activateBtnName || '激活'), disabled : options.gridActivateBtnDisabled,  iconCls : 'icon-activate', size : 'large', handler : function(){activateFun(options);}});
                    }
                    if (options.showExportButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_export_btn', text : (options.exportBtnName || '导出'), disabled : options.gridExportBtnDisabled,  iconCls : 'icon-redo', size : 'large', handler : function(){deleteFun(options);}});
                    }
                    if (options.showHelpButton) {
                        if (buttons.length > 0) {
                            buttons.push('-');
                        }
                        buttons.push({id : 'jq_' + options.className + '_help_btn', text : (options.helpBtnName || '帮助'), disabled : options.gridHelpBtnDisabled,  iconCls : 'icon-help', size : 'large', handler : function(){helpFun(options);}});
                    }
                    if ($.isArray(extToolbar) && extToolbar.length > 0) {
                        $.each(extToolbar, function() {
                            if (buttons.length > 0) {
                                buttons.push('-');
                            }
                            buttons.push(this);
                        });
                    }
                    return buttons;
                }
            }, options));

            if(toolbar && $.isArray(toolbar) && toolbar.length > 0) {
                options.toolbar = toolbar;
            }

            var rowStyler = self.handle($.extend({
                handlerPrefix : 'rowStyler',
                handler : 'toggleClass',
                defaultHandle : function(options) {
                    return function (index, row) {
                        return 'cursor:pointer';
                    };
                }
            }, options));
            options.rowStyler = rowStyler;

            var loadFilter = self.handle($.extend({
                handlerPrefix : 'loadFilter',
                handler : 'toggleClass',
                defaultHandle : function(options) {
                    return function (data) {
                        if (data.success != undefined && !data.success && data.status == 200) {
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: data.msg,
                                icon: 'warning',
                                ok: function () {
                                    window.location.href = (jsCtx || '') + '/loginUI';
                                }
                            });
                            return {total : 0, rows : []};
                         } else if (data.success != undefined && !data.success){
                            $.dialog({
                                title: '消息',
                                width: 150,
                                content: data.msg,
                                icon: 'warning',
                                time: 3
                            });
                            return {total : 0, rows : []};
                         } else {
                            return data;
                         }
                     };
                 }
            }, options));
            options.loadFilter = loadFilter;

            if (options.showEditButton && options.onDblClickRowEditFlag) {
                options.onDblClickRow = function() {
                    editFun(options);
                };
            }
            if (!options.autoLoadDataFlag) {
                options.url = null;
            }

            if (options.type == 'treegrid') {
                options.pagination = false;
                this.grid = $('#' + options.datagridIdPrefix + options.className).treegrid(options);
            } else {
                this.grid = $('#' + options.datagridIdPrefix + options.className).datagrid(options);
                var pager = this.grid.datagrid('getPager');
                var button = {};
                if (!options.hideJumpButton) {
                    button = {
                        iconCls:'icon-redo',
                        text:'跳转到指定页',
                        handler: function() {
                            var pager = self.grid.datagrid('getPager');
                            var pageNum = $(this).parents("table").find(".pagination-num").val();
                            pager.pagination('select', parseInt(pageNum));
                        }
                    };
                }
                pager.pagination({
                    onSelectPage:function(pageNumber, pageSize){
                        $(this).pagination('loading');
                        //var loadOptions = util.serializeForm($('#' + options.searchFormIdPrefix + options.className));
                        var loadOptions = self.grid.datagrid('options').queryParams;
                        loadOptions.total = $(this).pagination('options').total;
                        loadOptions.page = pageNumber;
                        loadOptions.pageNumber = pageNumber;
                        loadOptions.pageSize = pageSize;
                        $(this).pagination('options').page = pageNumber;
                        $(this).pagination('options').rows = pageSize;

                        self.grid.datagrid('load', loadOptions);

                        $(this).pagination('refresh',{
                            total: loadOptions.total,
                            pageNumber: pageNumber
                        });

                        $(this).pagination('loaded');
                    },
                    buttons:[button]
                });
            }
        }
    }, $.base.myeasyui.prototype, $.base.myeasyui.defaults);

    /**
     * 以下是所有datagrid的公共配置,可被子类覆盖.
     */
    $.base_myeasyui.mydatagrid.defaults = {
        /*datagrid参数  begin*/
        url : '',   //一个URL从远程站点请求数据，必须被子类重写
        autoLoadDataFlag : true,  // 是否自动加载数据
        fitColumns : true,  //自动展开/收缩列的大小，以适应网格的宽度，防止水平滚动
        striped : true,  //是否显示斑马线效果
        nowrap : false,   //如果为true，则在同一行中显示数据
        pagination : true,  //如果为true，则在DataGrid控件底部显示分页工具栏
        pageNumber : 1,   //设置分页属性的时候初始化页码
        pageSize : 10,   //设置分页属性的时候初始化页面大小
        pageList : [10, 20, 30, 40, 50],  //设置分页属性的时候 初始化页面大小选择列表
        rownumbers : false,  //如果为true，则显示一个行号列
        singleSelect : true, //如果为true，则只允许选择一行
        ctrlSelect : true, //在启用多行选择的时候允许使用Ctrl键+鼠标点击的方式进行多选操作
        selectOnCheck : false,
        checkOnSelect : true,
        gridAddBtnDisabled : false,
        gridEditBtnDisabled : false,
        gridDetailButtonDisabled : false,
        gridDeleteBtnDisabled : false,
        gridExamineBtnDisabled : false,
        gridActivateBtnDisabled : false,
        gridExportBtnDisabled : false,
        gridHelpBtnDisabled : false,
        onDblClickRowEditFlag : true, //双击行编辑标识
        hideJumpButton : false, // 是否显示跳转页， false默认显示， true不显示
        /*datagrid参数  end*/

        /*业务参数  begin*/
        datagridIdPrefix : 'jq_datagrid_', //页面上datagrid标签id前缀
        addFormIdPrefix : 'jq_add_form_',
        editFormIdPrefix : 'jq_edit_form_',
        searchFormIdPrefix : 'jq_search_form_',
        searchBtnIdPrefix : 'jq_search_btn_',
        expBtnIdPrefix : 'jq_exp_btn_',
        entityPk : 'id',  //数据库中表的主键名，做编辑操作时使用
        showAddButton : false,
        showEditButton : false,
        showDetailButton : false,
        showDeleteButton : false,
        showExportButton : false,
        showExamineButton : false,
        showActivateButton : false,
        showHelpButton : false,
        examineStatus : 'examineStatus',  //审核状态字段名称
        examineStatusPass : '审核通过',  // 审核通过状态值
        activateStatus : 'activateStatus',  //激活状态字段名称
        activateStatusPass : '激活成功',  // 已激活状态值
        /*业务参数  end*/

        /* 弹出框参数 begin */
        dialogWidth : 400,
        dialogHeight : 600,
        dialogResizable : false  //窗口是否可以改变大小
        /* 弹出框参数 begin */
    };

    return $.base;
});
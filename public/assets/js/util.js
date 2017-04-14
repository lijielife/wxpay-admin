define(['jquery', 'easyui', 'art_dialog', 'art_dialog_iframe'], function($, easyui, artDialog) {
    return {
        isBoolean: function (obj) {
            return $.type(obj) == "boolean";
        },
        isString: function (obj) {
            return $.type(obj) == "string";
        },
        isDate: function (obj) {
            return $.type(obj) == "date";
        },
        isRegExp: function (obj) {
            return $.type(obj) == "regexp";
        },
        isObject: function (obj) {
            return $.type(obj) == "object";
        },
        isNumber: function (obj) {
            return $.type(obj) == "number";
        },
        serializeForm: function(form) {
            var o = {};
            $.each(form.serializeArray(), function(index) {
                if (this['value'] != undefined && this['value'].length > 0) {// 如果表单项的值非空，才进行序列化操作
                    if (o[this['name']]) {
                        o[this['name']] = o[this['name']] + "," + this['value'];
                    } else {
                        o[this['name']] = this['value'];
                    }
                }
            });
            return o;
        },
        createWindow: function(options) {
            var that = this;
            if (that.createWindow.handler == undefined) {// 避免重复弹出
                var opts = $.extend({
                    //zIndex : 1,
                    lock : true,
                    padding : 0,
                    title : '提示信息',
                    width : 600,
                    height : 480,
                    opacity : 0.3,
                    cache : false,
                    cancelVal : '关闭',
                    cancel : function() {
                        if ($('.jq_search_panel_class').length > 0) {
                            $('.jq_search_panel_class').remove();
                        }
                        that.createWindow.handler = undefined;
                        that.createWindow.openner_dataGrid = undefined;
                        that.createWindow.validForm = undefined;
                    },
                    close : function() {
                        $('.easyui-datetimebox,.easyui-combobox,.easyui-combotree,.easyui-combogrid,.easyui-datebox', $('#jq_through_box_' + options.className)).each(function() {
                            $(this).combo('destroy');
                        });
                    }
                }, options);
                var throughBox = $.dialog.through(opts);

                if (opts.href) {
                    $.ajax({
                        url: opts.href + (opts.href.indexOf('?') == -1 ? '?' : '&') + 'menuFlag=true',
                        dataType : 'html',
                        task: function (data, textStatus, jqXHR) {
                            throughBox.content('<div style="padding-top:10px;" id="jq_through_box_' + opts.className + '">' + data + '</div>');
                            $.parser.parse('#jq_through_box_' + opts.className);
                        },
                        cache: false
                    });
                }

                return that.createWindow.handler = throughBox;
            }
        }
    };
});
$.extend($.fn.form.defaults,{
	onSubmit:function(param) {
		winTop.$.messager.progress();
		//var isValid = $(this).form('validate');
		//var isValid = xj.modalDialog.validForm.check();
		if (this.submitBeforeTask) {
			this.submitBeforeTask.call(this, param);
		}
		var isValid = winTop.x$.createWindow.validForm.extCheck(false);
		if (!isValid) {
			winTop.$.messager.progress('close');
		}
		return isValid;
	},
	success:function(result) {
		winTop.$.messager.progress('close');
		try {
			result = $.parseJSON(result);
			if (result.success) {
				if (this.task) {
					this.task.call(this, result);
					return;
				}
				if(winTop.x$.createWindow.openner_dataGrid) winTop.x$.createWindow.openner_dataGrid.datagrid('reload');
				if(winTop.x$.createWindow.openner_treeGrid) {
					if (winTop.x$.createWindow.idField) {
						winTop.x$.createWindow.openner_treeGrid.treegrid('reload', winTop.x$.createWindow.idField);
					} else {
						winTop.x$.createWindow.openner_treeGrid.treegrid('reload');
					}
				}
				winTop.x$.createWindow.handler.close();
				winTop.x$.createWindow.handler = undefined;
				//winTop.$.messager.show('提示', result.msg, 'success');
				winTop.$.messager.show({title : '消息', msg : result.msg, timeout : 3000, showType : 'slide'});
			} else {
				//$.messager.alert('错误', result.msg, 'error');
				winTop.$.messager.show({title : '消息', msg : result.msg, timeout : 3000, showType : 'slide'});
			}
		} catch(e) {
			//$.messager.alert('错误', '服务器请求地址无响应！', 'error');
			winTop.$.messager.show({title : '消息', msg : '服务器请求地址无响应！', timeout : 3000, showType : 'slide'});
		}
	}
});

/**
 * 
 * 扩展tree，使其支持平滑数据格式
 */
$.fn.tree.defaults.loadFilter = function(data, parent) {
	var opt = $(this).data().tree.options;
	var idFiled, textFiled, parentField;
	if (opt.parentField) {
		idFiled = opt.idFiled || 'id';
		textFiled = opt.textFiled || 'text';
		parentField = opt.parentField;
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
	}
	return data;
};

/**
 * 
 * 扩展treegrid，使其支持平滑数据格式
 */
$.fn.treegrid.defaults.loadFilter = function(data, parentId) {
	var opt = $(this).data().treegrid.options;
	var idFiled, textFiled, parentField;
	if (opt.parentField) {
		idFiled = opt.idFiled || 'id';
		textFiled = opt.textFiled || 'text';
		parentField = opt.parentField;
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
	}
	return data;
};

/**
 * 
 * 扩展combotree，使其支持平滑数据格式
 */
$.fn.combotree.defaults.loadFilter = $.fn.tree.defaults.loadFilter;

/*$.fn.panel.defaults = $.extend({}, $.fn.panel.defaults, {
	onBeforeDestroy : function() {
		$(this).find(".combo-p").each(function() {
			var panel = $(this).data().combo.panel;
			panel.panel("destroy");
		});
	}
});*/

/**
 * 扩展表格列对齐属性：
 *      自定义一个列字段属性：
 *      headalign ：原始align属性针对数据有效, headalign针对列名有效
 *
 */
$.extend($.fn.datagrid.defaults,{
	onLoadSuccess : function(data) {
		var target = $(this);
		var opts = $.data(this, "datagrid").options;
		var panel = $(this).datagrid("getPanel");
		//获取列
		var fields=$(this).datagrid('getColumnFields',false);
		//datagrid头部 table 的第一个tr 的td们，即columns的集合
		var headerTds =panel.find(".datagrid-view2 .datagrid-header .datagrid-header-inner table tr:first-child").children();
		//重新设置列表头的对齐方式
		headerTds.each(function (i, obj) {
			var col = target.datagrid('getColumnOption',fields[i]);
			if (!col.hidden && !col.checkbox)
			{
				var headalign=col.headalign||col.align||'left';
				$("div:first-child", obj).css("text-align", headalign);
			}
		});
		
		/*if (data.total == 0) {
			var imgSrc = (jsCtx || '') + "/images/no_data.png";
			panel.find('.datagrid-body')
				.html('<div class="no_data" style="width:100%; text-align:center;"><div class="tips_wrap"><img src="' + imgSrc + '" width="140" height="140"><p>没有查到数据！</p></div></div>');
        }*/
	},
	onBeforeLoad : function (param) {
		var gridOpts = $(this).datagrid('options');
		var panel = $(this).datagrid("getPanel");
		if (gridOpts.url == null) {
			var imgSrc = (jsCtx || '') + "/images/no_data.png";
			panel.find('.datagrid-body')
				.html('<div class="no_data" style="width:100%; text-align:center;"><div class="tips_wrap"><img src="' + imgSrc + '" width="140" height="140"><p>请选择查询条件进行查询！</p></div></div>');
		}
		return true;
	}
});
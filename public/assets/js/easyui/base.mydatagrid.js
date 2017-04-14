(function($) {
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
				var loadParams = x$.serializeForm($('#' + options.searchFormIdPrefix + options.className));
				loadParams.pageSize = pagerOptions.pageSize;
				self.grid.datagrid('load', loadParams);
			});
		},
		'load_grid' : function(options) {
			var self = this;
			options = $.extend({}, this.options, options);
			var queryParams = self.handle({
								  handlerPrefix : 'queryParams', 
							      handler : 'toggleClass',
							      defaultHandle : function(options) {
								     return {};
							      }
							  });
			options.queryParams = queryParams;
			
			var addFun = self.handle($.extend({
							 handlerPrefix : 'addFun', 
							 handler : 'toggleClass',
							 self : self,
							 defaultHandle : function(opts) {
								 return function (options) {
									 winTop.x$.createWindow($.extend({
										 title : '添加' + (options.dialogTitle || ''),
										 href : options.forwardAddUrl,
										 width : options.dialogWidth,
										 height : options.dialogHeight,
										 resizable : options.dialogResizable,
										 button : [ {
											 name : '保存',
											 focus : true,
											 callback : function() {
												 winTop.x$.createWindow.openner_dataGrid = self.grid;
												 var addForm = winTop.x$.createWindow[options.addFormIdPrefix + options.className];
												 if (addForm) {
													 addForm.submitForm();
												 } else {
													 winTop.$.dialog({
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
										 winTop.x$.createWindow($.extend({
											 title : '编辑' + (options.dialogTitle || ''),
											 href : options.forwardEditUrl + '?' + options.entityPk + '=' + id,
											 width : options.editDialogWidth || options.dialogWidth,
											 height : options.editDialogHeight || options.dialogHeight,
											 resizable : options.dialogResizable,
											 button : [ {
												 name : '编辑',
												 focus : true,
												 callback : function() {
													 winTop.x$.createWindow.openner_dataGrid = self.grid;
													 var editForm = winTop.x$.createWindow[options.editFormIdPrefix + options.className];
													 if (editForm) {
														 editForm.submitForm();
													 } else {
														 winTop.$.dialog({
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
										 winTop.$.dialog({
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
						 var selectRow = self.grid.datagrid('getSelected');
						 if (selectRow) {
							 var id = selectRow[options.entityPk];
							 winTop.x$.createWindow($.extend({
								 title : '详情',
								 href : options.forwardDetailUrl + '?' + options.entityPk + '=' + id,
								 width : options.editDialogWidth || options.dialogWidth,
								 height : options.editDialogHeight || options.dialogHeight,
								 resizable : options.dialogResizable
							 }, options));
						 } else {
							 winTop.$.dialog({
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
									 var selectRows = self.grid.datagrid('getChecked');
									 if (selectRows && selectRows.length > 0) {
										 $.dialog.confirm('您是否要废弃当前当前选中的记录？', function() {
											 var ids = [];
											 $.each(selectRows, function(i) {
												 ids.push(selectRows[i][options.entityPk]);
											 });
											 $.post(options.deleteUrl, {
												 ids : ids
											 }, function(result) {
												 if (result.success) {
													 winTop.$.dialog({
															title: '消息',
														    width: 150,
														    content: result.msg,
														    icon: 'face-smile',
														    time: 3
														});
													 self.grid.datagrid('reload');
												 } else {
													 winTop.$.dialog({
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
									    winTop.$.dialog({
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
			   winTop.x$.createWindow.openner_dataGrid = self.grid;
				 var editForm = winTop.x$.createWindow[options.editFormIdPrefix + options.className];
				 if (editForm) {
					 editForm.submitForm();
				 } else {
					 winTop.$.dialog({
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
									 var selectRow = self.grid.datagrid('getSelected');
									 if (selectRow) {
										 var id = selectRow[options.entityPk];
										 var examineStatus = selectRow[options.examineStatus];
										 if (examineStatus && examineStatus == options.examineStatusPass) {
											 winTop.$.dialog({
													title: '消息',
												    width: 150,
												    content: '审核通过的记录不能再次审核!',
												    icon: 'warning',
												    time: 3
												});
											 return false;
										 }
										 winTop.x$.createWindow($.extend({
											 title : '审核' + (options.dialogTitle || ''),
											 href : options.forwardExamineUrl + '?' + options.entityPk + '=' + id,
											 width : options.dialogWidth,
											 height : options.dialogHeight,
											 resizable : options.dialogResizable,
											 button : [ {
												 name : '审核通过',
												 focus : true,
												 callback : function() {
													 $('#' + options.editFormIdPrefix + 'examineStatus').val(1);
													 return examineFunCallback(options);
												 }
											 },
											 {
												 name : '审核不通过',
												 focus : false,
												 callback : function() {
													 $('#' + options.editFormIdPrefix + 'examineStatus').val(2);
													 return examineFunCallback(options);
												 }
											 } ]
										 }, options));
									 } else {
									    winTop.$.dialog({
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
			   winTop.x$.createWindow.openner_dataGrid = self.grid;
				 var editForm = winTop.x$.createWindow[options.editFormIdPrefix + options.className];
				 if (editForm) {
					 editForm.submitForm();
				 } else {
					 winTop.$.dialog({
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
						 var selectRow = self.grid.datagrid('getSelected');
						 if (selectRow) {
							 var id = selectRow[options.entityPk];
							 var activateStatus = selectRow[options.activateStatus];
							 /*if (activateStatus && activateStatus == options.activateStatusPass) {
								 winTop.$.dialog({
										title: '消息',
									    width: 150,
									    content: '已激活的记录不能再次审核!',
									    icon: 'warning',
									    time: 3
									});
								 return false;
							 }*/
							 winTop.x$.createWindow($.extend({
								 title : '激活' + (options.dialogTitle || ''),
								 href : options.forwardActivateUrl + '?' + options.entityPk + '=' + id,
								 width : options.dialogWidth,
								 height : options.dialogHeight,
								 resizable : options.dialogResizable,
								 button : [ {
									 name : '激活通过',
									 focus : true,
									 callback : function() {
										 $('#' + options.editFormIdPrefix + options.className + '_activateStatus').val(1);
										 return activateFunCallback(options);
									 }
								 },
								 {
									 name : '激活不通过',
									 focus : false,
									 callback : function() {
										 $('#' + options.editFormIdPrefix + options.className + '_activateStatus').val(2);
										 return activateFunCallback(options);
									 }
								 },
								 {
									 name : '冻结',
									 focus : false,
									 callback : function() {
										 $('#' + options.editFormIdPrefix + options.className + '_activateStatus').val(4);
										 return activateFunCallback(options);
									 }
								 } ]
							 }, options));
						 } else {
						    winTop.$.dialog({
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
							 winTop.x$.createWindow($.extend({
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
											 winTop.$.dialog({
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
											 winTop.$.dialog({
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
			this.grid = $('#' + options.datagridIdPrefix + options.className).datagrid(options);
			var pager = this.grid.datagrid('getPager');
			var button = {};
			if ( !options.hideJumpButton ) {
				button = {
					iconCls:'icon-redo',
					text:'跳转到指定页',
					handler:function(){
						var pager = self.grid.datagrid('getPager');
						var pageNum = $(this).parents("table").find(".pagination-num").val();
						pager.pagination('select', parseInt(pageNum));
					}
				};
			}
		    pager.pagination({
		    	onSelectPage:function(pageNumber, pageSize){
		    		$(this).pagination('loading');
		    		//var loadOptions = x$.serializeForm($('#' + options.searchFormIdPrefix + options.className));
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

})(jQuery);
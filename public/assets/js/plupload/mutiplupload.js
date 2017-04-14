define(['jquery', 'underscore', 'plupload'], function($, _, plupload) {
    $.fn.mutiupload = function (options) {
        var settings = {
            flash_swf_url: '/assets/js/plupload/Moxie.swf',
            silverlight_xap_url : '/assets/js/plupload/Moxie.xap',
            multi_selection: false,
            filters: {
                max_file_size : '2mb',
                prevent_duplicates : true,
                mime_types : [
                    { title : "图片文件", extensions : "jpg,gif,png,bmp" }
                ]
            },
            preview: {width: 170, height:120}
        };
        jQuery.extend(settings, options);

        this.each(function () {
            var uploader = new plupload.Uploader(settings);

            if (settings.success) {
                uploader.bind('FileUploaded', function (uploader, file, result) {
                    var res = result.response;
                    var args = [uploader, file, res, $(uploader.getOption('browse_button'))];
                    try {
                        var data = $.parseJSON(res);
                        if (data != null) {
                            args[2] = data;
                        }
                    }
                    catch (e) { }
                    settings.success.apply(uploader, args);
                });
            }

            if (settings.error) {
                uploader.bind('Error', function (uploader, error) {
                    settings.error(error.message);
                });
            } else {
                uploader.bind('Error', function (uploader, error) {
                    var data = $.parseJSON(error.response);
                    $.dialog({
                        title: '消息',
                        width: 150,
                        content: data.msg,
                        icon: 'face-sad',
                        time: 3
                    });
                });
            }

            uploader.bind('FilesAdded', function (uploader, files) {
                var item = $(uploader.getOption('browse_button'));
                var itemId = item.attr('id');
                for (var i = 0, len = files.length; i<len; i++) {
                    // 如果当前已经存在图片，则销毁
                    _.each(uploader.files, function(val, key, list){
                        if (typeof val != 'undefined' && val.id != files[i].id && val.item_id == itemId) {
                            uploader.removeFile(val);
                        }
                    });

                    !function(i) {
                        previewImage(files[i], function(imgsrc) {
                            files[i].item_id = itemId;
                            var img = '<img src="'+ imgsrc +'" width="'+settings.preview.width+'" height="'+settings.preview.height+'"/>';
                            item.prev().html(img);
                            if (itemId.indexOf('idcard1') != -1) {
                                $('#jq_jq_upload_indentityPhoto_img_div').html(img);
                            } else if(itemId.indexOf('protocolPhoto1') != -1) {
                                $('#jq_jq_upload_protocolPhoto_img_div').html(img);
                            }
                        })
                    }(i);
                }
                uploader.start();
            });

            uploader.setOption('browse_button', this.id);
            uploader.init();
            uploader.refresh();
        });

        function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
            if (!file || !/image\//.test(file.type)) return; //确保文件是图片

            if (file.type=='image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
                var fr = new mOxie.FileReader();
                fr.onload = function(){
                    callback(fr.result);
                    fr.destroy();
                    fr = null;
                }
                fr.readAsDataURL(file.getSource());
            } else {
                var preloader = new mOxie.Image();
                preloader.onload = function() {
                    preloader.downsize(settings.preview);//先压缩一下要预览的图片

                    var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg',90) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                    callback && callback(imgsrc); //callback传入的参数为预览图片的url
                    preloader.destroy();
                    preloader = null;
                };
                preloader.load(file.getSource());
            }
        }
    };

});
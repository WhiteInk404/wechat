var AppMediaImage = {

    option: {},

    tpl: {
        oneImageEditTpl: $('#one-image-edit-tpl').text()
    },

    bindUpload: function () {
        var uploader = WebUploader.create({
            //选择文件后，是否自动上传。
            auto: true,
            //swf文件路径
            swf: '/assets/js/plugins/webuploader/Uploader.swf',
            //文件接收服务端
            server: '/admin/wechat/media/image/upload',
            //选择文件的按钮。可选
            //内部根据当前运行是创建，可能是input元素，也可能是flash，
            pick: '#picker',
            //不压缩image，默认是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            //只允许选择图片
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeType: 'image/*'
            }
        });
        uploader.on('uploadBeforeSend', function(block, data, headers){
            $.extend(headers, { 'X-CSRF-TOKEN': $('meta[name=_token]').attr('content') });
        });
        uploader.on('uploadSuccess', function (file, response) {
            if (response.error_code > 0) {
                noty({
                    text: response.error_message,
                    type: 'error',
                    timeout: 2000
                });
            } else {
                noty({
                    text: '上传成功',
                    type: 'success',
                    timeout: 2000,
                    callback: {
                        afterClose: function () {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    },

    selectChange: function() {
        $('[select_search]').on('change', function() {
            var mcid = $('[select_search]').val();
            var type = $('[select_search]').data('type');
            var keyword = $('#keyword').val();
            window.location.href = '/admin/wechat/media/'+type+'?mcid=' + mcid+'&keyword='+keyword;
        });
    },
    search: function() {
        $('[btn_search]').on('click', function () {
            var mcid = $('[select_search]').val();
            var type = $('[select_search]').data('type');
            var keyword = $('#keyword').val();
            window.location.href = '/admin/wechat/media/'+type+'?mcid=' + mcid+'&keyword='+keyword;
        })
    },

    remove: function() {
        $('[hw_remove]').on('click', function(e) {
            var current = $(this);
            var id = $(e.currentTarget).data('id');
            var type = $(e.currentTarget).data('type');
            bootbox.confirm('确定删除吗？', function (result) {
                if (result) {
                    var postData = {
                        id: id
                    };
                    $.ajax({
                        "url": "/admin/wechat/media/"+type+"/"+id,
                        "dataType": "json",
                        "type": "delete",
                        success: function (data) {
                            if (data.error_code == 0) {
                                current.parents('.delete').remove();
                                noty({
                                    text: '删除成功',
                                    type: 'success',
                                    timeout: 2000
                                });
                            } else {
                                noty({
                                    text: data.error_message,
                                    type: 'error',
                                    timeout: 2000
                                });
                            }
                        }, error: function (data) {
                            noty({
                                text: data.error_message,
                                type: 'error',
                                timeout: 2000
                            });
                        }
                    });
                }
            });
        });
    },

    edit: function () {
        var self = this;
        $('[hw_edit]').on('click', function(e) {
            var id = $(e.currentTarget).data('id');
            var name = $(e.currentTarget).data('name');
            var mcid = $(e.currentTarget).data('mcid');
            self.showEditImageDialog({
                data: {
                    id: id,
                    name: name,
                    mcid: mcid
                },
                callback: function(postData) {
                    $.post('/admin/wechat/media/image/', postData, function(data) {
                        if (data.error_code) {
                            noty({
                                text: data.error_message,
                                type: 'error',
                                timeout: 2000
                            });
                        } else {
                            noty({
                                text: '编辑成功',
                                type: 'success',
                                timeout: 2000,
                                callback: {
                                    afterClose: function () {
                                        window.location.reload();
                                    }
                                }
                            });
                        }
                    }, 'json');
                }
            });
        });
    },

    showEditImageDialog: function (options) {
        var self = this;
        var dialog = bootbox.dialog({
            message: self.tpl.oneImageEditTpl,
            title: "图片编辑",
            buttons: {
                subimt: {
                    label: "确定",
                    className: "btn btn-primary",
                    callback: function() {
                        $('#one_image_edit_form').submit();
                        return false;
                    }
                },
                cancle: {
                    label: "取消",
                    className: "btn btn-default",
                    callback: function() {
                        bootbox.hideAll();
                    }
                }
            }
        });

        dialog.on('shown.bs.modal', function() {
            $('#id').val(options.data.id);
            $('#name').val(options.data.name);
            var selectKey = '#mcid [value=' + options.data.mcid + ']';
            $(selectKey).prop('selected', true);
            $.extend($.validator.defaults, {
                invalidHandler: function (event, validator) {
                    return false;
                },
                submitHandler: function (form) {
                    var postData = $(form).serialize();
                    if ($.isFunction(options.callback)) {
                        options.callback(postData);
                    }
                    bootbox.hideAll();
                    return false;
                }
            });
            $("#one_image_edit_form").validate();
        });
    },

    init: function(options) {
        bootbox.setLocale('zh_CN');
        this.bindUpload();
        this.remove();
        this.selectChange();
        this.search();
        this.edit();
    }
};
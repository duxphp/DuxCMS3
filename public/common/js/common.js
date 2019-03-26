/**
 * 页面框架
 * var 1.0
 */
window.initStats = 0;
(function ($, owner) {
    /**
     * 初始化自动绑定
     */
    owner.init = function () {
        //处理绑定组件
        if (window['initStats']) {
            return false;
        }
        $("[data-dux]").each(function () {
            var data = $(this).data(),
                name = data['dux'],
                names = name.split('-', 2);
            if (window[names[0]][names[1]] && typeof (window[names[0]][names[1]]) == "function") {
                window[names[0]][names[1]](this, data);
            } else {
                app.debug(names[0] + '组件中不存在' + names[1] + '方法!');
            }

        });
        if (window['windowAfter'] != undefined && window['windowAfter'] != '') {
            window['windowAfter']();
        }
        window.initStats = 1;
    };

    /**
     * 自定义绑定
     */
    owner.bind = function ($el) {
        $($el).find("[data-dux]").each(function () {
            var data = $(this).data(),
                name = data['dux'],
                names = name.split('-', 2);
            window[names[0]][names[1]](this, data);
        });
    };

}(jQuery, window.dux = {}));


(function ($, owner) {

    var dialogObj = [];
    /**
     * 加载提示
     * @param msg
     */
    owner.loading = function (msg, status) {
        var defaultConfig = {
            msg: '加载中...',
            status: true
        };
        var config = $.extend(defaultConfig, {
            msg: msg,
            status: status
        });
        var html = '<div class="dux-loading"><div class="loading-mask"></div><div class="loading-body"><div class="loading-icon"></div><div class="loading-msg">' + config.msg + '</div></div></div>';
        if (config.status) {
            if ($('.dux-loading').length) {
                return false;
            }
            $(html).appendTo('body');
        } else {
            setTimeout(function () {
                $('.dux-loading').remove();
            }, 100);
        }

    };
    /**
     * 消息提示
     * @param msg
     */
    owner.msg = function (msg, time) {
        var defaultConfig = {
            msg: '加载中',
            time: 3
        };
        var config = $.extend(defaultConfig, {
            msg: msg,
            time: time
        });
        var html = '<div class="dux-msg-layout"><div class="dux-msg"><div class="text">' + config.msg + '</div></div></div>';
        if ($('.dux-msg-layout').length) {
            $('.dux-msg-layout').remove();
        }
        $(html).appendTo('body');
        setTimeout(function () {
            $('.dux-msg-layout').remove();
        }, config.time * 1000);
    };
    /**
     * AJAX确认
     * @param $el
     */
    owner.ajax = function ($el) {
        $($el).click(function () {
            var data = $(this).data();
            owner.confirm({
                title: data.title,
                callback: [function () {
                    app.ajax({
                        url: data.url,
                        type: 'post',
                        data: data.params,
                        success: function (msg, url) {
                            var callback = data.callback;
                            if (callback != undefined && callback != '') {
                                window[callback](msg, url);
                            } else {
                                location.reload();
                            }
                        },
                        error: function (msg, url) {
                            app.error(msg, url);
                        }
                    });
                }]
            });
        });
    };

    /**
     * 询问窗口
     * @param config
     */
    owner.confirm = function (config) {
        var defaultConfig = {
            title: '询问',
            btn: ['确认', '取消'],
            callback: []
        };
        config = $.extend(defaultConfig, config);
        UIkit.modal.confirm(config.title, {
            labels: {
                ok: config.btn[0],
                cancel: config.btn[1]
            }
        }).then(config.callback[0], config.callback[1]);
    };


    /**
     * 打开窗口
     * @param $el
     * @param config
     */
    owner.open = function ($el, config) {
        var defaultConfig = {
            width: '',
            height: '400px'
        };
        config = $.extend(defaultConfig, config);

        var open = function (url, height) {
            var modal = UIkit.modal.dialog('<button class="uk-modal-close-default" type="button" uk-close></button>\n' +
                '        <div class="uk-modal-header">\n' +
                '            <h2 class="uk-modal-title">' + config.title + '</h2>\n' +
                '        </div>' +
                '<iframe src="' + url + '" width="100%" height="' + height + '" frameborder="0"></iframe>', {
                    cls: config.width ? 'uk-open uk-modal-container' : 'uk-open'
                }
            );
            dialogObj.push(modal);
        };

        if ($el) {
            $($el).on('click', function () {
                open(config.url, config.height);
            });
        } else {
            open(config.url, config.height);
        }
    };

    /**
     * 关闭窗口
     */
    owner.close = function () {
        for (var i in dialogObj) {
            dialogObj[i].hide();
        }
        $('.dux-msg-layout').remove();
    };

    /**
     * 确认对话框
     * @param config
     */
    owner.alert = function (config) {
        UIkit.modal.alert(config.title, {
            labels: {
                ok: config.btn ? config.btn : '确认'
            }
        }).then(function () {
            if (typeof config.callback == 'function') {
                config.callback();
            }
        });
    };

}(jQuery, window.dialog = {}));


/**
 * 表单操作
 */
(function ($, owner) {
    /**
     * 绑定AJAX提交
     */
    owner.bind = function ($el, config) {
        var defaultConfig = {
            advanced: true
        };
        config = $.extend(defaultConfig, config);
        Do('form', function () {
            var options = {
                dataType: 'json',
                beforeSubmit: function () {
                    $($el).find("button[type=submit]").prepend('<i class="fa fa-circle-o-notch fa-spin"></i> ');
                    $($el).find("button").attr("disabled", true);
                },
                uploadProgress: function (event, position, total, percentComplete) {
                },
                complete: function () {
                    $($el).find("button").attr("disabled", false);
                    $($el).find("button[type=submit]").find('i:first-child').remove();
                },
                success: function (json) {
                    var msg = json.message;
                    var url = json.url;
                    //成功回调
                    if (typeof config.callback === 'function') {
                        config.callback(msg, url);
                        return;
                    }
                    if (typeof config.callback === 'string') {
                        window[config.callback](msg, url);
                        return;
                    }
                    //执行弹窗
                    if (url) {
                        if (config.advanced) {
                            dialog.confirm({
                                title: msg,
                                btn: ['返回', '继续'],
                                callback: [function () {
                                    window.location.href = url;
                                }, function () {
                                    location.reload();
                                }]
                            });
                        } else {
                            dialog.alert({
                                title: msg,
                                callback: function () {
                                    window.location.href = url;
                                }
                            });
                        }
                    } else {
                        if (config.advanced) {
                            notify.success({
                                content: msg
                            });
                        } else {
                            dialog.alert({
                                title: msg,
                                callback: function () {
                                    location.reload();
                                }
                            });
                        }
                    }
                },
                error: function (e) {
                    var json = eval('(' + e.responseText + ')'), msg = json.message, url = json.url;
                    if (config.advanced) {
                        notify.error({
                            content: msg
                        });
                    } else {
                        app.error(msg, url);
                    }
                }
            };
            $($el).ajaxForm(options);
            $($el).find("button").attr("disabled", false);
        });
    };


    /**
     * 时间日期
     * @param $el
     * @param config
     */
    owner.date = function ($el, config) {
        Do('date', function () {
            var defaultConfig = {
                elem: $el,
            };
            config = $.extend(defaultConfig, config);
            laydate.render(config);
        });
    };

    /**
     * 地区选择
     * @param $el
     * @param config
     */
    owner.location = function ($el, config) {
        var defaultConfig = {};
        config = $.extend(defaultConfig, config);
        Do('distpicker', function () {
            $($el).distpicker(config);
        });
    };

    /**
     * 编辑器
     * @param $el
     * @param config
     */
    owner.editor = function ($el, config) {
        var defaultConfig = {
            height: 550,
            remoteLabel: 'duxup_',
            url: rootUrl + '/' + roleName + '/system/Upload/editor',
            remoteUrl: rootUrl + '/' + roleName + '/system/Upload/remote',
        };
        config = $.extend(defaultConfig, config);
        Do('editor', function () {
            tinymce.init({
                target: $($el)[0],
                images_upload_handler: function (blobInfo, success, failure) {
                    const data = new FormData();
                    data.append('upload', blobInfo.blob());
                    data.append('allowSize', 100);
                    $.ajax({
                        url: config.url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: data => {
                            if (!data.error) {
                                success(data.url);
                            } else {
                                failure(data.message);
                                reject(data.message);
                            }
                        }
                    });
                },
                init_instance_callback: function (editor) {
                    editor.on('Change', function (e) {
                        $($el).val(editor.getContent());
                    });
                },
                setup: function (ed) {
                    ed.ui.registry.addButton('xiumi', {
                        text: '秀米',
                        onAction: function () {
                            dialog.open('', {
                                width: '1080px',
                                height: '600px',
                                title: '秀米编辑器',
                                url: rootUrl + '/public/common/js/package/xiumi/index.html',
                            });
                        }
                    })
                    ed.ui.registry.addButton('multipleUpload', {
                        text: '上传',
                        onAction: function () {
                            owner.fileManage({
                                multiple: true,
                                callback: function (file) {
                                    var editor = tinymce.activeEditor;
                                    switch (file.ext) {
                                        case 'jpg':
                                        case 'png':
                                        case 'bmp':
                                        case 'jpeg':
                                        case 'gif':
                                            editor.insertContent('<img src="' + file.url + '" alt="' + file.title + '" />');
                                            break;
                                        case 'wmv':
                                        case 'mp4':
                                        case 'flv':
                                        case 'ogg':
                                        case 'avi':
                                        case 'mpg':
                                        case 'mp3':
                                        case 'wav':
                                            editor.insertContent('<video src="' + file.url + '" controls="controls">' + file.title + '</video>');
                                            break;
                                        default:
                                            editor.insertContent('<a href="' + file.url + '">' + file.title + '</a>');
                                    }
                                }
                            });
                        }
                    })
                },
                plugins: [
                    'advlist autolink link image lists preview hr pagebreak ',
                    'searchreplace visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                    'save table directionality paste'
                ],
                height: config.height,
                toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link image | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | xiumi multipleUpload',
                language: 'zh_CN',
                paste_webkit_styles: 'all',
                paste_retain_style_properties: 'all',
                paste_word_valid_elements: '*[*]',
                paste_convert_word_fake_lists: false,
                paste_data_images: true,
                convert_urls: false,
                paste_preprocess: function (plugin, args) {
                    var editor = tinymce.activeEditor;
                    editor.notificationManager.open({
                        text: '粘贴处理中，请稍等...',
                        type: 'error',
                    });
                    var str = args.content, arr = [];
                    args.content = '';
                    var reg = /<img [^>]*src=['"]([^'"]+)[^>]*>/gi;
                    while (tem = reg.exec(str)) {
                        arr.push(tem[1]);
                    }
                    var reg = /url\s*\(([^\)]+)\)/gim;
                    var reg2 = new RegExp('("|\'|&quot;)', "g");
                    while (tem = reg.exec(str)) {
                        var value = tem[1];
                        value = value.replace(reg2, '');
                        arr.push(value);
                    }

                    var files = [];
                    for (var i in arr) {
                        var url = arr[i];
                        if (url.indexOf(this.remoteDomain) < 0 && (url.indexOf('http://') >= 0 || url.indexOf('https://') >= 0)) {
                            files.push(url);
                        }
                    }

                    if (files.length <= 0) {
                        editor.notificationManager.close();
                        editor.insertContent(str);
                        return true;
                    }

                    if (files.length > 0) {
                        $.ajax({
                            url: config.remoteUrl,
                            type: 'POST',
                            data: {
                                files: files,
                                label: config.remoteLabel
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.error == 1) {
                                    editor.insertContent(str);
                                    editor.notificationManager.close();
                                    editor.notificationManager.open({
                                        text: data.message,
                                        type: 'error',
                                        timeout: 2000
                                    });
                                    return;
                                }
                                for (var i in files) {
                                    var file = files[i];
                                    file = file.replace(/\//g, "\\/");
                                    file = file.replace(/\./g, "\\.");
                                    file = file.replace(/\?/g, "\\?");
                                    file = file.replace(/\-/g, "\\-");
                                    file = file.replace(/\=/g, "\\=");
                                    var reg = new RegExp(file, "g");
                                    str = str.replace(reg, data.files[i]);
                                }
                                editor.insertContent(str);
                                editor.setMode('design');
                                editor.notificationManager.close();
                                editor.notificationManager.open({
                                    text: '图片保存成功！',
                                    type: 'success',
                                    timeout: 2000
                                });
                            },
                            error: function (msg) {
                                editor.insertContent(str);
                                editor.notificationManager.close();
                                editor.notificationManager.open({
                                    text: '图片保存失败！',
                                    type: 'error',
                                    timeout: 2000
                                });
                            }
                        });

                    }
                }
            });
        });
    };

    owner.fileManage = function (config) {
        var defaultConfig = {
            url: rootUrl + '/' + roleName + '/system/Upload/attach',
            target: '',
            multiple: false,
            type: 'all',
            callback: {}
        };
        config = $.extend(defaultConfig, config);

        var insertList = [];
        var dialog = null;
        var curPage = 1;
        var maxPage = 0;

        var html = '<div><div class="uk-modal-dialog">\n' +
            '        <div class="uk-modal-header uk-flex" style=" padding: 10px">\n' +
            '            <div class="uk-flex-1 uk-form  uk-form-inline" style="padding-top: 0;">\n' +
            '                <div class="uk-form-group">\n' +
            '                    <select class="uk-select" data-type>\n' +
            '                        <option value="all" selected>全部附件</option>\n' +
            '                        <option value="image">图片</option>\n' +
            '                        <option value="media">媒体</option>\n' +
            '                        <option value="document">文档</option>\n' +
            '                        <option value="other">其他</option>\n' +
            '                    </select>\n' +
            '                </div>\n' +
            '                <div class="uk-form-group">\n' +
            '                    <button type="button" data-search class="uk-button uk-button-primary"><i class="fa fa-search"></i></button>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '            <div>\n' +
            '                <button type="button" class="uk-button uk-button-primary" data-upload>上传</button>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '        <div class="dux-attach-box"><div class="uk-padding uk-text-center" style="width: 100%">加载中...</div></div>\n' +
            '        <div class="uk-modal-footer uk-flex" style=" padding: 10px">\n' +
            '            <div class="uk-flex-1">\n' +
            '                <button type="button" class="uk-button uk-button-default" data-page data-page-prev>上一页</button>\n' +
            '                <button type="button" class="uk-button uk-button-default" data-page data-page-next>下一页</button>\n' +
            '            </div>\n' +
            '            <div>\n' +
            '                <a href="JavaScript:;" class="uk-button uk-button-success" data-complete>确定</a>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div></div>';

        var item = '<div class="attach-item"><div class="item-image"><img src=""></div><div class="item-title"><div></div></div></div>';

        var attachData = function (callback) {
            insertList = [];
            app.ajax({
                url: config.url,
                data: {
                    page: curPage,
                    type: config.type,
                },
                loading: true,
                success: function (info) {
                    var data = info.list;
                    maxPage = info.pageData.page;
                    dialog.find('.dux-attach-box').html('');
                    if (data.length <= 0) {
                        dialog.find('.dux-attach-box').html('<div class="uk-padding uk-text-center" style="width: 100%">没有该类型文件</div>');
                    } else {
                        for (var i in data) {
                            var itemObj = $(item);
                            itemObj.find('.item-image img').attr('src', data[i].src);
                            itemObj.find('.item-image img').data('url', data[i].url);
                            itemObj.find('.item-image img').data('ext', data[i].ext);
                            itemObj.find('.item-image img').attr('title', data[i].title + '.' + data[i].ext);
                            itemObj.find('.item-title').text(data[i].title + '.' + data[i].ext);
                            itemObj.find('.item-title').attr('title', data[i].title + '.' + data[i].ext);
                            itemObj.find('.item-title').data('title', data[i].title);
                            dialog.find('.dux-attach-box').append(itemObj);
                        }
                    }
                    if (callback) {
                        callback();
                    }
                }
            });
        };

        var init = function () {
            dialog = $(html);
            curPage = 1;
            $(dialog).on('hidden', function () {
                $(dialog).remove();
            });
            $(dialog).find('[data-type]').val(config.type);
            if (config.type !== 'all') {
                $(dialog).find('[data-type]').attr('disabled', true);
            }
            $(dialog).on('click', '.attach-item', function () {
                var url = $(this).find('img').data('url');
                var ext = $(this).find('img').data('ext');
                var title = $(this).find('.item-title').data('title');
                var itemJson = { url: url, title: title, ext: ext };
                if (config.multiple) {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        var index = $.inArray(itemJson, insertList);
                        insertList.splice(index, 1);
                    } else {
                        $(this).addClass('active');
                        insertList.push(itemJson);
                    }
                } else {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        insertList = [];
                    } else {
                        $(this).parents('.dux-attach-box').find('.active').removeClass('active');
                        $(this).addClass('active');
                        insertList = [itemJson];
                    }
                }
            });
            $(dialog).on('click', '[data-complete]', function () {
                for (var i in insertList) {
                    if (config.target) {
                        $(config.target).val(insertList[i].url);
                    }
                    if (typeof config.callback === 'function') {
                        config.callback(insertList[i]);
                    }
                    if (typeof config.callback === 'string' && config.callback) {
                        window[config.callback](insertList[i]);
                    }
                    UIkit.modal(dialog).hide();
                }
            });
            $(dialog).on('click', '[data-page-prev]', function () {
                var page = curPage - 1;
                curPage = page > 0 ? page : 1;
                $(dialog).find('[data-page]').attr('disabled', false);
                if (curPage <= 1) {
                    $(dialog).find('[data-page-prev]').attr('disabled', true);
                } else {
                    $(dialog).find('[data-page-prev]').attr('disabled', false);
                }
                attachData();
            });
            $(dialog).on('click', '[data-page-next]', function () {
                var page = curPage + 1;
                curPage = page < maxPage ? page : maxPage;
                $(dialog).find('[data-page]').attr('disabled', false);
                if (curPage >= maxPage) {
                    $(dialog).find('[data-page-next]').attr('disabled', true);
                } else {
                    $(dialog).find('[data-page-next]').attr('disabled', false);
                }
                attachData();
            });
            $(dialog).on('click', '[data-search]', function () {
                curPage = 0;
                config.type = $(dialog).find('[data-type]').val();
                attachData();
            });
            attachData(function () {
                var type = '*';
                if (config.type == 'image') {
                    type = 'jpg,png,bmp,jpeg,gif';
                }
                if (config.type == 'media') {
                    type = 'wmv,mp4,flv,ogg,avi,mpg,mp3,wav';
                }
                if (config.type == 'document') {
                    type = 'doc,docx,xls,xlsx,pptx,ppt,csv';
                }
                form.upload($(dialog).find('[data-upload]'), {
                    type: type,
                    callback: function () {
                        curPage = 0;
                        attachData();
                    }
                });
                UIkit.modal(dialog).show();
            });
        };
        init();
    };


    owner.ckeditor = function ($el, config) {
        var defaultConfig = {
            height: 550
        };
        config = $.extend(defaultConfig, config);
        var html = $($el).html();
        $($el).addClass('dux-editor');
        $($el).html('');
        Do('ckeditor', function () {
            class UploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    return new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', this.loader.file);
                        data.append('allowSize', 100);
                        $.ajax({
                            url: rootUrl + '/' + roleName + '/system/Upload/editor',
                            type: 'POST',
                            data: data,
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            success: data => {
                                if (!data.error) {
                                    resolve({
                                        default: data.url
                                    });
                                } else {
                                    reject(data.message);
                                }
                            }
                        });
                    });
                }
            }

            $($el).append('<div class="toolbar-container"></div>');
            $($el).append('<div class="content-container"><div class="content-editor">' + html + '</div><textarea name="' + config.name + '" rows="10" style="display: none">' + html + '</textarea></div>');
            DecoupledEditor.create($el.querySelector('.content-editor'), {
                autosave: {
                    save(editor) {
                        return $($el).find('textarea').val(editor.getData());
                    }
                }
            }).then(editor => {
                $($el).find('.toolbar-container').prepend(editor.ui.view.toolbar.element);
                editor.ui.view.editable.editableElement.style.minHeight = config.height + 'px';
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new UploadAdapter(loader, editor);
                };
                //window['duxEditor'][config.name] = editor;
            }).catch(err => {
                console.error(err);
            });
        });
    };

    /**
     * tag输入组件
     */
    owner.tags = function ($el, config) {
        var defaultConfig = {};
        config = $.extend(defaultConfig, config);
        Do('tags', function () {
            $($el).tagsInput(config);
        });
    };

    /**
     * 下拉选择
     */
    owner.select = function ($el, config) {
        var defaultConfig = {
            language: "zh-CN"
        };
        config = $.extend(defaultConfig, config);
        Do('select2', function () {
            $.fn.select2.defaults.set("theme", "uikit");
            if (config.search) {
                var ajaxConfig = {
                    ajax: {
                        url: config.url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term,
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                        cache: false
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 2,
                    templateResult: function (repo) {
                        if (repo.loading) {
                            return repo.value || repo.text;
                        }
                        var markup = "<div class='select2-result-repository uk-clearfix'>" +
                            "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>" + repo.text + "</div>";
                        markup += "<div class='select2-result-repository__description'>" + repo.desc ? repo.desc : '' + "</div>";
                        markup += "</div></div>";
                        return markup;
                    },
                    templateSelection: function (repo) {
                        if (typeof config.callback === 'function') {
                            config.callback(repo);
                        }
                        if (typeof config.callback === 'string' && config.callback) {
                            window[config.callback](repo);
                        }
                        return repo.text;
                    }
                };
                config = $.extend(config, ajaxConfig);
            }
            $($el).select2(config);
        });
    };

    /**
     * 上传
     * @param $el
     * @param config
     */
    owner.upload = function ($el, config) {
        var defaultConfig = {
            url: rootUrl + '/' + roleName + '/system/Upload/index',
            type: '*',
            size: 0,
            num: 0,
            multi: true,
            resize: {},
            target: '',
            preview: '',
            callback: '',
            relative: 'false',
            progress: ''
        };
        config = $.extend(defaultConfig, config);

        var uploader;
        Do('upload', function () {
            uploader = new plupload.Uploader({
                runtimes: 'html5,html4',
                browse_button: $($el).get(0),
                url: config.url,
                filters: {
                    mime_types: [{
                        title: "指定文件",
                        extensions: config.type
                    }]
                },
                max_file_size: config.size,
                multipart: config.multi,
                resize: config.resize,
                init: {
                    PostInit: function () {
                        //初始化

                    },
                    FilesAdded: function (up, files) {
                        if (config.num > 0) {
                            if (up.files.length > config.num) {
                                dialog.msg('超过上传数量限制!');
                                uploader.removeFile(files);
                                return;
                            }
                        }
                        //添加文件
                        $($el).attr('disabled', true).append(' <span class="prs">[<strong>0%</strong>]</span>');
                        uploader.start();
                    },
                    UploadProgress: function (up, file) {
                        //上传进度
                        $($el).find('span').text(file.percent + '%');
                        if (typeof config.progress === 'string' && config.progress) {
                            window[config.progress].call($el, file.percent);
                        }

                    },
                    FileUploaded: function (up, file, response) {
                        //文件上传完毕
                        var data = JSON.parse(response.response);
                        if (!data.status) {
                            dialog.msg(data.info);
                            return;
                        }
                        //赋值地址
                        if (config.target) {
                            $(config.target).val(data.data.url);
                        }
                        //图片预览
                        if (config.preview) {
                            $(config.preview).attr('src', data.data.url);
                        }
                        //设置回调
                        if (typeof config.callback === 'function') {
                            config.callback.call($el, data.data);
                        }
                        if (typeof config.callback === 'string' && config.callback) {
                            window[config.callback].call($el, data.data);
                        }
                    },
                    Error: function (up, err) {
                        //错误信息
                        $($el).attr('disabled', false).find('span').remove();
                        dialog.msg(err.message);
                    },
                    UploadComplete: function (up, num) {
                        //队列上传完毕
                        $($el).attr('disabled', false).find('span').remove();
                    }
                }
            });
            uploader.init();
        });
    };

    /**
     * 图片预览
     * @param $el
     * @param config
     */
    owner.preview = function ($el, config) {
        var defaultConfig = {
            target: ''
        };

        config = $.extend(defaultConfig, config);
        $($el).on('click', function () {
            var image = $(config.target).val();
            if (!image) {
                dialog.msg('请先上传图片!');
                return;
            }
            window.open(image);
        });
    };

    owner.color = function ($el, config) {
        var defaultConfig = {};
        config = $.extend(defaultConfig, config);
        Do('color', function () {
            $($el).colorpicker(config);
        });
    };
}(jQuery, window.form = {}));


(function ($, owner) {
    /**
     * 二维码
     */
    owner.qrcode = function ($el, config) {
        var defaultConfig = {};
        config = $.extend(defaultConfig, config);
        Do('qrcode', function () {
            $($el).qrcode(config);
        });
    };
}(jQuery, window.show = {}));

/**
 * 通知组件
 */
(function ($, owner) {
    owner.success = function (config) {
        var defaultConfig = {
            content: "处理成功",
            time: 6
        };
        config = $.extend(defaultConfig, config, {
            status: 'success'
        });
        owner.show(config);
    };
    owner.warning = function (config) {
        var defaultConfig = {
            content: "处理中断",
            time: 6
        };
        config = $.extend(defaultConfig, config, {
            status: 'warning'
        });
        owner.show(config);
    };
    owner.error = function (config) {
        var defaultConfig = {
            content: "处理失败",
            time: 6

        };
        config = $.extend(defaultConfig, config, {
            status: 'error'
        });
        owner.show(config);
    };
    owner.show = function (config) {
        var status = {
            success: ['success', '#27ae60'],
            warning: ['warning', '#e0690c'],
            error: ['danger', '#dd514c']
        };
        UIkit.notification({
            message: config.content,
            status: status[config.status][0],
            timeout: config.time * 1000
        });
    };
}(jQuery, window.notify = {}));

/**
 * 常用方法
 */
(function ($, owner) {
    /**
     * 调试方法
     * @param msg
     */
    owner.debug = function (msg) {
        if (!debug) {
            return false;
        }
        if (typeof (console) != 'undefined') {
            console.log(msg);
        }
    };
    /**
     * AJAX请求
     * @param config
     */
    owner.ajax = function (config) {
        var defaultConfig = {
            async: true
        };
        config = $.extend(defaultConfig, config);
        if (config.loading) {
            dialog.loading();
        }
        $.ajax({
            url: config.url,
            type: config.type,
            data: config.data,
            async: config.async,
            dataType: 'json',
            beforeSend: function (request) {
                if (window.source) {
                    request.setRequestHeader("from", window.source);
                }
            },
            success: function (json) {
                if (config.loading) {
                    dialog.loading('', false);
                }
                if (typeof config.success == 'function') {
                    config.success(json.message, json.url);
                }
            },
            error: function (e) {
                if (config.loading) {
                    dialog.loading('', false);
                }
                try {
                    var json = eval('(' + e.responseText + ')');
                    var msg = json.message;
                    var url = json.url;
                } catch (e) {
                    var msg = null;
                    var url = null;
                }
                if (msg == '' || msg == null) {
                    msg = '数据请求失败，请刷新后再试！';
                }

                if (e.status == 404) {
                    app.error('请求操作不存在!');
                    return;
                }
                if (e.status == 401) {
                    if (typeof config.login == 'function') {
                        config.login(msg);
                        return;
                    } else {
                        app.error(msg, url);
                        return;
                    }
                }
                if (e.status == 501) {
                    dialog.confirm({
                        title: msg,
                        callback: [function () {
                            window.postMessage('{"event":"jump", "url":"' + url + '"}');
                            dialog.close();
                        }]
                    });
                    return;
                }
                if (typeof config.error == 'function') {
                    config.error(msg, url);
                    return;
                }
                app.error(msg, url);
            }
        });
    };
    /**
     * 成功提示
     * @param msg
     * @param url
     */
    owner.success = function (msg, url) {
        if (url) {
            window.location.href = url;
        } else {
            dialog.msg(msg);
        }
    };
    /**
     * 失败提示
     * @param msg
     * @param url
     */
    owner.error = function (msg, url) {
        if (url) {
            dialog.confirm({
                title: msg,
                callback: [function () {
                    window.location.href = url;
                }]
            });
        } else {
            dialog.msg(msg);
            return false;
        }
    };

    owner.date = function (fmt, time) {
        time = time * 1000;
        let date = new Date(time);

        function padLeftZero(str) {
            return ('00' + str).substr(str.length)
        }

        if (/(y+)/.test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length))
        }
        let o = {
            'M+': date.getMonth() + 1,
            'd+': date.getDate(),
            'h+': date.getHours(),
            'm+': date.getMinutes(),
            's+': date.getSeconds()
        };
        for (let k in o) {
            if (new RegExp(`(${k})`).test(fmt)) {
                let str = o[k] + '';
                fmt = fmt.replace(RegExp.$1, RegExp.$1.length === 1 ? str : padLeftZero(str))
            }
        }
        return fmt
    };
}(jQuery, window.app = {}));
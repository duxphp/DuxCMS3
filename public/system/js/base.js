/**
 * 页面框架
 */
(function ($, owner) {
    owner.frame = function () {
        dux.init();
    };
    owner.menu = function ($el, config) {
        $($el).click(function () {
            $('body').toggleClass('dux-mobile-menu');
        });
    };
}(jQuery, window.base = {}));

/**
 * 表格组件
 */
(function ($, owner) {
    owner.bind = function ($el, config) {
        Do('dialog', function () {
            var defaultConfig = {}, config = $.extend(defaultConfig, config);
            var $table = $($el).find('[data-table]'), $del = $table.find('[data-del]');
            //更改状态
            $table.on('click', '[data-status]', function () {
                var data = $(this).data(), $obj = this;
                if (data.status == 1) {
                    var status = 0;
                    var css = 'uk-text-danger';
                } else {
                    var status = 1;
                    var css = 'uk-text-success';
                }
                app.ajax({
                    type: 'post',
                    url: data.url,
                    data: {
                        id: data.id,
                        name: data.name,
                        status: status
                    },
                    success: function (info) {
                        notify.success({
                            content: info
                        });
                        $($obj).removeClass('uk-text-success uk-text-danger').addClass(css).data('status', status);
                    }
                });

            });
            //全选
            $table.find('[data-all]').click(function () {
                if (!$(this).is(':checked')) {
                    $table.find('input[type=checkbox]').prop("checked", false);
                } else {
                    $table.find('input[type=checkbox]').prop("checked", true);
                }
            });
            //删除
            $del.click(function () {
                var data = $(this).data(), $tr = $(this).parents('tr, .tr');

                dialog.confirm({
                    title: '是否确认删除?',
                    btn: ['删除', '取消'],
                    callback: [function () {
                        app.ajax({
                            type: 'post',
                            url: data.url,
                            data: {id: data.id},
                            success: function (info) {
                                notify.success({
                                    content: info
                                });
                                $tr.remove();
                                dialog.close();
                            },
                            error: function (msg) {
                                dialog.msg(msg);
                            }
                        });
                    }]
                });

            });
            //批量操作
            var $batch = $($el).find('[data-batch]');
            $batch.submit(function () {
                event.stopPropagation();
                var data = {}, ids = [];
                $.each($batch.serializeArray(), function (index, vo) {
                    data[vo.name] = vo.value;
                });
                $table.find('input[type=checkbox]:checked').each(function () {
                    var id = $(this).val();
                    if (id) {
                        ids.push(id);
                    }
                });
                data['ids'] = ids.join(',');
                app.ajax({
                    url: $batch.attr('action'),
                    data: data,
                    type: 'post',
                    success: function (info) {
                        dialog.alert({
                            title: info,
                            callback: function () {
                                location.reload();
                            }
                        });
                    },
                    error: function (info) {
                        dialog.alert({
                            title: info,
                            callback: function () {
                                location.reload();
                            }
                        });
                    }
                });
                return false;
            });
            //分页跳转
            var $pages = $($el).find('[data-pages]');
            $pages.submit(function (event) {
                event.stopPropagation();
                var page = $pages.find('input[name="page"]').val();
                var href = location.href;
                if (/page=\d+/.test(href)) {
                    href = href.replace(/page=\d+/, "page=" + page);
                } else if (href.indexOf('?') == -1) {
                    href = href + "?page=" + page;
                } else {
                    href = href + "&page=" + page;
                }
                window.location.href = href;
                return false;
            });

        });
    };
}(jQuery, window.table = {}));

/**
 * 系统组件
 */
(function ($, owner) {
    owner.mall = function ($el, config) {
        Do('tpl', function () {
            var defaultConfig = {
                key: 'mall_ids',
                data : ''
            };
            config = $.extend(defaultConfig, config);

            var modal = '<div class="uk-modal-container">\n' +
                '    <div class="uk-modal-dialog">\n' +
                '        <button class="uk-modal-close-default" type="button" uk-close></button>\n' +
                '        <div class="uk-modal-header" style=" padding: 15px 20px">\n' +
                '            <div class="uk-flex-1 uk-form uk-form-inline" style="padding-top: 0;">\n' +
                '                <div class="uk-form-group">\n' +
                '                    <input type="text" name="keyword" class="uk-input" value=""\n' +
                '                           placeholder="商品搜索">\n' +
                '                </div>\n' +
                '                <div class="uk-form-group">\n' +
                '                    <button type="button" class="uk-button uk-button-primary" data-search><i class="fa fa-search"></i></button>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '        <div style="height: 600px; overflow-y: auto;">\n' +
                '            <div class="dux-modal-media dux-modal-list" style="padding: 10px 10px; ">\n' +
                '\n' +
                '            </div>\n' +
                '        </div>\n' +
                '\n' +
                '        <div class="uk-modal-footer uk-text-right">\n' +
                '                <a href="javascript:;" class="uk-button uk-button-default" type="button"  data-page data-page-prev>上一页</a>\n' +
                '                <a href="javascript:;" class="uk-button uk-button-default" type="button"  data-page data-page-prev>下一页</a>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>';

            var tpl = '{{# for(var i in d){ }}\n' +
                '    <div class="item">\n' +
                '        <div class="dux-media-mall">\n' +
                '            <div class="mall-top">\n' +
                '                <div class="img">\n' +
                '                    <img class="" src="{{d[i].image}}" alt="">\n' +
                '                </div>\n' +
                '                <div class="title ">{{d[i].title}}</div>\n' +
                '            </div>\n' +
                '            <div class="mall-info">\n' +
                '                <div class="price">￥{{d[i].sell_price}}</div>\n' +
                '                <div class="store">{{d[i].store}}{{d[i].unit}}</div>\n' +
                '            </div>\n' +
                '            {{# if(d[i].hide != true){ }} ' +
                '            <div class="mall-action">\n' +
                '                <a href="javascript:;" class="uk-button uk-button-primary uk-button-block"\n' +
                '                   data-select="{{i}}">选择</a>\n' +
                '            </div>' +
                '            {{# }else{ }}' +
                '            <div class="mall-action">\n' +
                '                <a href="javascript:;" class="uk-button uk-button-danger uk-button-block"\n' +
                '                   data-media-del="{{d[i].mall_id}}">删除</a>\n' +
                '            </div>' +
                '            {{# } }}' +
                '        </div>\n' +
                '    </div>\n' +
                '    {{# } }}';

            var dialog = $(modal);
            var mediaMall = [];
            var curPage = 1;
            var maxPage = 0;
            var keyword = '';
            var loadData = function (callback) {
                app.ajax({
                    url: config.url,
                    data: {
                        page : curPage,
                        keyword: keyword
                    },
                    type: 'post',
                    loading: true,
                    success: function (info) {
                        var data = info.list;
                        maxPage = info.pageData.page;
                        mediaMall = data;
                        $(dialog).find('.dux-modal-list').html('');
                        laytpl(tpl).render(data, function (html) {
                            $(dialog).find('.dux-modal-list').append(html);
                            $(dialog).find('.dux-modal-list').append('<div class="item item-empty"></div><div class="item item-empty"></div>');
                            if(callback) {
                                callback();
                            }
                        });
                    }
                });
            };

            if (config.target) {
                var html = '<table class="uk-table uk-table-hover uk-table-middle dux-table dux-table-dialog uk-text-nowrap" style="border: 1px solid #ddd"><thead><tr><th>商品</th><th>价格/库存</th><th width="70">操作</th></tr></thead><tbody></tbody></table>';
                $(config.target).html(html);
                $(config.target).on('click', '[data-del]', function () {
                    $(this).parents('tr').remove();
                });
                if(config.data) {
                    app.ajax({
                        url: config.url,
                        data: {
                            id: config.data
                        },
                        type: 'post',
                        loading: true,
                        success: function (info) {
                            var list = info.list;
                            for (var i in list) {
                                var info = list[i];
                                $(config.target).find('tbody').append('<tr data-mall-id="' + info.mall_id + '">' +
                                    '<td>[' + info.mall_id + '] ' + info.title + '</td>' +
                                    '<td>￥' + info.sell_price + '/' + info.store + info.unit + '</td>' +
                                    '<td><input type="hidden" name="' + config.key + '[]" value="' + info.mall_id + '"><button type="button" class="uk-button uk-button-small uk-button-danger" data-del><i class="fa fa-trash"></i></button></td>' +
                                    '</tr>');
                            }
                        }
                    });
                }
            }

            $($el).on('click', function () {
                $(dialog).on('hidden', function () {
                    $(dialog).remove();
                });
                $(dialog).find('.dux-modal-list').on('click', '[data-select]', function () {
                    var key = $(this).data('select');
                    var info = mediaMall[key];
                    UIkit.modal(dialog).hide();
                    if (config.target) {
                        if ($(config.target).find('[data-mall-id="' + info.mall_id + '"]').length > 0) {
                            return;
                        }
                        $(config.target).find('tbody').append('<tr data-mall-id="' + info.mall_id + '">' +
                            '<td>[' + info.mall_id + '] ' + info.title + '</td>' +
                            '<td>￥' + info.sell_price + '/' + info.store + info.unit + '</td>' +
                            '<td><input type="hidden" name="' + config.key + '[]" value="' + info.mall_id + '"><button type="button" class="uk-button uk-button-small uk-button-danger" data-del><i class="fa fa-trash"></i></button></td>' +
                            '</tr>');
                    }
                    if (typeof config.callback === 'function') {
                        config.callback(html, info);
                    }
                    if (typeof config.callback === 'string' && config.callback) {
                        window[config.callback](html, info);
                    }
                });

                $(dialog).on('click', '[data-search]', function () {
                    console.log($(dialog).find('input[name="keyword"]').val());
                    curPage = 0;
                    keyword = $(dialog).find('input[name="keyword"]').val();
                    loadData();
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
                    loadData();
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
                    loadData();
                });
                loadData(function () {
                    UIkit.modal(dialog).show();
                });
            });



        });
    };

    owner.user = function ($el, config) {
        var defaultConfig = {
            url: rootUrl + '/' + roleName + '/member/MemberUser/ajaxInfo'
        };
        config = $.extend(defaultConfig, config);
        $($el).on('beforeshow', function () {
            var obj = this;
            $(obj).html('加载中...');
            app.ajax({
                url: config.url,
                data: {
                    id: $(obj).data('id')
                },
                success: function (data) {
                    var html = '<div class="dux-user-box"><div class="user-avatar"><img src=""></div><div class="user-info"><div class="user-data"><span class="user-id"></span> <span class="user-name"></span> <span class="user-tel"></span> <span class="user-emall"></span></div><div class="user-desc"><span class="uk-label uk-label-primary role-name"></span> <span class="uk-label uk-label-success grade-name"></span></div><div class="user-reg"></div></div></div>';
                    html = $(html);
                    html.find('img').attr('src', data.avatar);
                    html.find('.user-id').text('[' + data.user_id + ']');
                    html.find('.user-name').text(data.nickname);
                    html.find('.user-tel').text(data.tel ? '[' + data.tel + ']' : '');
                    html.find('.user-email').text(data.email ? '[' + data.email + ']' : '');
                    html.find('.grade-name').text(data.grade_name);
                    html.find('.role-name').text(data.role_name);
                    html.find('.user-reg').text('登录时间：' + app.date('yyyy-MM-dd', data.login_time));
                    $(obj).html(html);
                }
            });
        });
    };

    owner.dialog = function ($el, config) {
        var defaultConfig = {};
        config = $.extend(defaultConfig, config);

        var html = '<div>\n' +
            '    <div class="uk-modal-dialog">\n' +
            '        <button class="uk-modal-close-default" type="button" uk-close></button>\n' +
            '        <div class="uk-modal-header">\n' +
            '            <h2 class="uk-modal-title">' + config.title + '</h2>\n' +
            '        </div>\n' +
            '        <div data-html></div>\n' +
            '    </div>\n' +
            '</div>';
        var dialog = $(html);
        $($el).on('click', function () {
            $(dialog).on('hidden', function () {
                $(dialog).remove();
            });
            app.ajax({
                url: config.url,
                loading: true,
                data: config.params,
                success: function (html) {
                    dialog.find('[data-html]').html(html);
                    UIkit.modal(dialog).show();
                }
            });
        });
    };

    owner.attach = function ($el, config) {
        if($el) {
            $($el).on('click', function () {
                form.fileManage(config);
            });
        }else {
            init();
        }
    };

    owner.images = function ($el, config) {
        var defaultConfig = {
            layout: '.image-list',
            name: 'images',
        };
        config = $.extend(defaultConfig, config);
        config.callback = function (data) {
            var html = '<li data-item><input type="hidden" name="' + config.name + '[url][]" value="' + data.url + '"><div class="del-layout"><a class="uk-button uk-button-danger uk-button-small" data-del href="javascript:;">删除</a></div><div class="image"><img src="' + data.url + '"></div></li>';
            $(config.layout).append(html);
        };
        owner.attach($el, config);
        if (config.data) {
            $.each(config.data, function (index, item) {
                config.callback(item);
            });
        }
        $(config.layout).on('mouseenter', '[data-item]', function () {
            $(this).addClass('del-show');
        });
        $(config.layout).on('mouseleave', '[data-item]', function () {
            $(this).removeClass('del-show');
        });
        $(config.layout).on('click', '[data-del]', function () {
            $(this).parents('[data-item]').remove();
        });
    };
    owner.random = function ($el, config) {
        var defaultConfig = {
            len: 32,
            target: ''
        };
        config = $.extend(defaultConfig, config);
        $($el).on('click', function () {
            var $chars = 'ABCDEFGHIJKRMNOPQRSTUVWXYZabcdefghijkrmnopqrstuvwxyz012345678';
            var maxPos = $chars.length;
            var pwd = '';
            for (i = 0; i < config.len; i++) {
                pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
            }
            $(config.target).val(pwd);
        });
    }
}(jQuery, window.system = {}));


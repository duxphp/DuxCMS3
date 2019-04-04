;(function ($) {
    $.fn.duxWebEditor = function (config) {

        var editObj = this;

        //定义配置
        config = $.extend({
            label: [
                {
                    'name': '普通文本',
                    'type': 'text',
                    'data': '普通文本',
                },
            ],
            scale: true,
            width: 800,
            height: 400,
            background: '',
            content: $(this).html()
        }, config);

        //定义标签
        var labelHtml = '';
        $.each(config.label, function (key, val) {
            labelHtml += '<li><a href="javascript:;" data-key="' + key + '">' + val.name + '</a></li>';
        });

        //定义HTML
        var editHtml = '<div class="dux-designer-editor">\n' +
            '            <div class="editor-header">\n' +
            '                <div class="header-items">\n' +
            '                    <div class="uk-button-group">\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button"  data-tool="background">背景图</button>\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button">模板</button>\n' +
            '                        <div uk-dropdown="mode: click;">\n' +
            '                            <ul class="uk-nav uk-dropdown-nav"  data-menu="tpl">' + labelHtml + '</ul>\n' +
            '                        </div>\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button" data-tool="text">文本</button>\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button" data-tool="image">图片</button>\n' +
            '                        <button class="uk-button uk-button-small uk-button-danger" type="button" data-tool="del">删除</button>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '                <div class="header-items">\n' +
            '                    <div class="uk-button-group">\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button" data-tool="layer-top">置于顶层</button>\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button"  data-tool="layer-prev">上移一层</button>\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button" data-tool="layer-next">下移一层</button>\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button" data-tool="layer-bottom">置于底层</button>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '                <div class="header-items">\n' +
            '                    <div class="uk-button-group">\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button">字号</button>\n' +
            '                        <div uk-dropdown="mode: click;">\n' +
            '                            <ul class="uk-nav uk-dropdown-nav" data-menu="size">\n' +
            '                                <li><a href="javascript:;">12px</a></li>\n' +
            '                                <li><a href="javascript:;">14px</a></li>\n' +
            '                                <li><a href="javascript:;">16px</a></li>\n' +
            '                                <li><a href="javascript:;">18px</a></li>\n' +
            '                                <li><a href="javascript:;">20px</a></li>\n' +
            '                                <li><a href="javascript:;">22px</a></li>\n' +
            '                                <li><a href="javascript:;">24px</a></li>\n' +
            '                            </ul>\n' +
            '                        </div>\n' +
            '                    <div class="uk-button-group">\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" type="button">对齐</button>\n' +
            '                        <div uk-dropdown="mode: click;">\n' +
            '                            <ul class="uk-nav uk-dropdown-nav" data-menu="align">\n' +
            '                                <li><a href="javascript:;" data-type="left">左对齐</a></li>\n' +
            '                                <li><a href="javascript:;" data-type="right">右对齐</a></li>\n' +
            '                                <li><a href="javascript:;" data-type="center">居中</a></li>\n' +
            '                            </ul>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                    <div class="uk-button-group">\n' +
            '                        <button class="uk-button uk-button-small uk-button-primary" data-tool="color" type="button">颜色</button>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '            </div>\n' +
            '            <div class="editor-body">\n' +
            '                <div class="body-layout" style="width: ' + config.width + 'px; height: ' + config.height + 'px;">\n' +
            '                </div>\n' +
            '            </div>\n' +
            '            <div class="editor-footer">\n' +
            '            </div>\n' +
            '        </div>';
        $(this).html(editHtml);

        //定义基本元素
        var $webEditor = $(this).find('.dux-designer-editor'),    //编辑器
            $editTools = $webEditor.find('.editor-header'),
            $editBody = $webEditor.find('.body-layout');

        /**
         * 初始化工具栏
         */
        var toolsInit = function () {
            form.color($editTools.find('[data-tool="color"]'), {
                'onSelect': function (color) {
                    execute.color(color);
                }
            });
            system.attach($editTools.find('[data-tool="image"]'), {
                type: 'image',
                callback: function (data) {
                    execute.image(data.url);
                }
            });
            system.attach($editTools.find('[data-tool="background"]'), {
                type: 'image',
                callback: function (data) {
                    execute.background(data.url);
                }
            });
            $editTools.on('click', '[data-tool]', function () {
                var type = $(this).data('tool');
                switch (type) {
                    case 'del':
                        execute.del();
                        break;
                    case 'text':
                        execute.text();
                        break;
                    case 'layer-top':
                        execute.layer('top');
                        break;
                    case 'layer-prev':
                        execute.layer('prev');
                        break;
                    case 'layer-next':
                        execute.layer('next');
                        break;
                    case 'layer-bottom':
                        execute.layer('bottom');
                        break;
                }
            });
            $editTools.find('[data-menu="tpl"]').on('click', 'a', function () {
                console.log('xx');
                execute.tpl($(this).data('key'));
            });
            $editTools.find('[data-menu="size"]').on('click', 'a', function () {
                execute.size($(this).text());
            });
            $editTools.find('[data-menu="align"]').on('click', 'a', function () {
                execute.align($(this).data('type'));
            });
        };
        toolsInit();

        var execute = {
            text: function (text, width, height) {
                text = text ? text : '请填写内容';
                width = width ? width : 100;
                height = height ? height : 22;
                var box = $('<div class="box" data-type="text" contenteditable="true">' + text + '</div>');
                box.css({
                    'color': '#000000',
                    'top': '0px',
                    'left': '0px',
                    'width': width + 'px',
                    'height': height + 'px',
                    'z-index': 0
                });
                $editBody.append(box);
            },
            del: function () {
                $editBody.find('.active').remove();
            },
            image: function (url, width, height) {
                var box = $('<div class="box" data-type="image"><img src="' + url + '"/></div>');
                box.css({
                    'tpl': '0px',
                    'left': '0px',
                    'z-index': 0
                });
                if (!width || !height) {
                    var img = new Image();
                    img.src = url;
                    if (img.complete) {
                        width = img.width;
                        height = img.height;
                    } else {
                        img.onload = function () {
                            width = img.width;
                            height = img.height;
                        }
                    }
                    var maxWidth = $editBody.width() / 2;
                    if (width > maxWidth) {
                        height = (maxWidth * height) / width;
                        width = maxWidth;
                    }
                }
                width ? width : '100px';
                height ? height : '100px';
                box.css({
                    'width': width + 'px',
                    'height': height + 'px'
                });
                $editBody.append(box);
            },
            background: function (url) {
                $editBody.css('background-image', 'url("' + url + '")');
                config.background = url;
            },
            tpl: function (val) {
                var data = config.label[val];
                if (data.type == 'text') {
                    execute.text(data.data, data.width, data.height);
                }
                if (data.type == 'image') {
                    execute.image(data.data, data.width, data.height);
                }
            },
            size: function (size) {
                if ($editBody.find('.active').data('type') == 'text') {
                    $editBody.find('.active').css('font-size', size);
                }
            },
            align: function (align) {
                if ($editBody.find('.active').data('type') == 'text') {
                    $editBody.find('.active').css('text-align', align);
                }
            },
            layer: function (type) {
                var index = $editBody.find('.active').css('z-index');
                switch (type) {
                    case 'top':
                        index = 10;
                        break;
                    case 'prev':
                        index = index + 1;
                        break;
                    case 'next':
                        index = index - 1;
                        break;
                    case 'bottom':
                        index = index - 0;
                        break;
                }
                if (index > 10) {
                    index = 10;
                }
                if (index < 0) {
                    index = 0;
                }
                $editBody.find('.active').css('z-index', index);
            },
            color: function (color) {
                $editBody.find('.active').css('color', color);
            }
        };


        var unBoxModel = function () {
            $editBody.find('.move').remove();
            $editBody.find('.box').removeClass('active');
        };

        var initBody = function () {

            if (config.background) {
                execute.background(config.background);
            }
            $editBody.html(config.content);

            $editBody.on({
                'mousemove': function (e) {
                    if (!!$editBody.move) {
                        var posix = !$editBody.move_target ? {'x': 0, 'y': 0} : $editBody.move_target.posix,
                            callback = $editBody.call_down || function () {
                                startX = e.pageX - parseInt($editBody.offset().left);
                                startY = e.pageY - parseInt($editBody.offset().top);
                                $($editBody.move_target).css({
                                    'top': startY - posix.y,
                                    'left': startX - posix.x
                                });
                            };
                        callback.call(this, e, posix);
                    }
                    return false;
                }, 'mouseup': function (e) {
                    if (!!$editBody.move) {
                        var callback = $editBody.call_up || function () {
                        };
                        callback.call($editBody, e);
                        $.extend($editBody, {
                            'move': false,
                            'move_target': null,
                            'call_down': false,
                            'call_up': false
                        });
                    }
                }
            });


            $editBody.on('mousedown', '.box.active', function (e) {
                this.posix = {
                    'x': e.offsetX,
                    'y': e.offsetY
                };
                $.extend($editBody, {
                    'move': true,
                    'move_target': this
                });
            }).on('mousedown', '.move', function (e) {
                var $box = $editBody.find('.box.active');
                var posix = {
                    'w': $box.width(),
                    'h': $box.height(),
                    'x': e.pageX,
                    'y': e.pageY
                };
                $.extend($editBody, {
                    'move': true,
                    'call_down': function (e) {
                        $box.css({
                            'width': Math.max(50, e.pageX - posix.x + posix.w),
                            'height': Math.max(20, e.pageY - posix.y + posix.h)
                        });
                    }
                });
                return false;
            });
            $editBody.on('click', '.box', function () {
                unBoxModel();
                $(this).addClass('active');
                $(this).append('<div class="move"></div>');
            });
        };
        initBody();
        $(editObj).show();

        var rgb2hex = function (rgb) {
            if (rgb >= 0) return rgb;
            else {
                rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

                function hex(x) {
                    return ("0" + parseInt(x).toString(16)).slice(-2);
                }

                rgb = "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
            }
            return rgb;
        };

        $.fn.extend({
            'getHtml': function () {
                return $editBody.html();
            },

            'getData': function () {
                var data = {
                    'background': config.background,
                    'list': []
                };
                $editBody.find('.box').each(function () {
                    var item = {
                        'type': $(this).data('type'),
                        'index': $(this).css('z-index'),
                        'top': $(this).css('top'),
                        'left': $(this).css('left'),
                        'width': $(this).css('width'),
                        'height': $(this).css('height'),
                        'size': $(this).css('font-size'),
                        'align': $(this).css('text-align'),
                        'color': $(this).css('color'),
                        'content': ''
                    };
                    if (item.type == 'text') {
                        item.content = $(this).text();
                    }
                    if (item.type == 'image') {
                        item.content = $(this).find('img').attr('src');
                    }
                    if (item.color) {
                        item.color = rgb2hex(item.color);
                    }
                    data.list.push(item);

                });
                return data;
            }
        });
    };
}
)(jQuery);

/**
 * 初始化类库
 */
(function (win, doc) {
    /**
     * 设置包路径
     */
    var jsSelf = (function () {
        var files = doc.getElementsByTagName('script');
        return files[files.length - 1];
    })();
    window.packagePath = jsSelf.getAttribute('data-path');
    window.rootUrl = jsSelf.getAttribute('data-root');
    window.roleName = jsSelf.getAttribute('data-role');
    window.tplPath = jsSelf.getAttribute('data-tpl');
    window.commonPath = '/public/common/js/';
    var source = jsSelf.getAttribute('data-source');
    if(source == null) {
        source = false;
    }
    window.source = source;
    var debug = jsSelf.getAttribute('data-debug');
    if(debug == null) {
        debug = false;
    }
    window.debug = debug;
    window.mobile = false;

    /**
     * 公共类
     */
    Do.add('common', {
        path: commonPath + 'common.min.js?v=2.0',
        type : 'js'
    });

    /**
     * 表单
     */
    Do.add('form', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js',
        type: 'js'
    });

    /**
     * 模板引擎
     */
    Do.add('tpl', {
        path: commonPath + 'package/tpl/laytpl.js',
        type: 'js'
    });

    /**
     * 富文本编辑器
     */

    Do.add('editorSrc', {
        path: commonPath + 'package/tinymce/tinymce.min.js',
    });
    Do.add('editor', {
        path: commonPath + 'package/tinymce/tinymce.min.js',
    });

    Do.add('ckeditor', {
        path: commonPath + 'package/editor/ckeditor.js',
    });

    /**
     * 日期选择
     */
    Do.add('date', {
        path: commonPath + 'package/date/laydate.js',
    });

    /**
     * 地区选择
     */
    Do.add('distpicker', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/distpicker/2.0.3/distpicker.min.js'
    });

    /**
     * TAG输入
     */
    Do.add('tags', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js'
    });

    /**
     * 下拉增强
     */
    Do.add('select2Css', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/css/select2.min.css',
        type: 'css'
    });
    Do.add('select2Src', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/select2.full.min.js'
    });
    Do.add('select2', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/i18n/zh-CN.js',
        requires: ['select2Src', 'select2Css']
    });

    /**
     * 打印
     */
    Do.add('print', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.5.1/jQuery.print.min.js'
    });

    /**
     * 二维码
     */
    Do.add('qrcode', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js',
        type: 'js'
    });

    /**
     * 图表
     */
    Do.add('echarts', {
        path: 'https://cdn.bootcss.com/echarts/4.2.1-rc1/echarts.min.js',
        type: 'js'
    });

    /**
     * 上传
     */
    Do.add('uploadSrc', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.2/plupload.full.min.js'
    });
    Do.add('upload', {
        path: 'https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.2/i18n/zh_CN.js',
        requires: ['uploadSrc']
    });

    /**
     * 颜色选择
     */
    Do.add('color', {
        path:  commonPath + 'package/color/jquery.colorpicker.js',
        type: 'js'
    });


})(window, document);

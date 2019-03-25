/**
 * 初始化系统库
 */
(function (win, doc) {

    var path = packagePath + '/system/js/';

    /**
     * 核心模块
     */
    Do.add('base', {
        path: path + 'base.js',
        type: 'js',
        requires: ['common']
    });

    /**
     * 编辑器
     */
    Do.add('webeditCss', {
        path: path + 'package/webedit/css/edit.css',
        type: 'css'
    });
    Do.add('webedit', {
        path: path + 'package/webedit/js/editor.js',
        requires: ['webeditCss']
    });

})(window, document);
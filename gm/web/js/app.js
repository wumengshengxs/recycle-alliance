$(function () {
    $(".navbar-expand-toggle").click(function () {
        $(".app-container").toggleClass("expanded");
        return $(".navbar-expand-toggle").toggleClass("fa-rotate-90");
    });
    return $(".navbar-right-expand-toggle").click(function () {
        $(".navbar-right").toggleClass("expanded");
        return $(".navbar-right-expand-toggle").toggleClass("fa-rotate-90");
    });
});

$(function () {
    return $('select').select2();
});

$(function () {
    return $('.toggle-checkbox').bootstrapSwitch({
        size: "small"
    });
});

$(function () {
    return $('.match-height').matchHeight();
});

$(function () {
    //特殊页面需要加参数可以直接使用window.dataTable_param={'aaa':'bbb'}
    if (typeof (dataTable_param) == 'undefined') {
        window.dataTable({});
    } else {
        window.dataTable(dataTable_param);
    }
});

function dataTable(params, url) {
    if (!url) {
        var uri = location.pathname.replace(new RegExp('/', 'g'), '-');
        var uri_arr = uri.split('-');
        var uri_action = uri_arr.pop();
        var uri_controller = uri_arr.pop();
        url = '/' + uri_controller + '/ajax_' + uri_action;
    }

    var datatable = $('.datatable' + uri);
    return datatable.DataTable({
        'dom': '<"top"fl<"clear">>rt<"bottom"ip<"clear">>',
        'searching': false,
        'destroy': true,
        'ordering': false,
        'language': {
            'sProcessing': '松鼠宝宝正在查找线索...',
            'sLengthMenu': '每页显示 _MENU_ 条记录',
            'sZeroRecords': '松鼠宝宝已经尽力了 :（',
            'sSearch': '松鼠侦探',
            'oPaginate': {
                'sFirst': '首页',
                'sPrevious': '上页',
                'sNext': '下页',
                'sLast': '末页'
            },
            'sInfo': '当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项',
            'sInfoEmpty': '当前显示第 0 至 0 项，共 0 项'
        },
        'serverSide': true,
        'processing': true,
        'ajax': {
            'url': url,
            'data': params
        }
    });
}

function show_detail(title, src) {
    Dialog({
        title: title,
        width: 800,
        iframeContent: {
            src: src,
            height: 500
        },
        showButton: false
    });
}

$(function () {
    return $(".side-menu .nav .dropdown").on('show.bs.collapse', function () {
        return $(".side-menu .nav .dropdown .collapse").collapse('hide');
    });
});

$(function () {
    $.datepicker.regional['zh-CN'] = {
        clearText: '清除',
        clearStatus: '清除已选日期',
        closeText: '关闭',
        closeStatus: '不改变当前选择',
        prevText: '<上月',
        prevStatus: '显示上月',
        prevBigText: '<<',
        prevBigStatus: '显示上一年',
        nextText: '下月>',
        nextStatus: '显示下月',
        nextBigText: '>>',
        nextBigStatus: '显示下一年',
        currentText: '今天',
        currentStatus: '显示本月',
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        monthNamesShort: ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'],
        monthStatus: '选择月份',
        yearStatus: '选择年份',
        weekHeader: '周',
        weekStatus: '年内周次',
        dayNames: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        dayNamesShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        dayStatus: '设置 DD 为一周起始',
        dateStatus: '选择 m月 d日, DD',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        initStatus: '请选择日期',
        isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
    $("#datepicker").datepicker();
    $("#datepicker_start").datepicker();
    $("#datepicker_end").datepicker();
});
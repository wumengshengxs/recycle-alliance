function dataTable(params, url, datatable) {
    if (!url) {
        var uri = location.pathname.replace(new RegExp('/', 'g'), '-');
        var uri_arr = uri.split('-');
        var uri_action = uri_arr.pop();
        var uri_controller = uri_arr.pop();
        url = '/' + uri_controller + '/ajax_' + uri_action;
    }
    if(typeof(datatable) == 'undefined'){
        datatable = $('#dataTable');
    }
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
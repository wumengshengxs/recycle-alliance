jQuery(document).ready(function($) {
    $('#datetimepicker1').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: moment.locale('zh-cn')
    });
    $('#datetimepicker1').keyup(function(){
        $(this).val('');
    })
});


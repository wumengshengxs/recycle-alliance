$('#province').change(function() {
    var pid = $(this).val();
    if (pid == '') {
        $('#city').html('<option value="">选择市</option>');
        $('#district').html('<option value="">选择区县</option>');
        $('#street').html('<option value="">选择街/镇</option>');
        return;
    }
    var type = 'city';
    $.ajax({
        url: '/manager/region_tx',
        type: 'get',
        data: { 'pid': pid, 'type': type },
        dataType: 'json',
        success: function(res) {
            var html = '<option value="">选择市</option>';
            for (var i in res) {
                html += '<option value="' + res[i].id + '">' + res[i].fullname + '</option>';
            }
            $('#city').html(html);
            $('#district').html('<option value="">选择区县</option>');
            $('#street').html('<option value="">选择街/镇</option>');
        }
    });
});

$('#city').change(function() {
    var pid = $(this).val();
    var type = 'district';
    $.ajax({
        url: '/manager/region_tx',
        type: 'get',
        data: { 'pid': pid, 'type': type },
        dataType: 'json',
        success: function(res) {
            var html = '<option value="">选择区县</option>';
            for (var i in res) {
                html += '<option value="' + res[i].id + '">' + res[i].fullname + '</option>';
            }
            $('#district').html(html);
        }
    });
});

$('#district').change(function() {
    var pid = $(this).val();
    var type = 'street';
    $.ajax({
        url: '/manager/region_tx',
        type: 'get',
        data: { 'pid': pid, 'type': type },
        dataType: 'json',
        success: function(res) {
            var html = '<option value="">选择街/镇</option>';
            for (var i in res) {
                html += '<option value="' + res[i].id + '">' + res[i].fullname + '</option>';
            }
            $('#street').html(html);
        }
    });
});
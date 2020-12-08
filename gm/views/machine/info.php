<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/concept/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/concept/libs/css/style.css">
    <link rel="stylesheet" href="/concept/vendor/fonts/fontawesome/css/fontawesome-all.css">


    <!-- Javascript Libs -->
    <script src="/concept/vendor/jquery/jquery-3.3.1.min.js"></script>

    <title>小松鼠运营管理系统</title>
    <style>
        .butt{
            border:solid 1px #ced4da;cursor:pointer;
        }
        .butt:hover{
            background-color:#05aafe;
            color: #fff;
        }
        .row-input{
            margin: 20px 0px 0px 0px;
        }
        .row-button{
            line-height: 62px;
            margin: -20px;
            margin-top: 45px;
        }
        #map{
            width:500px;
            height:300px;
        }
    </style>
</head>
<body>
<div class="card" style="margin-bottom:0px">
    <div class="card-body">
        <div class="row row-input">
            <label>所属联营方</label>
        </div>
        <div><?=$agent['company_name']?></div>
        <div class="row row-input">
            <label>所在省市</label>
        </div>
        <div><?=$srRecyclingMachine['province_name'] . '-' . $srRecyclingMachine['city_name']?></div>
        <div class="row row-input">
            <label>所属街镇</label>
            <input type="text" id="street_name" value="<?=$srRecyclingMachine['street_name']?>" class="form-control col-12" />
        </div>
        <div class="row row-input">
            <label>点位信息</label>
            <input type="text" id="community_name" value="<?=$srRecyclingMachine['community_name']?>" class="form-control col-12" />
        </div>
        <div class="row row-input">
            <label>点位地址</label>
            <input type="text" id="location" value="<?=$srRecyclingMachine['location']?>" class="form-control col-12" />
        </div>
        <div class="row row-input">
            <label>安装位置</label>
        </div>
        <div id="map"></div>
        <div class="row row-button">
            <input type="hidden" id="machine_id" value="<?=$srRecyclingMachine['id']?>" />
            <input type="hidden" id="lng" value="<?=$srRecyclingMachine['longitude']?>" />
            <input type="hidden" id="lat" value="<?=$srRecyclingMachine['latitude']?>" />
            <div id="save" class="col-12 text-center butt"><b>保存</b></div>
        </div>
    </div>
</div>

<script charset="utf-8" src="https://map.qq.com/api/gljs?v=1.exp&key=WZYBZ-7CO6D-2K44L-HCQ2N-BYDYQ-U2BSM"></script>
<script type="text/javascript" src="/lib/js/MiniDialog-es5.min.js"></script>
<script>
    function initMap() {
        var center = new TMap.LatLng(<?=$srRecyclingMachine['latitude']?>, <?=$srRecyclingMachine['longitude']?>);//设置中心点坐标
        //初始化地图
        var map = new TMap.Map('map', {
            center: center,
            zoom: 17
        });

        //初始化marker
        var marker = new TMap.MultiMarker({
            id: "marker-layer", //图层id
            map: map,
            styles: { //点标注的相关样式
                "marker": new TMap.MarkerStyle({
                    "width": 35,
                    "height": 35,
                    "anchor": { x: 16, y: 32 },
                    "src": "/concept/images/agent_logo.png"
                })
            },
            geometries: [{ //点标注数据数组
                "id": "demo",
                "styleId": "marker",
                "position": new TMap.LatLng(<?=$srRecyclingMachine['latitude']?>, <?=$srRecyclingMachine['longitude']?>),
                "properties": {
                    "title": "marker"
                }
            }]
        });

        //监听点击事件添加marker
        map.on("click", (evt) => {
            marker.setMap(null);
            marker = null;
            marker = new TMap.MultiMarker({
                map: map,
                styles: { //点标注的相关样式
                    "marker": new TMap.MarkerStyle({
                        "width": 35,
                        "height": 35,
                        "anchor": { x: 16, y: 32 },
                        "src": "/concept/images/agent_logo.png"
                    })
                },
            });
            marker.add({
                "styleId": "marker",
                position: evt.latLng
            });
            var lng = evt.latLng.getLng().toFixed(6);
            var lat = evt.latLng.getLat().toFixed(6);
            $("#lng").val(lng)
            $("#lat").val(lat)
        });


    }
    window.onload = initMap();

    $('#save').click(function(){
        var params = {
            street_name:$('#street_name').val(),
            community_name:$('#community_name').val(),
            location:$('#location').val(),
            longitude:$('#lng').val(),
            latitude:$('#lat').val(),
            id:$('#machine_id').val(),
            '_csrf-gm': '<?=Yii::$app->request->csrfToken?>'
        }
        Dialog({
            showTitle: false,
            content: "<h5><b>是否确认保存</b></h5>",
            ok: {
                callback: function () {
                    $('.mini-dialog-container').remove();
                    Dialog.waiting( "处理中，请等待..." );
                    $.ajax({
                        url: '/machine/ajax_info',
                        type: 'post',
                        data: params,
                        dataType: 'json',
                        success: function(res){
                            $('.mini-dialog-container').remove();
                            if(res){
                                Dialog.success('提示', '保存成功');
                                window.parent.ifr_close();
                            }
                            else{
                                Dialog.error('错误', '系统错误，请联系管理员');
                            }
                        }
                    })
                }
            }
        });

    });
</script>
</body>
</html>
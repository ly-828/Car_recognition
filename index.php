<?php require 'dbselect.php'?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"宋体";}
	</style>
	<script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=RFYunlQtsTdvxgWAM2ETqdiHa1GP8NtF"></script>
	<title>基于车辆品牌的消费水平统计系统</title>
</head>
<body>
	<div id="allmap"></div>
</body>
</html>

<script type="text/javascript">
    
	// 百度地图API功能
	var map = new BMap.Map("allmap", { enableMapClick: false });
    map.enableScrollWheelZoom(true);
	var centerpoint = new BMap.Point(104.108000,30.679944);
    map.centerAndZoom(centerpoint, 16.8);//确定中心点

    var jsonstr = <?php echo($jsonres)?>;
    var pNum = <?php echo($strNum)?>;//点的个数
    
    function addMarker(array){
        let point = new BMap.Point(array[1],array[2]);
        let marker = new BMap.Marker(point);// 创建标注
        map.addOverlay(marker);// 将标注添加到地图中
        var opts = {
	        width : 200,     // 信息窗口宽度
	        height: 100,     // 信息窗口高度
	        title : array[0] , // 信息窗口标题
	    }
        var infomation = "最多车辆品牌：" + array[3] + "<br/>车辆平均价格：" + array[4] + "元";
        var infoWindow = new BMap.InfoWindow(infomation,opts); // 创建信息窗口对象
        marker.addEventListener("click", function(){          
		    map.openInfoWindow(infoWindow,point); //开启信息窗口
	    });
    } //定义创建标注函数

    for(var i=0;i<pNum;i++){
        addMarker(jsonstr[i]);
    }//通过循环不断创建标注
    
</script>

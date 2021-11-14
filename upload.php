<?php
    header("Content-type: text/html; charset=utf-8");
    $con=mysqli_connect("localhost","root","E8C78cb65dea","test");
    if(!$con){
        die("连接失败");
    }
    mysqli_query($con,"set names 'utf8' ");
    mysqli_query($con,"set character_set_client=utf8");
    mysqli_query($con,"set character_set_results=utf8");

    $strname=$_POST["strname"];
    $lon=$_POST["lon"];
    $lat=$_POST["lat"];
    
    $file=$_FILES["file"]["tmp_name"];
    $filename=$_FILES["file"]["name"];
    $path="/usr/local/wenjian/photo/";
    
    
    $res = move_uploaded_file($file,$path.$filename);
    if($res){
        echo "成功上传到：".$path.$filename;
		echo "<br/>";
	/* 服务器运行python识别程序指令 */
        $commond = "/usr/local/bin/python3.7 -W ignore /usr/local/wenjian/predict.py"." ".$filename;
		echo "正在识别车型···<br/>";
		exec($commond." 2>error.txt",$out,$worked);
        $txtfile = fopen("/usr/local/wenjian/result.txt","r");
		$result = array();
		$i=0;
		while(!feof($txtfile)){
			$result[$i]=fgets($txtfile);	
			$i++;
		}
		fclose($txtfile);
		sleep(3);
	//$result[0]是车品牌
    //$result[1]是价格
	/* 数据库上传语句 */
		if($result[0]=='error1'){
			echo "抱歉未能识别车辆或所识别得分过低，请重新尝试";		
		}elseif($result[0]=='error2'){
			echo "请调整角度后再次拍摄以获取正确结果";
		}else{
   			echo "识别完成，正在导出至数据库···<br/>";
			$dbinsert="insert into temp values(0,'".$strname."','".$lon."','".$lat."','".$result[0]."','".$result[1]."')";
			if(mysqli_query($con,$dbinsert)==TRUE){
           		echo "数据成功插入";
        	}else{
            		echo "insert error";
        	}
		}
    }else{
        echo "upload error";
    }
    
    mysqli_close($con);   
?>


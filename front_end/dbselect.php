<?php

    header("content-type:text/html;charset=utf-8"); 
    
    $con=mysqli_connect("localhost","root","E8C78cb65dea","test");
    if(!$con){
        die("连接失败");
    }
    mysqli_query($con,"set names 'utf8' ");
    mysqli_query($con,"set character_set_client=utf8");
    mysqli_query($con,"set character_set_results=utf8");

    $total;
    $result=mysqli_query($con,"select count(*) as total from temp");
    

    $strNumRes=mysqli_query($con,"select count(*) as strnum from (select strname from temp group by strname) as a");
    $strNumArr=mysqli_fetch_array($strNumRes);
    $strNum=$strNumArr["strnum"];//街道的个数，即一共需要多少个数据  
    
    $resultArray = array($strNum=>6);

    $strNameRes=mysqli_query($con,"select strname from temp group by strname");
    //$resultArray[$n][0]为街道名称存储数组单元
    $n=0;

    while($strNameArr=mysqli_fetch_array($strNameRes)){
        $resultArray[$n][0]=$strNameArr["strname"];//街道名称数组赋值
        $n++;
    };
    


    
    //$resultArray[$n][1]为经度存储数组单元
    //$resultArray[$n][2]为纬度存储数组单元
    //$resultArray[$n][3]为最多车品牌存储数组单元
    //$resultArray[$n][4]为平均价格存储数组单元
    for($n=0;$n<$strNum;$n++){
        /* 数据库查询语句 */
        $lonselect = "select lon from temp where strname='".$resultArray[$n][0]."'";
        $latselect = "select lat from temp where strname='".$resultArray[$n][0]."'";
        $brandselect = "select brand,count(brand) as count from temp where strname='".$resultArray[$n][0]."' group by brand ORDER BY count DESC";
        $averpriceselect = "select avg(averprice) as price from temp where strname='".$resultArray[$n][0]."'";
        /* 数据库查询语句 */

        /* 查询结果返回 */
        $lonRes=mysqli_query($con,$lonselect);
        $latRes=mysqli_query($con,$latselect);
        $brandRes=mysqli_query($con,$brandselect);
        $averpriceRes=mysqli_query($con,$averpriceselect);
        /* 查询结果返回 */

        /* 返回结果赋值 */
        $lonArr=mysqli_fetch_array($lonRes);
        $resultArray[$n][1] = $lonArr["lon"];
        $latArr=mysqli_fetch_array($latRes);
        $resultArray[$n][2] = $latArr["lat"];
        $brandArr=mysqli_fetch_array($brandRes);
        $resultArray[$n][3] = $brandArr["brand"];
        $averpriceArr=mysqli_fetch_array($averpriceRes);
        $resultArray[$n][4] = $averpriceArr["price"];
        /* 返回结果赋值 */
    }
 
    $jsonres = json_encode($resultArray);
    mysqli_close($con);
?>

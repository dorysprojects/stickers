<?php

function kcount($array_or_countable, $mode=COUNT_NORMAL){
    if(is_array($array_or_countable) || is_object($array_or_countable)){
        return count($array_or_countable, $mode);
    }else if($array_or_countable != ''){
         return 1;
    }else{
        return 0;
    }
}
        
$project = $_GET['project'];
$module = $_GET['module'];

$NO = $_GET['NO'];
$pic_url = $_GET['pic_url'];
$userId = $_GET['userId'];

$link = mysqli_connect("localhost","customer","allproducts");//連結伺服器
mysqli_select_db($link, "Line_ricky");//選擇資料庫
mysqli_query($link, "set names utf8");//以utf8讀取資料，讓資料可以讀取中文

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

if($module == 'flex'){
    mysqli_query($link, "DELETE FROM flex_list WHERE NO = '$NO' and userId = '$userId'");
}else{
    $select = mysqli_query($link, "SELECT * FROM pic_list WHERE pic_url = '$pic_url' and userId = '$userId'");

    if( (kcount(explode($_SERVER['HTTP_HOST'],$pic_url))>1) && (mysqli_num_rows($select)>0) ){
        $pic_url2 = '.' . substr($pic_url, 39);
        if(file_exists($pic_url2)){
            unlink($pic_url2);
        }
    }
    mysqli_query($link, "DELETE FROM pic_list WHERE pic_url = '$pic_url' and userId = '$userId'");
}

echo "<script>
            //location.href = 'home.php';
            location.href = 'home.php?project=$project' + '&displayName=' + '$displayName' + '&pic_url=' + '$pic_url' + '&pictureUrl=' + '$pictureUrl' + '&statusMessage=' + '$statusMessage' + '&userId=' + '$userId';
      </script>";
?>
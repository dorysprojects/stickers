<?php
$module = $_GET['module'];

$NO = $_GET['NO'];
$pic_url = $_GET['pic_url'];
$favorite = $_GET['favorite'];

$link = mysqli_connect("localhost","customer","allproducts");//連結伺服器
mysqli_select_db($link, "Line_ricky");//選擇資料庫
mysqli_query($link, "set names utf8");//以utf8讀取資料，讓資料可以讀取中文

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

if($module == 'flex'){
    mysqli_query($link, "UPDATE flex_list SET favorite='$favorite' WHERE NO='$NO'");
}else if($module == 'note'){
    mysqli_query($link, "UPDATE note_list SET favorite='$favorite' WHERE NO='$NO'");
}else{
    $gethistory = mysqli_query($link, "select * from pic_list where userId like '$userId'");

    $count = $userId . "_" . (mysqli_num_rows($gethistory)+1);

    mysqli_query($link, "UPDATE pic_list SET favorite='$favorite' WHERE pic_url='$pic_url'");
}
?>
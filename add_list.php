<?php
$displayName = $_GET['displayName'];
$pictureUrl = $_GET['pictureUrl'];
$statusMessage = $_GET['statusMessage'];
$userId = $_GET['userId'];
$pic_url = $_GET['pic_url'];
date_default_timezone_set('Asia/Taipei');
$datetime = date('Y-m-d h:i:sa');
//$last_pictureUrl = $_GET['last_pictureUrl'];

$link = mysqli_connect("localhost","customer","allproducts");//連結伺服器
mysqli_select_db($link, "Line_ricky");//選擇資料庫
mysqli_query($link, "set names utf8");//以utf8讀取資料，讓資料可以讀取中文

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$gethistory = mysqli_query($link, "select * from pic_list where userId like '$userId'");

$count = $userId . "_" . (mysqli_num_rows($gethistory)+1);
//if($last_pictureUrl == ''){
    mysqli_query($link, "REPLACE INTO pic_list (displayName,pictureUrl,statusMessage,userId,pic_url,count,datetime,favorite) VALUES('$displayName','$pictureUrl','$statusMessage','$userId','$pic_url','$count','$datetime','false')");
/*}else{
    mysqli_query($link, "UPDATE pic_list SET pic_url='$pic_url' WHERE pic_url='$last_pictureUrl'");
}*/

echo "<script>
            location.href = 'Signature%20Pad%20demo.php?project=手畫貼圖' + '&displayName=' + '$displayName' + '&pic_url=' + '$pic_url' + '&pictureUrl=' + '$pictureUrl' + '&statusMessage=' + '$statusMessage' + '&userId=' + '$userId' + '&datetime=' + '$datetime';
      </script>"
?>
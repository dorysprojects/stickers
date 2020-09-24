<?php

session_start();
$admin = $_GET['admin'];
$userId = $_GET['userId'];
$displayName = $_GET['displayName'];
$pictureUrl = $_GET['pictureUrl'];
$level = 'user';

date_default_timezone_set('Asia/Taipei');
$datetime = date('Y-m-d h:i:sa');

if($admin){
    if($userId){
        $_SESSION['redirect'] = NULL;
        $_SESSION['data'] = NULL;
        $update = 0;
        
        $link = mysqli_connect("localhost","customer","allproducts");//連結伺服器
        mysqli_select_db($link, "Line_ricky");//選擇資料庫
        mysqli_query($link, "set names utf8");//以utf8讀取資料，讓資料可以讀取中文
        
        if ($link->connect_error) {
            die("Connection failed: " . $link->connect_error);
        }
        
        if($userId != $admin){
            $gethistory = mysqli_query($link, "select * from note_list where level='$level' AND prevlevel='$admin' AND userId='$userId'");
            if(mysqli_num_rows($gethistory) < 1){
                $update = 1;
            }
        }
        if($update == 1){
            mysqli_query($link, "REPLACE INTO note_list (displayName,pictureUrl,statusMessage,userId,name,count,datetime,favorite,prevlevel,level,data) VALUES('$displayName','$pictureUrl','$statusMessage','$userId','$name','$count','$datetime','false','$admin','$level','$data')");
            echo "<script>alert('你已成為管理者，請關閉視窗');</script>";
        }else{
            echo "<script>alert('你已是管理者');</script>";
        }
    }else{
        $LineID = '@ypm8594w';
        $ChannelID = '1577298076';
        $client_secret = '11c361890297a9c1ca3a1e5b9c0d3c58';
        $redirect_uri = 'https://rickytest.gadclubs.com/stickers/callback.php';

        $authorizeURL = 'https://access.line.me/oauth2/v2.1/authorize?';
        $authorizeURL .= 'response_type=code';
        $authorizeURL .= '&client_id=' . $ChannelID;
        $authorizeURL .= '&redirect_uri=' . $redirect_uri;
        $authorizeURL .= '&state=abcde';
        $authorizeURL .= '&scope=openid%20profile';
        
        $_SESSION['redirect'] = 'https://rickytest.gadclubs.com/stickers/sharenote.php';
        $_SESSION['data'] = '{"admin" : "'.$admin.'"}';
        
        /*
         * linelogin
         */
        header("Location: " . $authorizeURL);
    }
}

?>
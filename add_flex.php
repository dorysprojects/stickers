<?php
$module = $_GET['module'];
$action = $_GET['action'];
$NO = $_GET['NO'];

$displayName = $_GET['displayName'];
$pictureUrl = $_GET['pictureUrl'];
$statusMessage = $_GET['statusMessage'];
$userId = $_GET['userId'];

$flex_json = $_GET['flex_json'];

$name = $_GET['name'];
$prevlevel = $_GET['prevlevel'];
$level = $_GET['level'];
$data = $_GET['data'];

date_default_timezone_set('Asia/Taipei');
$datetime = date('Y-m-d h:i:sa');

$link = mysqli_connect("localhost","customer","allproducts");//連結伺服器
mysqli_select_db($link, "Line_ricky");//選擇資料庫
mysqli_query($link, "set names utf8");//以utf8讀取資料，讓資料可以讀取中文

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

if($module == 'note'){
    $gethistory = mysqli_query($link, "select * from note_list where userId like '$userId'");

    $count = $userId . "_" . (mysqli_num_rows($gethistory)+1);
    
    switch ($level) {
        case 'user':
            mysqli_query($link, "DELETE FROM note_list WHERE NO = '$NO' and prevlevel = '$userId'");
            break;
        case 'note':
            switch ($action) {
                case 'add':
                    mysqli_query($link, "REPLACE INTO note_list (displayName,pictureUrl,statusMessage,userId,name,count,datetime,favorite,prevlevel,level,data) VALUES('$displayName','$pictureUrl','$statusMessage','$userId','$name','$count','$datetime','false','$prevlevel','$level','$data')");
                    break;
                case 'edit':
                    mysqli_query($link, "UPDATE note_list SET name='$name',data='$data' WHERE NO='$NO'");
                    break;
                case 'delete':
                    mysqli_query($link, "DELETE FROM note_list WHERE NO = '$NO' and userId = '$userId'");
                    break;
            }
            break;
        case 'category':
            switch ($action) {
                case 'add':
                    mysqli_query($link, "REPLACE INTO note_list (displayName,pictureUrl,statusMessage,userId,name,count,datetime,favorite,prevlevel,level) VALUES('$displayName','$pictureUrl','$statusMessage','$userId','$name','$count','$datetime','false','$prevlevel','$level')");
                    break;
                case 'edit':
                    mysqli_query($link, "UPDATE note_list SET name='$name' WHERE NO='$NO'");
                    break;
                case 'delete':
                    mysqli_query($link, "DELETE FROM note_list WHERE NO = '$NO' and userId = '$userId'");
                    break;
            }
            break;
        case 'subcategory':
            switch ($action) {
                case 'add':
                    mysqli_query($link, "REPLACE INTO note_list (displayName,pictureUrl,statusMessage,userId,name,count,datetime,favorite,prevlevel,level) VALUES('$displayName','$pictureUrl','$statusMessage','$userId','$name','$count','$datetime','false','$prevlevel','$level')");
                    break;
                case 'edit':
                    mysqli_query($link, "UPDATE note_list SET name='$name',prevlevel='$prevlevel' WHERE NO='$NO'");
                    break;
                case 'delete':
                    mysqli_query($link, "DELETE FROM note_list WHERE NO = '$NO' and userId = '$userId'");
                    break;
            }
            break;
    }
    
    echo "<script>
                location.href = 'Signature%20Pad%20demo.php?project=筆記本' + '&displayName=' + '$displayName' + '&name=' + '$name' + '&pictureUrl=' + '$pictureUrl' + '&statusMessage=' + '$statusMessage' + '&userId=' + '$userId' + '&datetime=' + '$datetime';
          </script>";
}else{
    $gethistory = mysqli_query($link, "select * from flex_list where userId like '$userId'");

    $count = $userId . "_" . (mysqli_num_rows($gethistory)+1);
    if($action == 'add'){
        mysqli_query($link, "REPLACE INTO flex_list (displayName,pictureUrl,statusMessage,userId,flex_json,count,datetime,favorite) VALUES('$displayName','$pictureUrl','$statusMessage','$userId','$flex_json','$count','$datetime','false')");
    }else if($action == 'edit'){
        mysqli_query($link, "UPDATE flex_list SET flex_json='$flex_json' WHERE NO='$NO'");
    }

    echo "<script>
                location.href = 'Signature%20Pad%20demo.php?project=訊息DIY' + '&displayName=' + '$displayName' + '&pictureUrl=' + '$pictureUrl' + '&statusMessage=' + '$statusMessage' + '&userId=' + '$userId' + '&datetime=' + '$datetime';
          </script>";
}
?>
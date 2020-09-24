<!DOCTYPE html>
<html lang="en">


<head>
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
        
        $ProjectName = ($_GET['project']) ? $_GET['project'] : '手畫貼圖';
    ?>
    <title><?php echo $ProjectName; ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <link rel="stylesheet" href="./res/farbtastic.css" type="text/css" />  
    <link rel="stylesheet" href="./res/signature-pad.css">
    
    <link rel="stylesheet" type="text/css" href="./res/loaders.min.css"><!--https://cdn.rawgit.com/ConnorAtherton/loaders.css/master/-->
    
    <script type="text/javascript" src="./res/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-39365077-1']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>

<style>
    .farbtastic {
        position: relative;
        display: inline-block;
    }
    
    .selector {
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 4px;
        color: #555555;
        font-size: 16px;
        margin: 10px;
        padding: 5px 25px;
        outline-offset: -2px;
        outline: -webkit-focus-ring-color auto 5px;
    }
    
    .X {
        position:absolute;
        top: 0;
        right:0;
        padding: 7px 12px;
        font-size:25px;
        color:#fff;
    }
    
    .button {
        display: inline-block;
        background-color: #f58e31;
        color: #fff;
        padding: 10px 15px;
        margin: 5px;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 1px 3px;
        border: 1px #f58e31 solid;
        transition: all .6s cubic-bezier(0.4,0,0.2,1);
    }
    
    #addarea input[type="checkbox"]:checked + label .button {
        background-color: #fff;
        color: #f58e31;
        border: 1px #f58e31 solid;
    }
    
    .pic_block, .flex_block {
        width:25%;
        float:left;
        display:inline-block;
        border:1px #bbb solid;
        border-top: 0px;
        cursor: pointer;
    }
    
    .manager_block {
        width:25%;
        float:left;
        display:inline-block;
        border:1px #bbb solid;
        border-top: 0px;
        cursor: pointer;
        touch-action: pan-y;
        user-select: none;
        -webkit-user-drag: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }
    
    .category_block .category_title, .subcategory_block .subcategory_title {
        background-color: #d9edf7;
        color: #31708f;
        border-color: #bce8f1;
        font-size: 20px;
        font-weight: bold;
        border: 1px solid transparent;
        border-radius: 4px;
        cursor: pointer;
        padding: 10px;
        margin-top: 5px;
    }
    
    .subcategory_block .subcategory_title {
        background-color: #dff0d8;
        color: #3c763d;
        border-color: #d6e9c6;
    }
    
    .subcategory_block {
        display: none;
    }
    
    .category_btn, .subcategory_btn {
        font-size: 30px;
        position: absolute;
        right: 15px;
        margin-top: -47px;
    }
    
    .note_block {
        display: none;
        border-bottom: 1px #bbb solid;
        cursor: pointer;
        padding: 10px;
    }
    .note {
        width: 100%;
        display: inline-block;
    }
    .note span {
        font-size: 18px;
        font-weight: bold;
    }
    
    .pic, .flex {
        width:100%;
        line-height: 70px;
        height:70px;
        text-align: center;
        float:left;
        display:inline-block;
    }
    
    .flex span {
        font-size: 25px;
        font-weight: bold;
        text-align: center;
    }
    
    .name {
        text-align:center;
        background-color:#bbb;
        color:#fff;
        float:left;
        width:100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 12px;
        height: 20px;
        line-height: 20px;
        border: 1px #fff solid;
    }
    
    .press_button_area {
        width:70%;
        /*height:50%;*/
        background-color:rgba(167, 167, 167, 0.9);
        border-radius:20px;
        position:absolute;
        z-index:1;
        top:15%;
        left:15%;
    }
    
    .press_button {
        padding:15px;
        color:#fff;
        font-size:16px;
        border-bottom:1px #fff solid;
        text-align:center;
    }
    
    .press_background {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0px;
        right: 0px;
        padding: 16px;
        z-index: 1;
        background-color: rgba(255, 255, 255, 0.9);
    }
    
    .pic_press {
        width: 100%;
        background-color: #fff;
        border: 2px #bbb solid;
        border-bottom: 1px #fff solid;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }
    
    .profile {
        text-align: center;
        font-size: 16px;
        background-color: #fff;
        padding: 10px;
        border: 1px rgba(167, 167, 167, 0.9) solid;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .the_profile_pic {
        width: 80%;
        background-color: #fff;
        border: 2px #fff solid;
        margin-top: 4px;
    }
    
    .X {
        position: absolute;
        right: 0;
        padding: 7px 12px;
    }
    
    .btn_info {
        color: #f58e31;
        background-color: #fff;
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        touch-action: manipulation;
        cursor: pointer;
        user-select: none;
        background-image: none;
        border: 1px #f58e31 solid;
        border-radius: 4px;
        width: -webkit-fill-available;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .tips {
        width:90%;
        position: fixed;
        top: 5px;
        right: 5%;
        background-color: #aba57f;
        color: #fff;
        padding: 5px 0px;
        padding-left: 5px;
        z-index: 1;
    }
    
    .change_tips {
        overflow: scroll;
        white-space: nowrap;
        display: inline-table;
        height: 19px;
    }
    .tips_area{
        display: inline-grid;
        overflow: scroll;
        width: 60%;
        height: 19px;
        font-size: 12px;
    }
    
    .word_div {
        text-align: left;
        display: inline-block;
        padding: 5px;
        padding-bottom: 0px;
        width: 85%;
        font-size: 20px;
        color: #fff;
        margin-top: 5px;
    }
    
    .word_input {
        border: none;
        border-bottom: 2px #f58e31 solid;
        padding: 5px;
        font-size: 16px;
        width: 85%;
        background-color: #fff;
        border-radius:0px;
    }
    
    #loading {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.9);
        width: 100%;
        height: 100%;
        border-radius: 7px;
        position: fixed;
        top: 0;
        z-index: 1;
    }
    
    #loading_div {
        top: 35%;
        left: 35%;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.78);
        padding: 15px 20px;
        border-radius: 7px;
        position: fixed;
        z-index: 1;
    }
    
    html, body {
        /*background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAb1UlEQ…bhb8fIWpwxQqLWjmq6eMz55lB3Fsy5IgTGIO1SZ7D0vXj/D1qZ7VFrqtW0AAAAAElFTkSuQmCC) repeat scroll center center #b3b3b3;*/
        background-color: #b3b3b3;
        color: #6f7c82;
    }
    
    #flex_Weight, #flex_Align {
        text-align: left;
        display: inline-block;
        width: 85%;
    }
    #flex_Weight input[type="radio"], #flex_Align input[type="radio"] {
        display: none;
    }
    #flex_Weight input[type="radio"] + label, #flex_Align input[type="radio"] + label {
        cursor: pointer;
        display: inline-block;
        background-color: #fff;
        color: #f58e31;
        padding: 10px 15px;
        margin: 5px;
        border: 1px #f58e31 solid;
        border-radius: 7px;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 1px 3px;
        transition: all .6s cubic-bezier(0.4,0,0.2,1);
    }
    #flex_Weight input[type="radio"]:checked + label, #flex_Align input[type="radio"]:checked + label {
        background-color: #f58e31;
        color: #fff;
    }
    
    .EditArea {
        display:none;
        background-color:rgba(0, 0, 0, 0.78);
        position:absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        padding:100px 0px;
        z-index: 3;
    }

    #flex_send_Text, #flex_send_altText {
        width: 50%;/*auto*/
        border: 1px solid #f58e31;
        border-bottom: 2px #f58e31 solid;
    }
    
    .Select {
        border: 2px #f58e31 solid;
    }
    
    .TextSize {
        font-size: 14px!important;
        position: absolute;
        top: -25px;
        margin-left: -10px;
    }
    
    .Page {
        background-color: #fff;
        color: #f58e31;
        border: 1px #f58e31 solid;
        font-weight: bold;
    }
    
    .CategorySelect, .SubCategorySelect, .NoteSelect {
        border: 2px #f58e31 solid;
    }
    
</style>

<?php
    $displayName = $_GET['displayName'];
    $pictureUrl = $_GET['pictureUrl'];
    $statusMessage = $_GET['statusMessage'];
    $userId = $_GET['userId'];
    $pic_url = $_GET['pic_url'];
    $imgur_result = $_GET['imgur_result'];

    $link = mysqli_connect("localhost", "customer", "allproducts"); //連結伺服器
    mysqli_select_db($link, "Line_ricky"); //選擇資料庫
    mysqli_query($link, "set names utf8"); //以utf8讀取資料，讓資料可以讀取中文
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }
    $get_all_history = mysqli_query($link, "select * from pic_list ORDER BY displayName,favorite DESC,datetime DESC");
    $store_all_name = [];
    $store_all_profile_pic = [];
    $store_all_profile_statusMessage = [];
    $store_all_pic = [];
    $store_all_datetime = [];
    $store_all_favorite = [];
    for ($b = 0; $b < mysqli_num_rows($get_all_history); $b++) {
        $rs_all_history = mysqli_fetch_row($get_all_history);
        array_push($store_all_name, $rs_all_history[1]);
        array_push($store_all_profile_pic, $rs_all_history[2]);
        array_push($store_all_profile_statusMessage, $rs_all_history[3]);
        array_push($store_all_pic, $rs_all_history[5]);
        array_push($store_all_datetime, $rs_all_history[7]);
        array_push($store_all_favorite, $rs_all_history[8]);
    }
    $store_names = array_unique($store_all_name);
    $search_store_all_names = array_count_values($store_all_name);

    if ($userId != '') {
        $gethistory = mysqli_query($link, "select * from pic_list where userId like '$userId' ORDER BY favorite DESC,datetime DESC");
        $store_list = [];
        $store_datetime = [];
        for ($c = 0; $c < mysqli_num_rows($gethistory); $c++) {
            $rs_history = mysqli_fetch_row($gethistory);
            array_push($store_list, $rs_history);
            array_push($store_datetime, $rs_history[7]);
        }
        
        $gethistory2 = mysqli_query($link, "select * from flex_list where userId like '$userId' ORDER BY favorite DESC,datetime DESC");
        $flex_list = [];
        for ($c = 0; $c < mysqli_num_rows($gethistory2); $c++) {
            $rs_history2 = mysqli_fetch_row($gethistory2);
            array_push($flex_list, $rs_history2);
        }
        
        /*
         * 誰可以看我的
         */
        $manager_list = [];
        $getmanagers = mysqli_query($link, "select * from note_list where level='user' AND prevlevel='$userId'");
        for ($c = 0; $c < mysqli_num_rows($getmanagers); $c++) {
            $rs_manager = mysqli_fetch_row($getmanagers);
            if($rs_manager[4]){
                array_push($manager_list, $rs_manager);
            }
        }
        
        /*
         * 我可以看誰的
         */
        $View_list = [];
        $getviewers = mysqli_query($link, "select * from note_list where level='user' AND userId='$userId'");
        for ($c = 0; $c < mysqli_num_rows($getviewers); $c++) {
            $rs_viewer = mysqli_fetch_row($getviewers);
            if($rs_viewer[9]){
                array_push($View_list, $rs_viewer);
            }
        }
        
        $SelectList = "userId = '$userId'";
        if($View_list){
            foreach ($View_list as $key => $value) {
                $SelectList .= " OR userId = '$value[9]'";
            }
        }
        
        $gethistory3 = mysqli_query($link, "select * from note_list where $SelectList ORDER BY favorite DESC,datetime DESC");
        $category_list = [];
        $subcategory_list = [];
        $note_list = [];
        for ($c = 0; $c < mysqli_num_rows($gethistory3); $c++) {
            $rs_history3 = mysqli_fetch_row($gethistory3);
            if($rs_history3[10] == 'category'){
                array_push($category_list, $rs_history3);
            }else if($rs_history3[10] == 'subcategory'){
                array_push($subcategory_list, $rs_history3);
            }else if($rs_history3[10] == 'note'){
                array_push($note_list, $rs_history3);
            }
        }
    }

    $family = array(
        "標楷體",
        "新細明體",
        "文鼎超明",
        "黑體",
        "宋體",
        "微軟雅黑",
    );
    
    $size = array(
        "xxs",
        "xs",
        "sm",
        "md",
        "lg",
        "xl",
        "xxl",
        "3xl",
        "4xl",
        "5xl"
    );
    
    $BubbleSize = array(
        "giga",
        "mega",
        "kilo",
        "micro",
        "nano"
    );
    
    $BubbleSizeZhTw = array(
        "giga" => "大",
        "mega" => "一般",
        "kilo" => "中",
        "micro" => "小",
        "nano" => "超小"
    );
    
?>

<body onselectstart="return false">
    <div id="tips" class="tips">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 20 20" style="vertical-align:-6px;margin-right:5px;">
            <path fill="#fff" d="M18 6.793l-5.293-5.293c-0.188-0.188-0.44-0.292-0.707-0.292s-0.519 0.104-0.707 0.292l-0.293 0.293c-0.29 0.29-0.5 0.797-0.5 1.207v1c0 0.142-0.106 0.399-0.207 0.5l-2.793 2.793c-0.101 0.101-0.358 0.207-0.5 0.207h-1c-0.41 0-0.917 0.21-1.207 0.5l-0.293 0.293c-0.39 0.39-0.39 1.024 0 1.414l1.553 1.553-4.95 6.435c-0.153 0.199-0.135 0.481 0.043 0.658 0.097 0.097 0.225 0.146 0.354 0.146 0.107 0 0.214-0.034 0.305-0.104l6.435-4.95 1.553 1.553c0.188 0.188 0.44 0.292 0.707 0.292s0.519-0.104 0.707-0.292l0.293-0.293c0.29-0.29 0.5-0.797 0.5-1.207v-1c0-0.142 0.106-0.399 0.207-0.5l2.793-2.793c0.101-0.101 0.358-0.207 0.5-0.207h1c0.41 0 0.917-0.21 1.207-0.5l0.293-0.293c0.188-0.188 0.292-0.44 0.292-0.707s-0.104-0.519-0.292-0.707zM4.234 15.266l2.533-3.293 0.76 0.76-3.293 2.533zM17 7.793c-0.101 0.101-0.358 0.207-0.5 0.207h-1c-0.41 0-0.917 0.21-1.207 0.5l-2.793 2.793c-0.29 0.29-0.5 0.797-0.5 1.207v1c0 0.142-0.106 0.399-0.207 0.5l-0.292 0.292c-0 0-0.001 0-0.001 0v0.001l-5.293-5.293 0.293-0.293c0.101-0.101 0.358-0.207 0.5-0.207h1c0.41 0 0.917-0.21 1.207-0.5l2.793-2.793c0.29-0.29 0.5-0.797 0.5-1.207v-1c0-0.142 0.106-0.399 0.207-0.5l0.293-0.293 5.293 5.293-0.293 0.293z"/>
        </svg>
        <span>小提示:</span>
        <div id="tips_area" class="tips_area">
            <div id="tips_1" class="change_tips">長按清單中的圖片有其他功能</div>
            <div id="tips_2" class="change_tips">上傳檔案格式目前僅支援 [ .png、.jpg、.jpeg、.gif ] </div>
            <div id="tips_3" class="change_tips">清單 -> 雙擊兩下圖片即可( 加入/移除 )我的最愛</div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 20 20" style="vertical-align:-6px;margin-left:5px;transform:rotate(-90deg);transition:transform 0.5s ease-in;position:absolute;top:5px;" onclick="tips_switch(this);">
            <path fill="#fff" d="M0 15c0 0.128 0.049 0.256 0.146 0.354 0.195 0.195 0.512 0.195 0.707 0l8.646-8.646 8.646 8.646c0.195 0.195 0.512 0.195 0.707 0s0.195-0.512 0-0.707l-9-9c-0.195-0.195-0.512-0.195-0.707 0l-9 9c-0.098 0.098-0.146 0.226-0.146 0.354z"/>
        </svg>
    </div>
    <div id="enlarge_pic" style="height:100%;width:100%;position:absolute;z-index:2;background-color:rgba(0, 0, 0, 0.78);padding-top:50px;display:none;">
        <div id="X1" onclick="X1(this);" class="X">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"></path>
            </svg>
        </div>
        <img id="the_enlarge_pic" src="" class="pic_press" style="border-radius:0px;">
    </div>
    <div id="upload_area" style="position:absolute;z-index:1;width:100%;height:100%;/*text-align:center;*/background-color:rgba(255, 255, 255, 0.9);padding:25px;display:none;">
        <div id="X2" onclick="X2(this);" class="X">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                <path fill="#f58e31" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"></path>
            </svg>
        </div>
        <div style="padding:10px 0px;font-size:20px;font-weight:bold;color:#f58e31;">上傳本地檔案:</div>
        <form id="upload_form" action="img_callback4.php?userId=<?php echo $userId; ?>" enctype="multipart/form-data" method="POST" style="text-align:center;">
            <label class="btn_info">
                <input type="file" name="fileData" size="35" accept="image/*" style="display:none;" onchange="upload_file(this);">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 20 20">
                    <path fill="#f58e31" d="M18.5 20h-17c-0.827 0-1.5-0.673-1.5-1.5v-17c0-0.827 0.673-1.5 1.5-1.5h17c0.827 0 1.5 0.673 1.5 1.5v17c0 0.827-0.673 1.5-1.5 1.5zM1.5 1c-0.276 0-0.5 0.224-0.5 0.5v17c0 0.276 0.224 0.5 0.5 0.5h17c0.276 0 0.5-0.224 0.5-0.5v-17c0-0.276-0.224-0.5-0.5-0.5h-17z"/>
                    <path fill="#f58e31" d="M13 9c-1.103 0-2-0.897-2-2s0.897-2 2-2 2 0.897 2 2-0.897 2-2 2zM13 6c-0.551 0-1 0.449-1 1s0.449 1 1 1 1-0.449 1-1-0.449-1-1-1z"/>
                    <path fill="#f58e31" d="M17.5 2h-15c-0.276 0-0.5 0.224-0.5 0.5v12c0 0.276 0.224 0.5 0.5 0.5h15c0.276 0 0.5-0.224 0.5-0.5v-12c0-0.276-0.224-0.5-0.5-0.5zM3 11.69l3.209-3.611c0.082-0.092 0.189-0.144 0.302-0.145s0.221 0.048 0.305 0.138l5.533 5.928h-9.349v-2.31zM17 14h-3.283l-6.169-6.61c-0.279-0.299-0.651-0.461-1.049-0.456s-0.766 0.176-1.037 0.481l-2.462 2.77v-7.185h14v11z"/>
                </svg>
                <span style="vertical-align:30%;margin-left:5px;">選擇圖片<span>
                <span id="file_result" style="margin-left:10px;"></span>
            </label>
            <label id="get_link" class="btn_info" style="margin-top:5px;width:auto;padding:10px 40px;display:none;">
                <input type="submit" name="submit" value="產生連結" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20" height="20" viewBox="0 0 20 20">
                    <path fill="#f58e31" d="M10.682 12.998c-0.943 0-1.886-0.359-2.604-1.077-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0c1.046 1.046 2.747 1.046 3.793 0l3.636-3.636c1.046-1.046 1.046-2.747 0-3.793s-2.747-1.046-3.793 0l-3.068 3.068c-0.195 0.195-0.512 0.195-0.707 0s-0.195-0.512 0-0.707l3.068-3.068c1.436-1.436 3.772-1.436 5.207 0s1.436 3.772 0 5.207l-3.636 3.636c-0.718 0.718-1.661 1.077-2.604 1.077z"/>
                    <path fill="#f58e31" d="M4.682 18.998c-0.943 0-1.886-0.359-2.604-1.077-1.436-1.436-1.436-3.772 0-5.207l3.636-3.636c1.436-1.436 3.772-1.436 5.207 0 0.195 0.195 0.195 0.512 0 0.707s-0.512 0.195-0.707 0c-1.046-1.046-2.747-1.046-3.793 0l-3.636 3.636c-1.046 1.046-1.046 2.747 0 3.793s2.747 1.046 3.793 0l3.068-3.068c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-3.068 3.068c-0.718 0.718-1.661 1.077-2.604 1.077z"/>
                </svg>
                <span style="vertical-align:30%;margin-left:5px;">產生圖片網址<span>
            </label>
        </form>
        <div style="padding:10px 0px;font-size:20px;font-weight:bold;color:#f58e31;">圖片網址:</div>
        <input id="imgur_result" type="textbox" id="keyin_url" placeholder="建議上傳PNG、GIF檔案" style="width:-webkit-fill-available;border:none;border-bottom:1px #f58e31 solid;padding:5px 5px 0px 5px;font-size:16px;height:30px;">
        <div id="send_url" style="text-align:center;padding:10px;margin:15px 0px;border:1px #f58e31 solid;color:#f58e31;font-weight:bold;border-radius:20px;" onclick="send_url();">送出</div>
    </div>
    <iframe id="favorite_iframe" src="add_favorite.php" style="display:none;"></iframe>
    <div id="signature-pad" class="signature-pad">
        <div style="display:none;">
            <select id="choose_user" class="selector" onchange="choose_user(this);">
                <option value="全部">全部 (<?php echo kcount($store_all_name); ?>)</option>
                <?php for ($uar = 0; $uar <= kcount($store_all_name); $uar++) { ?>
                    <?php if ($store_names[$uar] != '') { ?>
                        <option value="<?php echo $store_names[$uar]; ?>"><?php echo $store_names[$uar] . ' (' . $search_store_all_names[$store_names[$uar]] . ')'; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <?php if (!$userId) { ?>
            <div id="project_details" class="press_background">
                <div class="press_button_area">
                    <div class="press_button" onclick="SelectProject($(this).html());">手畫貼圖</div>
                    <div class="press_button" onclick="SelectProject($(this).html());">訊息DIY</div>
                    <div class="press_button" onclick="SelectProject($(this).html());">筆記本</div>
                </div>
            </div>
        <?php } ?>
        <div id="pic_details" class="press_background" style="display:none;">
            <div class="press_button_area">
                <div class="press_button" style="border:1px #a7a7a7 solid;border-bottom:1px;border-radius:12px 12px 0px 0px;color: #a7a7a7;background-color:#fff;">日期</div>
                <img src="" class="pic_press" style="border-top-left-radius:0px;border-top-right-radius:0px;">
                <div id="pic_press_cancel" class="press_button" style="border-top:1px #fff solid;" onclick="pic_press_cancel();">關閉</div>
            </div>
        </div>
        <div id="profile_details" class="press_background" style="display:none;">
            <div class="press_button_area" style="text-align:center;">
                <img src="" class="the_profile_pic" onerror="this.src = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNf0Teecy2bqycPmLAMFuMoOd29LRhR4kKSofmjQq5TxDIXge_xw';">
                <div id="name" class="profile" style="font-size:20px;"></div>
                <div id="profile_statusMessage" class="profile"></div>
                <div id="profile_press_cancel" style="border-top:1px #fff solid;" class="press_button" onclick="profile_press_cancel();">關閉</div>
            </div>
        </div>
        <div id="press_background" class="press_background" style="display:none;">
            <div id="press_button_area" class="press_button_area">
                <div id="check_profile" class="press_button" onclick="check_profile();">查看個人資料</div>
                <div id="add_favorite_btn" class="press_button" onclick="add_favorite();">加入我的最愛</div>
                <div id="check_pic" class="press_button" onclick="check_pic();">查看圖片</div>
                <div id="go_pic_link" class="press_button" onclick="go_pic_link();">前往圖片位址</div>
                <div id="press_modify" class="press_button" onclick="press_modify();">編輯</div>
                <div id="press_delete" class="press_button delete" onclick="press_delete();">刪除</div>
                <div id="all_press_cancel" class="press_button" onclick="$(this).parent().parent().hide();">取消</div>
            </div>
        </div>
        <div id="flex_press_background" class="press_background" style="display:none;">
            <div id="flex_press_button_area" class="press_button_area">
                <div class="press_button" onclick="flex_add_favorite();">加入我的最愛</div>
                <div class="press_button" onclick="flex_press_modify();">編輯</div>
                <div class="press_button delete" onclick="flex_press_delete();">刪除</div>
                <div class="press_button" onclick="$(this).parent().parent().hide();">取消</div>
            </div>
        </div>
        <div id="category_press_background" class="press_background" style="display:none;">
            <div id="category_press_button_area" class="press_button_area">
                <div class="press_button" onclick="AddCategory('edit');">編輯</div>
                <div class="press_button delete" onclick="AddCategory('delete');">刪除</div>
                <div class="press_button" onclick="$(this).parent().parent().hide();">取消</div>
            </div>
        </div>
        <div id="subcategory_press_background" class="press_background" style="display:none;">
            <div id="subcategory_press_button_area" class="press_button_area">
                <div class="press_button" onclick="AddSubCategory('edit');">編輯</div>
                <div class="press_button delete" onclick="AddSubCategory('delete');">刪除</div>
                <div class="press_button" onclick="$(this).parent().parent().hide();">取消</div>
            </div>
        </div>
        <div id="note_press_background" class="press_background" style="display:none;">
            <div id="note_press_button_area" class="press_button_area">
                <div class="press_button" onclick="dbl_push_note();">加入我的最愛</div>
                <div class="press_button" onclick="AddNote('edit');">編輯</div>
                <div class="press_button delete" onclick="AddNote('delete');">刪除</div>
                <div class="press_button" onclick="$(this).parent().parent().hide();">取消</div>
            </div>
        </div>
        <div id="note_add_background" class="press_background" style="display:none;">
            <div id="note_add_background_area" class="press_button_area">
                <div class="press_button" onclick="AddCategory('add');">主類別</div>
                <div class="press_button" onclick="AddSubCategory('add');">次類別</div>
                <div class="press_button" onclick="AddNote('add');">筆記</div>
                <div class="press_button" onclick="$(this).parent().parent().hide();">取消</div>
            </div>
        </div>
        <div class="signature-pad--body" style="overflow:auto;">
            <canvas id="canvas" width="307" height="331" style="touch-action:none;display:block;"></canvas>
            <div id="list_area" style="<?php if($ProjectName == '手畫貼圖'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
                <?php if ($userId != '') { ?>
                    <?php for ($rr = 0; $rr < kcount($store_list); $rr++) { ?>
                        <div id="push_pic<?php echo ($rr + 1); ?>" class="pic_block" onclick="push_pic(this);">
                            <?php if (substr($store_list[$rr][5], -3) == 'mp4') { ?>
                                <!--<video class='pic' crossorigin='anonymous' src='<?php /* echo $store_list[$rr][5]; */ ?>' autoplay></video>
                                <svg width="20" height="20" style="position:absolute;margin-left:-20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500">
                                    <path id="heart<?php /* echo ($rr+1); */ ?>" style="transition:fill ease-in .5s;<?php /* if($store_list[$rr][8] == 'true'){echo 'fill:rgb(206, 61, 61);';}else{echo 'fill:#fff;';} */ ?>" stroke="rgb(206, 61, 61)" stroke-width="40" stroke-linejoin="round" d="M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z"></path>
                                </svg>-->
                                <img class='pic' crossorigin='anonymous' src='<?php echo $store_list[$rr][5]; ?>'>
                                <svg width="20" height="20" style="position:absolute;margin-left:-20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500">
                                    <path id="heart<?php echo ($rr + 1); ?>" style="transition:fill ease-in .5s;<?php if ($store_list[$rr][8] == 'true') {
                                        echo 'fill:rgb(206, 61, 61);';
                                    } else {
                                        echo 'fill:#fff;';
                                    } ?>" stroke="rgb(206, 61, 61)" stroke-width="40" stroke-linejoin="round" d="M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z"></path>
                                </svg>
                            <?php } else { ?>
                                <img class='pic' crossorigin='anonymous' src='<?php echo $store_list[$rr][5]; ?>'>
                                <svg width="20" height="20" style="position:absolute;margin-left:-20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500">
                                    <path id="heart<?php echo ($rr + 1); ?>" style="transition:fill ease-in .5s;<?php if ($store_list[$rr][8] == 'true') {
                                        echo 'fill:rgb(206, 61, 61);';
                                    } else {
                                        echo 'fill:#fff;';
                                    } ?>" stroke="rgb(206, 61, 61)" stroke-width="40" stroke-linejoin="round" d="M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z"></path>
                                </svg>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div id="flex_list" style="<?php if($ProjectName == '訊息DIY'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
                <?php if ($userId != '') { ?>
                    <?php for ($rr = 0; $rr < kcount($flex_list); $rr++) { ?>
                        <div id="push_flex<?php echo ($rr + 1); ?>" class="flex_block" onclick="push_flex($(this));">
                            <?php 
                                $FlexItem = json_decode($flex_list[$rr][5], true);
                                $BGItem = $FlexItem['body']['contents'][0];
                                $TextItem = $BGItem['contents'][0];
                                $Text_Weight = ($TextItem['weight']=='bold') ? '粗' : '細';
                                $Bubble_Size = ($FlexItem['size']) ? $BubbleSizeZhTw[$FlexItem['size']] : $BubbleSizeZhTw['mega'];
                            ?>
                            <div class='flex' id="<?php echo $flex_list[$rr][0]; ?>" NO="<?php echo $flex_list[$rr][0]; ?>" data-json='<?php echo $flex_list[$rr][5]; ?>' style='background-color: <?php echo $BGItem['backgroundColor']; ?>'>
                                <span class="TextSize" style='color: <?php echo $TextItem['color']; ?>'><?php echo $TextItem['size'];?>(<?php echo $Text_Weight;?>)</span>
                                <span style='color: <?php echo $TextItem['color']; ?>'><?php echo $Bubble_Size;?></span>
                            </div>
                            <svg width="20" height="20" style="position:absolute;margin-left:-20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500">
                                <path id="flex_heart<?php echo ($rr + 1); ?>" style="transition:fill ease-in .5s;<?php if ($flex_list[$rr][8] == 'true') {
                                    echo 'fill:rgb(206, 61, 61);';
                                } else {
                                    echo 'fill:#fff;';
                                } ?>" stroke="rgb(206, 61, 61)" stroke-width="40" stroke-linejoin="round" d="M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z"></path>
                            </svg>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div id="note_list" style="<?php if($ProjectName == '筆記本'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
                    <?php if (kcount($note_list) > 0) { ?>
                        <?php for ($category_Ctn = 0; $category_Ctn < kcount($category_list); $category_Ctn++) { ?>
                            <?php 
                                $category_NO = $category_list[$category_Ctn][0];
                                $category_UID = $category_list[$category_Ctn][4];
                                $category_name = $category_list[$category_Ctn][5];
                                $category_favorite = $category_list[$category_Ctn][8];
                                $category_prevlevel = $category_list[$category_Ctn][9];
                            ?>
                            <div id="push_category<?php echo ($category_Ctn + 1); ?>" class="category_block" onclick="push_category($(this));" name="<?php echo $category_name; ?>" NO="<?php echo $category_NO; ?>" prevlevel="<?php echo $category_prevlevel; ?>" UID="<?php echo $category_UID; ?>">
                            <div class="category_title"><?php echo $category_name; ?></div>
                            <div class="category_btn" onclick="if($(this).parent().find('.subcategory_block').eq(0).css('display')=='none'){ $(this).html('-');$(this).parent().find('.subcategory_block').show(); }else{ $(this).html('+');$(this).parent().find('.subcategory_block').hide(); }">+</div>
                            <?php for ($subcategory_Ctn = 0; $subcategory_Ctn < kcount($subcategory_list); $subcategory_Ctn++) { ?>
                                <?php 
                                    $subcategory_NO = $subcategory_list[$subcategory_Ctn][0];
                                    $subcategory_UID = $subcategory_list[$subcategory_Ctn][4];
                                    $subcategory_name = $subcategory_list[$subcategory_Ctn][5];
                                    $subcategory_favorite = $subcategory_list[$subcategory_Ctn][8];
                                    $subcategory_prevlevel = $subcategory_list[$subcategory_Ctn][9];
                                ?>
                                <?php if($subcategory_prevlevel == $category_NO){ ?>
                                    <div id="push_subcategory<?php echo ($subcategory_Ctn + 1); ?>" class="subcategory_block" onclick="push_subcategory($(this));" name="<?php echo $subcategory_name; ?>" NO="<?php echo $subcategory_NO; ?>" prevlevel="<?php echo $subcategory_prevlevel; ?>" UID="<?php echo $category_UID; ?>">
                                    <div class="subcategory_title"><?php echo $subcategory_name; ?></div>
                                    <div class="subcategory_btn" onclick="if($(this).parent().find('.note_block').eq(0).css('display')=='none'){ $(this).html('-');$(this).parent().find('.note_block').show(); }else{ $(this).html('+');$(this).parent().find('.note_block').hide(); }">+</div>
                                    <?php for ($note_Ctn = 0; $note_Ctn < kcount($note_list); $note_Ctn++) { ?>
                                        <?php 
                                            $note_NO = $note_list[$note_Ctn][0];
                                            $note_UID = $note_list[$note_Ctn][4];
                                            $note_name = $note_list[$note_Ctn][5];
                                            $note_favorite = $note_list[$note_Ctn][8];
                                            $note_prevlevel = $note_list[$note_Ctn][9];
                                            $note_data = $note_list[$note_Ctn][11];
                                        ?>
                                        <?php if($note_prevlevel == $subcategory_NO){ ?>
                                            <div id="push_note<?php echo ($note_Ctn + 1); ?>" class="note_block" onclick="push_note($(this));"  NO="<?php echo $note_NO; ?>" name="<?php echo $note_name; ?>" data="<?php echo $note_data; ?>" prevlevel="<?php echo $note_prevlevel; ?>" UID="<?php echo $category_UID; ?>">
                                                <div class='note'>
                                                    <span><?php echo $note_name; ?></span>
                                                </div>
                                                <svg width="20" height="20" style="position:absolute;margin-left:-20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500">
                                                    <path id="note_heart<?php echo ($note_Ctn + 1); ?>" style="transition:fill ease-in .5s;<?php if ($note_favorite == 'true') {
                                                        echo 'fill:rgb(206, 61, 61);';
                                                    } else {
                                                        echo 'fill:#fff;';
                                                    } ?>" stroke="rgb(206, 61, 61)" stroke-width="40" stroke-linejoin="round" d="M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z"></path>
                                                </svg>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
            </div>
        </div>
        <div class="signature-pad--footer">
            <div id="addarea" style="">
                <div id='DrawArea' style="<?php if($ProjectName == '手畫貼圖'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>;">                    
                    <input id="List_input" name="List_input" type="checkbox" style="display:none;" onchange="List(this);">
                    <label for="List_input"><div id="List" class="button">清單</div></label>
                    <input id="add_word_input" name="add_word_input" type="checkbox" style="display:none;" onchange="change_word(this);">
                    <label for="add_word_input"><div id="add_word" class="button">文字</div></label>
                    <div id="choose_color" class="button" onclick="change_color(this);">顏色</div>
                    <div id="choose_width" class="button" onclick="change_width(this);" style="display:none;">粗細</div>
                    <div class="button Page" onclick="$('title').html('訊息DIY');$('#DrawArea').hide();$('#WriteArea').show();$('#list_area').hide();$('#canvas').hide();$('#flex_list').show();if(!$('#List_input').prop('checked') && $('#list_area').children().length==0){$('#List').click();}">＞</div>
                    <div id="the_return" class="button" data-action="the_return">上一步</div>
                    <div id="the_download_btn" class="button" onclick="the_download(this);" style="display:none;">下載</div>
                    <input id="erase_input" name="erase_input" type="checkbox" style="display:none;">
                    <label for="erase_input"><div id="erase" class="button" style="display:none;">像皮擦</div></label>
                    <div id="clear" class="button" data-action="clear_all">清除</div>
                    <div id="upload_btn" class="button" onclick="upload_btn(this);">上傳</div>
                    <div id="send" class="button">傳送</div>
                </div>
                <div id='WriteArea' style="<?php if($ProjectName == '訊息DIY'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
                    <div class="button Page" onclick="$('title').html('手畫貼圖');$('#DrawArea').show();$('#WriteArea').hide();$('#flex_list').hide();if($('#List_input').prop('checked')){$('#list_area').show();}else{$('#canvas').show();}">＜</div>
                    <input id="flex_send_altText" class="word_input" placeholder="預覽文字">
                    <div class="button Page" onclick="$('title').html('筆記本');$('#NoteArea').show();$('#WriteArea').hide();$('#flex_list').hide();$('#note_list').show();">＞</div>
                    </br>
                    <div class="button" style="font-weight: bold;" onclick="ChooseFlex();">＋</div>
                    <input id="flex_send_Text" class="word_input" placeholder="flex內文字">
                    <div class="button" onclick="SendFlex();">傳送</div>
                </div>
                <div id='NoteArea' style="<?php if($ProjectName == '筆記本'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
                    <div class="button Page" onclick="$('title').html('訊息DIY');$('#NoteArea').hide();$('#WriteArea').show();$('#flex_list').show();$('#note_list').hide();">＜</div>
                    <div class="button" style="font-weight: bold;" onclick="$('#note_add_background').show();$('#note_add_background_area').css('top', (($('#note_add_background').outerHeight() - $('#note_add_background_area').outerHeight()) / 2) + 'px');">＋</div>
                    <div class="button" onclick="$('#add_manarger').show();">管理者</div>
                    <div class="button" onclick="SendNote();">傳送</div>
                </div>
                
                
                <div id="change_color" class="EditArea" style="">
                    <div onclick="$(this).parent().hide();" data-action="change_the_color" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <form id="form" style="background-color:#f4f4f4;">
                        <input type="text" id="color" name="color" value="#000000">
                    </form>
                    <div id="picker" style="background-color:#f4f4f4;">
                        <div class="farbtastic">
                            <div class="color" style="background-color: rgb(255, 0, 15);"></div>
                            <div class="wheel"></div>
                            <div class="overlay"></div>
                            <div class="h-marker marker" style="left: 92px; top: 13px;"></div>
                            <div class="sl-marker marker" style="left: 74px; top: 90px;"></div>
                        </div>
                    </div>
                </div>
                <div id="change_width" class="EditArea" style="">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <select id="select_width" class="selector" onchange="select_width(this);" /*data-action="select_width"*/>
                        <?php for ($trg = 1; $trg <= 30; $trg++) { ?>
                            <option value="<?php echo $trg; ?>"><?php echo $trg; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div id="the_download" class="EditArea" style="">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div id="the_png" class="button" data-action="the_png">png</div>
                    <div id="the_jpg" class="button" data-action="the_jpg">jpg</div>
                    <div id="the_svg" class="button" data-action="the_svg">svg</div>
                </div>
                <div id="change_word" class="EditArea" style="padding:50px 0px;">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div class="word_div" style="display:none;">X座標</div>
                    <input id="add_word_x" class="word_input" type="text" placeholder="輸入X" value="0" style="display:none;">
                    <div class="word_div" style="display:none;">Y座標</div>
                    <input id="add_word_y" class="word_input" type="text" placeholder="輸入Y" value="0" style="display:none;">
                    <div class="word_div">內容</div>
                    <input id="add_word_text" class="word_input" type="text" placeholder="輸入文字">
                    <div class="word_div">字的大小</div>
                    <input id="add_word_size" class="word_input" type="tel" placeholder="輸入數字" value="20" onkeyup="value = value.replace(/[^\d]/g, '')" onblur="location.href = '#send';">
                    <div class="word_div" style="display:none">字型</div>
                    <select id="select_family" class="selector" style="display:none">
                        <?php for ($rpg = 0; $rpg < kcount($family); $rpg++) { ?>
                            <option value="<?php echo $family[$rpg]; ?>"><?php echo $family[$rpg]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="button" style="width:85%;border-radius:20px;margin-top:20px;" onclick="add_word($('#add_word_x').val(), ($('#add_word_y').val() * 1 + $('#add_word_size').val() * 0.8), $('#add_word_text').val(), $('#add_word_size').val() + 'px', document.getElementById('color').style.backgroundColor, 'true', $('#select_width').val(), 'false', $('#select_family').val())">確定新增</div>
                </div>
                <div id="choose_flex" class="EditArea" style="padding:20px 0px;overflow: auto;">
                    <div onclick="$(this).parent().hide();" data-action="choose_the_flex" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div class="word_div" style="font-size: 16px;">訊息寬度</div>
                    <select id="flex_BubbleSize" class="btn_info word_input">
                        <?php for ($rpg = 0; $rpg < kcount($BubbleSize); $rpg++) { ?>
                            <option value="<?php echo $BubbleSize[$rpg]; ?>"><?php echo $BubbleSizeZhTw[$BubbleSize[$rpg]]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="word_div" style="font-size: 16px;">背景顏色</div>
                    <input id="flex_BGColor" class="jscolor word_input" value="ffffff">
                    <div class="word_div" style="font-size: 16px;">字體顏色</div>
                    <input id="flex_Color" class="jscolor word_input" value="000000">
                    <div class="word_div" style="font-size: 16px;">字體大小</div>
                    <select id="flex_Size" class="btn_info word_input">
                        <?php for ($trg = 0; $trg < kcount($size); $trg++) { ?>
                            <option value="<?php echo $size[$trg]; ?>"><?php echo $size[$trg]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="word_div" style="font-size: 16px;">字體粗細</div>
                    <div id="flex_Weight">
                        <input type="radio" id="flex_Weight_regular" name="flex_Weight" value="regular" checked>
                        <label for="flex_Weight_regular">細</label>
                        <input type="radio" id="flex_Weight_bold" name="flex_Weight" value="bold">
                        <label for="flex_Weight_bold">粗</label>
                    </div>
                    <div class="word_div" style="font-size: 16px;">文本對齊方式</div>
                    <div id="flex_Align">
                        <input type="radio" id="flex_Align_start" name="flex_Align" value="start" checked>
                        <label for="flex_Align_start">置左</label>
                        <input type="radio" id="flex_Align_center" name="flex_Align" value="center">
                        <label for="flex_Align_center">置中</label>
                        <input type="radio" id="flex_Align_end" name="flex_Align" value="end">
                        <label for="flex_Align_end">置右</label>
                    </div>
                    </br></br>
                    <div id="CreateFlex" class="button" onclick="CreateFlex();">建立</div>
                    <div id="CopyFlex" class="button" onclick="CopyFlex();">複製</div>
                    <div id="UpdateFlex" class="button" onclick="UpdateFlex();">更新</div>
                </div>
                <div id="add_category" class="EditArea" style="padding:50px 0px;">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div class="word_div" style="font-size: 16px;">主類別名稱</div>
                    <input id="category_name" class="word_input" type="text" placeholder="輸入文字">
                    <div class="word_div" style="font-size: 16px;">選擇主類別</div>
                    <select id="select_category" class="btn_info word_input">
                        <option value="">請選擇</option>
                        <?php for ($uar = 0; $uar <= kcount($category_list); $uar++) { ?>
                            <?php
                                $NO = $category_list[$uar][0];
                                $Category_Name = $category_list[$uar][5];
                            ?>
                            <?php if ($Category_Name != '') { ?>
                                <option value="<?php echo $NO; ?>"><?php echo $Category_Name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    </br></br>
                    <div class="button" onclick="CreateCategory('add');">建立</div>
                    <div class="button" onclick="CreateCategory('edit');">更新</div>
                    <div class="button" onclick="CreateCategory('delete');">刪除</div>
                </div>
                <div id="add_subcategory" class="EditArea" style="padding:10px 0px;overflow: auto;">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div class="word_div" style="font-size: 16px;">選擇主類別</div>
                    <select id="select_maincategory" class="btn_info word_input" onchange="UpdateSubcategory($('#select_subcategory'), $(this).val());">
                        <option value="">請選擇</option>
                        <?php for ($uar = 0; $uar <= kcount($category_list); $uar++) { ?>
                            <?php
                                $NO = $category_list[$uar][0];
                                $Category_Name = $category_list[$uar][5];
                            ?>
                            <?php if ($Category_Name != '') { ?>
                                <option value="<?php echo $NO; ?>"><?php echo $Category_Name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <div class="word_div" style="font-size: 16px;">次類別名稱</div>
                    <input id="subcategory_name" class="word_input" type="text" placeholder="輸入文字">
                    <div class="word_div" style="font-size: 16px;">選擇次類別</div>
                    <select id="select_subcategory" class="btn_info word_input">
                        <option value="">請選擇</option>
                    </select>
                    </br></br>
                    <div class="button" onclick="CreateSubCategory('add');">建立</div>
                    <div class="button" onclick="CreateSubCategory('edit');">更新</div>
                    <div class="button" onclick="CreateSubCategory('delete');">刪除</div>
                </div>
                <div id="add_note" class="EditArea" style="padding:50px 0px;">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div class="word_div">選擇主類別</div>
                    <select id="select_note_category" class="btn_info word_input" onchange="UpdateSubcategory($('#select_note_subcategory'), $(this).val());">
                        <option value="">請選擇</option>
                        <?php for ($uar = 0; $uar <= kcount($category_list); $uar++) { ?>
                            <?php
                                $NO = $category_list[$uar][0];
                                $Category_Name = $category_list[$uar][5];
                            ?>
                            <?php if ($Category_Name != '') { ?>
                                <option value="<?php echo $NO; ?>"><?php echo $Category_Name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <div class="word_div">選擇次類別</div>
                    <select id="select_note_subcategory" class="btn_info word_input">
                        <option value="">請選擇</option>
                    </select>
                    <div class="word_div">筆記名稱</div>
                    <input id="note_name" class="word_input" type="text" placeholder="輸入文字">
                    <div class="word_div">筆記連結</div>
                    <input id="note_data" class="word_input" type="text" placeholder="輸入文字">
                    </br></br>
                    <div class="button" onclick="CreateNote('add');">建立</div>
                    <div class="button" onclick="CreateNote('edit');">更新</div>
                    <div class="button" onclick="CreateNote('delete');">刪除</div>
                </div>
                <div id="add_manarger" class="EditArea" style="padding:10px 0px;overflow: auto;">
                    <div onclick="$(this).parent().hide();" class="X">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="35" height="35" viewBox="0 0 20 20">
                            <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"/>
                        </svg>
                    </div>
                    <div class="word_div">已邀請</div>
                    <?php if($manager_list){ ?>
                        <?php foreach ($manager_list as $key => $value) { ?>
                            <?php
                                $NO = $value[0];
                                $displayName = $value[1];
                                $pictureUrl = $value[2];
                            ?>
                            <div class="manager_block" NO="<?php echo $NO; ?>">
                                <img class="pic" src="<?php echo $pictureUrl; ?>">
                                <div class="name"><?php echo $displayName; ?></div>
                                <svg onclick="RemoveManager($(this).parents('.manager_block'));" style="position: absolute;margin-left: -30px;background-color: #00000080;" width="30" height="30" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 20 20">
                                    <path fill="#fff" d="M10.707 10.5l5.646-5.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-5.646 5.646-5.646-5.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.646 5.646-5.646 5.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l5.646-5.646 5.646 5.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-5.646-5.646z"></path>
                                </svg>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    </br></br>
                    <div class="button" onclick="ShareNote();" style="position: absolute;bottom: 10px;left: 30px;">邀請好友</div>
                </div>
            </div>
            
            
            <div class="signature-pad--actions" style="display:none">
                <div>
                    <button type="button" class="button clear" data-action="clear">Clear</button>
                    <button type="button" class="button" data-action="change-color">Change color</button>
                    <button type="button" class="button" data-action="undo">Undo</button>
                </div>
                <div>
                    <button type="button" class="button save" data-action="save-png">PNG</button>
                    <button type="button" class="button save" data-action="save-jpg">JPG</button>
                    <button type="button" class="button save" data-action="save-svg">SVG</button>
                </div>
                <select id="DefaultSubcategory" class="btn_info word_input" style="display: none;">
                    <option value="">請選擇</option>
                    <?php for ($uar = 0; $uar <= kcount($subcategory_list); $uar++) { ?>
                        <?php
                            $NO = $subcategory_list[$uar][0];
                            $SubCategory_Name = $subcategory_list[$uar][5];
                            $SubCategory_prevlevel = $subcategory_list[$uar][9];
                        ?>
                        <?php if ($SubCategory_Name != '') { ?>
                            <option value="<?php echo $NO; ?>" prevlevel="<?php echo $SubCategory_prevlevel; ?>"><?php echo $SubCategory_Name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>

    <div id='data_area' style="display:none;">
        <div id="pic_url"></div>
        <!-- liff.getProfile() -->
        <div id="userId"></div>
        <div id="displayName"></div>
        <div id="pictureUrl"></div>
        <div id="statusMessage"></div>
        <!-- liff.getContext() -->
        <div id="context_type"></div>
        <div id="context_utouId"></div>
        <div id="context_userId"></div>
        <div id="context_viewType"></div>
        <div id="context_accessTokenHash"></div>
        <!-- liff.getAccessToken() -->
        <div id="accessToken"></div>
        <!-- liff.getOS() -->
        <div id="OS"></div>
        <!-- liff.getLanguage() -->
        <div id="Language"></div>
        <!-- liff.getVersion() -->
        <div id="Version"></div>
        <!-- liff.getLineVersion() -->
        <div id="LineVersion"></div>
        <!-- liff.getIDToken() -->
        <div id="IDToken"></div>
        <!-- liff.getDecodedIDToken() -->
        <div id="DecodedIDToken"></div>
        <!-- liff.getFriendship() -->
        <div id="friendFlag"></div>
    </div>

    <div id="loading">
        <div id="loading_div">
            <span>資料處理中</span>
            <div class="ball-pulse-sync" style="margin-top:10px;text-align:center;">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <?php
        if ($imgur_result != '') {
            echo "<script>
                    $('#imgur_result').val('$imgur_result');
                    $('#upload_area').show();
                </script>";
        }
    ?>
    
    <script src="./res/vconsole.min.js"></script><!-- vConsole(手機版console[ LIFF適用 ])https://cdn.jsdelivr.net/npm/vconsole -->
    <!--<script src="./res/sdk.js"></script>--><!-- https://d.line-scdn.net/liff/1.0/ -->
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
    <!-- <script charset="utf-8" src="./res/sdk-2.1.js"></script><!-- https://static.line-scdn.net/liff/edge/2.1/sdk.js -->
    <script src='./res/jquery-1.8.2.js'></script><!-- https://code.jquery.com/ -->
    <script src="./res/jquery.js"></script><!-- https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/ -->
    <script src="./res/jquery.min.js"></script><!-- https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/ -->
    <script src="./res/signature_pad.min.js"></script><!-- https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/ -->
    <script src="./res/axios.min.js"></script><!-- https://unpkg.com/axios/dist/ -->
    <script src="./res/hammer.min.js"></script><!-- https://hammerjs.github.io/dist/ -->
    
    <script src="./res/jscolor.js"></script>
    <script src="./res/ga.js"></script>
    <script src="./res/signature_pad.umd.js"></script>
    <script src="./res/app.js?<?php echo date('YmdHis');?>"></script>
    <script src="./res/farbtastic3.js" type="text/javascript"></script>
    
    <script>
        //vConsole(手機版console[ LIFF適用 ])
//        var vConsole = new VConsole();
        
        var loading_div = $('#loading').find('#loading_div');
        loading_div.css('left', ($(window).width()-loading_div.outerWidth())/2 + 'px');
        loading_div.css('top', ($(window).height()-loading_div.outerHeight())/2 + 'px');
        $('#loading').hide(); 
        $("#project_details").find('.press_button_area').css('top', (($("#project_details").outerHeight() - $("#project_details").find('.press_button_area').outerHeight()) / 2) + 'px');

        /*if (document.getElementById('my_iframe2').attachEvent){
            document.getElementById('my_iframe2').attachEvent("onload", function(){
                $('#loading').hide();
                location.reload();
            });
        }else{
            document.getElementById('my_iframe2').onload = function(){
                $('#loading').hide();
                location.reload();
            };
        }*/

        var store_all_pic = [];
        var store_all_name = [];
        var store_all_profile_pic = [];
        var store_all_profile_statusMessage = [];
        var store_all_datetime = [];
        var store_all_favorite = [];
        var more = 'false';
        var store_datetime = ["<?php echo join("\", \"", $store_datetime); ?>"];
        var the_dataURL = "";
        var userId = "";
        var displayName = "";
        var pic_url = "";
        var pictureUrl = "";
        var statusMessage = "";
        var the_div = '';
        var the_pic = '';
        var the_id = '';
        var the_name = '';
        var word_list = [];
        var word = [];
        var line_width = [];
        var Text_DefaultJson = '{\
            "type": "bubble",\
            "body": {\
                "type": "box",\
                "layout": "vertical",\
                "contents": [\
                    {\
                        "type": "box",\
                        "layout": "horizontal",\
                        "contents": [\
                            {\
                                "type": "text",\
                                "text": "HelloWorld",\
                                "size": "md",\
                                "gravity": "center",\
                                "wrap": true\
                            }\
                        ],\
                        "paddingAll": "15px"\
                    }\
                ],\
                "paddingAll": "0px"\
            }\
        }';
        var DefaultSubcategory = $("#DefaultSubcategory").html();
        var category_list = JSON.parse('<?php echo json_encode($category_list); ?>');
        var subcategory_list = JSON.parse('<?php echo json_encode($subcategory_list); ?>');
        var note_list = JSON.parse('<?php echo json_encode($note_list); ?>');
        
        window.onload = function (e) {
            // v1: line://app/{liffId}
            // v2: https://liff.line.me/{liffId}
            liff.init({
                    liffId: '1577298076-2Wx5MnW5'
                })
                .then(() => {
                    if (!liff.isLoggedIn()) {
                        liff.login();
                    }else{
                        //liff.isInClient();
                        //liff.logout();
                        /*liff.openWindow({
                            url: "https://line.me",
                            external: true
                        });*/
                        //liff.closeWindow();
                        
                        liff.getProfile().then(function (profile) {
                            $("#userId").html(profile.userId);
                            $("#displayName").html(profile.displayName);
                            $("#pictureUrl").html(profile.pictureUrl);
                            $("#statusMessage").html(profile.statusMessage);
                        }).catch(function (error) {
                            window.alert("Error getting profile: " + error);
                        });
                        var Context = liff.getContext();
                        if(Context){
                            $("#context_type").html(Context.type);
                            $("#context_utouId").html(Context.utouId);
                            $("#context_userId").html(Context.userId);
                            $("#context_viewType").html(Context.viewType);
                            $("#context_accessTokenHash").html(Context.accessTokenHash);
//                            console.log(Context);
                        }
                        $("#accessToken").html(liff.getAccessToken());
                        $("#OS").html(liff.getOS());
                        $("#Language").html(liff.getLanguage());
                        $("#Version").html(liff.getVersion());
                        $("#LineVersion").html(liff.getLineVersion());
                        $("#IDToken").html(liff.getIDToken());
                        $("#DecodedIDToken").html(liff.getDecodedIDToken());
                        liff.getFriendship().then(data => {
                            if (data.friendFlag) {
                                $("#friendFlag").html('true');
                            }else{
                                $("#friendFlag").html('false');
                            }
                        });
                        if (0 && liff.isApiAvailable('shareTargetPicker')) {
                            liff.shareTargetPicker([
                                {
                                    type: "text",
                                    text: "分享測試"
                                }
                            ])
                            .then(
                                console.log("已啟動")
                            ).catch(function(res) {
                                console.log("啟動失敗")
                            })
                        }
                        if (0 && liff.scanCode) { //OS 在 9.19.0 之後已經暫停此功能， Android 目前維持不變
                            liff.scanCode().then(result => {
                                result = { value: '123' }
                            });
                        }
                    }      
                })
                .catch((error) => {
                    window.alert(error);
                });
        };
        
        function RemoveManager(obj){
            var userId = $("#userId").html();
            var name = obj.find('.name').html();
            var NO = obj.attr('NO');
            if (confirm("確定要移除【" + name +"】一起編輯的權限嗎?") == true) {
                location.href= "add_flex.php?" + "module=note" + "&NO=" + NO + "&userId=" + userId + "&level=user";
            }
        }
        
        function ShareNote(){
            var userId = $("#userId").html();
            var displayName = $("#displayName").html();
            var pictureUrl = $("#pictureUrl").html();
            var statusMessage = $("#statusMessage").html();
            
            var Msg = '【' + displayName + '】邀請你一起編輯筆記';
            location.href = 'line://msg/text/?' + Msg + 'https://rickytest.gadclubs.com/stickers/sharenote.php?admin=' + userId;
        }
        
        function UpdateSubcategory(obj, val){
            if(val != ''){
                obj.html(DefaultSubcategory);
                var SubCtn = 0;
                obj.find('option').each(function(e) {
                    SubCtn++;
                    var prevlevel = $(this).attr('prevlevel');
                    if(SubCtn!=1 && prevlevel!=val){
                        $(this).remove();
                    }
                });
            }else{
                obj.html('<option value="">請選擇</option>');
            }
        }
        
        function AddCategory(act){
            $('#note_add_background').hide();
            $('#add_category').show();
            $('#add_category').find('.button').hide();
            $('#add_category').find('.word_div').hide();
            $('#add_category').find('.word_input').hide();
            $('#category_name').val('');
            $('#select_category').val('');
            switch(act){
                case 'add':
                    $('#add_category').find('.button').eq(0).show();
                    $('#add_category').find('.word_div').eq(0).show();
                    $('#category_name').show();
                    break;
                case 'edit':
                    $('#category_press_background').hide();
                    var category_block = $('#'+the_pic.parentNode.id);
                    var NO = category_block.attr('NO');
                    var name = category_block.attr('name');
                    $('#select_category').val(NO);
                    $('#category_name').val(name);
                    $('#add_category').find('.button').eq(1).show();
                    $('#add_category').find('.word_div').show();
                    $('#add_category').find('.word_input').show();
                    break;
                case 'delete':
                    $('#add_category').hide();
                    CreateCategory('delete');
                    break;
            }
        }
        
        function AddSubCategory(act){
            $('#note_add_background').hide();
            $('#add_subcategory').show();
            $('#add_subcategory').find('.button').hide();
            $('#add_subcategory').find('.word_div').hide();
            $('#add_subcategory').find('.word_input').hide();
            $('#select_maincategory').val('');
            $('#subcategory_name').val('');
            $('#select_subcategory').html('<option value="">請選擇</option>');
            switch(act){
                case 'add':
                    $('#add_subcategory').find('.button').eq(0).show();
                    $('#add_subcategory').find('.word_div').eq(0).show();
                    $('#add_subcategory').find('.word_div').eq(1).show();
                    $('#select_maincategory').show();
                    $('#subcategory_name').show();
                    break;
                case 'edit':
                    $('#subcategory_press_background').hide();
                    var subcategory_block = $('#'+the_pic.parentNode.id);
                    var NO = subcategory_block.attr('NO');
                    var name = subcategory_block.attr('name');
                    var prevlevel = subcategory_block.attr('prevlevel');
                    $('#select_maincategory').val(prevlevel);
                    $('#subcategory_name').val(name);
                    UpdateSubcategory($('#select_subcategory'), prevlevel);
                    $('#select_subcategory').val(NO);
                    $('#add_subcategory').find('.button').eq(1).show();
                    $('#add_subcategory').find('.word_div').show();
                    $('#add_subcategory').find('.word_input').show();
                    break;
                case 'delete':
                    $('#add_subcategory').hide();
                    CreateSubCategory('delete');
                    break;
            }
        }
        
        function AddNote(act){
            $('#note_add_background').hide();
            $('#select_note_category').val('');
            $('#select_note_subcategory').html('<option value="">請選擇</option>');
            $('#note_name').val('');
            $('#note_data').val('');
            $('#add_note').show();
            $('#add_note').find('.button').hide();
            switch(act){
                case 'add':
                    $('#add_note').find('.button').eq(0).show();
                    break;
                case 'edit':
                    $('#note_press_background').hide();
                    $('#add_note').find('.button').eq(1).show();
                    var note_block = $('#'+the_pic.parentNode.id);
                    var NO = note_block.attr('NO');
                    var name = note_block.attr('name');
                    var data = note_block.attr('data');
                    var prevlevel = note_block.attr('prevlevel');
                    var category_NO = note_block.parents('.category_block').attr('NO');

                    $('#select_note_category').val(category_NO);
                    UpdateSubcategory($('#select_note_subcategory'), category_NO);
                    $('#select_note_subcategory').val(prevlevel);
                    $('#note_name').val(name);
                    $('#note_data').val(data);
                    break;
                case 'delete':
                    $('#add_note').hide();
                    if (confirm("確定要永久刪除嗎?") == true) {
                        CreateNote('delete');
                    }
                    break;
            }
        }
        
        function ChooseFlex(val){
            $("#flex_BubbleSize").val('mega');
            $("#flex_BGColor").val('ffffff');
            $("#flex_BGColor").css('background-color', '#ffffff');
            $("#flex_Color").val('000000');
            $("#flex_Color").css('background-color', '#000000');
            $("#flex_Size").val('md');
            $('#flex_Weight input[type="radio"]').prop('checked', false);
            $("#flex_Weight_regular").prop('checked', true);
            $('#flex_Align input[type="radio"]').prop('checked', false);
            $("#flex_Align_start").prop('checked', true);
            $("#choose_flex").show();
            if(val){
                var ValJson = JSON.parse(val);
                var contentsCtn = ValJson['body']['contents'].length-1;
                var textCtn = ValJson['body']['contents'][contentsCtn]['contents'].length-1;
                
                if(ValJson['size']){
                    $("#flex_BubbleSize").val(ValJson['size']);
                }
                if(ValJson['body']['contents'][contentsCtn]['backgroundColor']){
                    $("#flex_BGColor").val(ValJson['body']['contents'][contentsCtn]['backgroundColor'].replace(/\#/, ''));
                    $("#flex_BGColor").css('background-color', ValJson['body']['contents'][contentsCtn]['backgroundColor']);
                }
                if(ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['color']){
                    $("#flex_Color").val(ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['color'].replace(/\#/, ''));
                    $("#flex_Color").css('background-color', ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['color']);
                }
                if(ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['size']){
                    $("#flex_Size").val(ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['size']);
                }
                if(ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['weight'] == 'bold'){
                    $('#flex_Weight input[type="radio"]').prop('checked', false);
                    $("#flex_Weight_bold").prop('checked', true);
                }
                if(ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['align']){
                    $('#flex_Align input[type="radio"]').prop('checked', false);
                    $("#flex_Align_"+ValJson['body']['contents'][contentsCtn]['contents'][textCtn]['align']).prop('checked', true);
                }
                $("#UpdateFlex").show();
                $("#CopyFlex").show();
                $("#CreateFlex").hide();
            }else{
                $("#UpdateFlex").hide();
                $("#CopyFlex").hide();
                $("#CreateFlex").show();
            }
        }

        var touchCount = 0;
        var wordCount = 0;
        var dataURL_Backup = '';
        var SelectModule = '';
        
        function CreateNote(action){
            if(action == 'delete'){
                var userId = $("#userId").html();
                var level = 'note';
                NO = $('#'+the_pic.parentNode.id).attr('NO');
                location.href= "add_flex.php?" + "module=note" + "&action=" + action + "&NO=" + NO + "&displayName=" + displayName + "&name=" + NoteName + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId + "&prevlevel=" + prevlevel + "&level=" + level + "&data=" + NoteData;
            }else if($('#select_note_category').val() && $('#select_note_subcategory').val() && $('#note_name').val() && $('#note_data').val()){
                var userId = $("#userId").html();
                var displayName = $("#displayName").html();
                var pictureUrl = $("#pictureUrl").html();
                var statusMessage = $("#statusMessage").html();
                
                var prevlevel = $('#select_note_subcategory').val();
                var NoteName = $('#note_name').val();
                var NoteData = $('#note_data').val();
                var NO = '';
                if(action == 'edit'){
                    NO = $('#'+the_pic.parentNode.id).attr('NO');
                }
                var level = 'note';
                location.href= "add_flex.php?" + "module=note" + "&action=" + action + "&NO=" + NO + "&displayName=" + displayName + "&name=" + NoteName + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId + "&prevlevel=" + prevlevel + "&level=" + level + "&data=" + NoteData;
            }else{
                alert('請填寫【選擇主類別、選擇次類別、筆記名稱、筆記連結】');
            }
        }
        
        function CreateCategory(action){
            var errorMsg = '';
            
            switch(action){
                case 'add':
                    if(!$('#category_name').val()){
                        errorMsg = '請填寫【主類別名稱】';
                    }
                    break;
                case 'edit':
                    if(!$('#category_name').val() || !$('#select_category').val()){
                        errorMsg = '請填寫【主類別名稱、選擇主類別】';
                    }
                    break;
            }
            
            if(!errorMsg){
                var userId = $("#userId").html();
                var displayName = $("#displayName").html();
                var pictureUrl = $("#pictureUrl").html();
                var statusMessage = $("#statusMessage").html();
                
                var CategoryName = $('#category_name').val();
                var NO = $('#select_category').val();
                if(action=='delete'){
                    NO = $('#'+the_pic.parentNode.id).attr('NO');
                }
                var prevlevel = '';
                var level = 'category';
                var Location = "add_flex.php?" + "module=note" + "&action=" + action + "&NO=" + NO + "&displayName=" + displayName + "&name=" + CategoryName + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId + "&prevlevel=" + prevlevel + "&level=" + level;
                
                if(action=='delete'){
                    var Category_SubCategoryCtn = 0;
                    subcategory_list.forEach(function(note){
                        if(note[9] == NO){
                            Category_SubCategoryCtn++;
                        }
                    });
                    if(Category_SubCategoryCtn > 0){
                        alert('請先刪除附屬之次類別');
                    }else{
                        if(confirm("確定要刪除嗎?") == true){
                            location.href = Location;
                        }
                    }
                }else{
                    location.href = Location;
                }
            }else{
                alert(errorMsg);
            }
        }
        
        function CreateSubCategory(action){
            var errorMsg = '';
            
            switch(action){
                case 'add':
                    if(!$('#select_maincategory').val() || !$('#subcategory_name').val()){
                        errorMsg = '請填寫【選擇主類別、次類別名稱】';
                    }
                    break;
                case 'edit':
                    if(!$('#select_maincategory').val() || !$('#subcategory_name').val() || !$('#select_subcategory').val()){
                        errorMsg = '請填寫【選擇主類別、次類別名稱、選擇次類別】';
                    }
                    break;
            }
            
            if(!errorMsg){
                var userId = $("#userId").html();
                var displayName = $("#displayName").html();
                var pictureUrl = $("#pictureUrl").html();
                var statusMessage = $("#statusMessage").html();
                
                var SubCategoryName = $('#subcategory_name').val();
                var NO = $('#select_subcategory').val();
                if(action=='delete'){
                    NO = $('#'+the_pic.parentNode.id).attr('NO');
                }
                var prevlevel = $('#select_maincategory').val();
                var level = 'subcategory';
                var Location = "add_flex.php?" + "module=note" + "&action=" + action + "&NO=" + NO + "&displayName=" + displayName + "&name=" + SubCategoryName + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId + "&prevlevel=" + prevlevel + "&level=" + level;
                
                if(action=='delete'){
                    var SubCategory_NoteCtn = 0;
                    note_list.forEach(function(note){
                        if(note[9] == NO){
                            SubCategory_NoteCtn++;
                        }
                    });
                    if(SubCategory_NoteCtn > 0){
                        alert('請先刪除附屬之筆記');
                    }else{
                        console.log(NO);
                        if(confirm("確定要刪除嗎?") == true){
                            location.href = Location;
                        }
                    }
                }else{
                    location.href = Location;
                }
            }else{
                alert(errorMsg);
            }
        }
        
        function CreateFlex(val, act){
            var Text_DefaultArray = JSON.parse(Text_DefaultJson);
            var action = 'add';
            var NO = '';
            if(val){
                Text_DefaultArray = JSON.parse(val);
                if(act != 'copy'){
                    action = 'edit';
                    NO = $("#"+the_pic.id).attr('NO');
                }
            }
            var contentsCtn = Text_DefaultArray['body']['contents'].length-1;
            var textCtn = Text_DefaultArray['body']['contents'][contentsCtn]['contents'].length-1;
            
            if($('#flex_BubbleSize').val()){ Text_DefaultArray['size'] = $('#flex_BubbleSize').val(); }
            if($('#flex_BGColor').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['backgroundColor'] = '#' + $('#flex_BGColor').val(); }
            if($('#flex_Color').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['contents'][textCtn]['color'] = '#' + $('#flex_Color').val(); }
            if($('#flex_Size').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['contents'][textCtn]['size'] = $('#flex_Size').val(); }
            if($('#flex_Weight input[type="radio"]:checked').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['contents'][textCtn]['weight'] = $('#flex_Weight input[type="radio"]:checked').val(); }
            if($('#flex_Align input[type="radio"]:checked').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['contents'][textCtn]['align'] = $('#flex_Align input[type="radio"]:checked').val(); }
            if($('#flex_Text').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['contents'][textCtn]['text'] = $('#flex_Text').val(); }
            
            if($('#flex_BGColor').val() && $('#flex_Color').val()){
                var userId = $("#userId").html();
                var displayName = $("#displayName").html();
                var pictureUrl = $("#pictureUrl").html();
                var statusMessage = $("#statusMessage").html();
                var flex_json = encodeURIComponent(JSON.stringify(Text_DefaultArray));
                location.href = "add_flex.php?" + "action=" + action + "&NO=" + NO + "&displayName=" + displayName + "&flex_json=" + flex_json + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId;
            }else{
                alert('請填寫完整');
            }
        }
        
        function UpdateFlex(){
            CreateFlex(SelectModule);
        }
        
        function CopyFlex(){
            CreateFlex(SelectModule, 'copy');
        }
        
        function SendFlex(){
            if(SelectModule && $('#flex_send_Text').val()){
                var Text_DefaultArray = JSON.parse(SelectModule);
                var contentsCtn = Text_DefaultArray['body']['contents'].length-1;
                var textCtn = Text_DefaultArray['body']['contents'][contentsCtn]['contents'].length-1;
                
                if($('#flex_send_Text').val()){ Text_DefaultArray['body']['contents'][contentsCtn]['contents'][textCtn]['text'] = $('#flex_send_Text').val(); }
                var Msg = [{
                    "type": "flex",
                    "altText": $('#flex_send_altText').val() ? $('#flex_send_altText').val() : $('#flex_send_Text').val(),
                    "contents": Text_DefaultArray
                }];
                /*var Msg = [{
                    "type": "text",
                    "text": 'https://developers.line.biz/flex-simulator/',
                    "label": '給你玩'
                }];*/
                liff.sendMessages(Msg).then(function () {
                    window.alert("訊息已送出");
                }).catch(function (error) {
                    window.alert(error);
                });
            }else{
                if(!SelectModule && !$('#flex_send_Text').val()){
                    alert('請選擇flex模板，並填寫flex內文字');
                }else if(!SelectModule){
                    alert('請選擇flex模板');
                }else if(!$('#flex_send_Text').val()){
                    alert('請填寫flex內文字');
                }             
            }
        }
        
        function SendNote(){
            var CategoryObj = $('.CategorySelect').find('.note_block');
            var SubCategoryObj = $('.SubCategorySelect').find('.note_block');
            var NoteObj = $('.NoteSelect');
            var BGColorList = ['#27ACB2', '#FF6B6E', '#A17DF5', '#27ACB2', '#FF6B6E', '#A17DF5', '#27ACB2', '#FF6B6E', '#A17DF5', '#27ACB2'];
            var carouselJSON = '{\
                "type": "carousel"\
              }';
            var bubbleJSON = '{\
                "type": "bubble",\
                "size": "nano",\
                "header": {\
                    "type": "box",\
                    "layout": "vertical",\
                    "contents": [\
                        {\
                            "type": "text",\
                            "text": "In Progress",\
                            "color": "#ffffff",\
                            "align": "start",\
                            "size": "md",\
                            "gravity": "center"\
                        },\
                        {\
                            "type": "text",\
                            "text": "70%",\
                            "color": "#ffffff",\
                            "align": "start",\
                            "size": "xs",\
                            "gravity": "center",\
                            "margin": "lg"\
                        }\
                    ],\
                    "backgroundColor": "#27ACB2",\
                    "paddingTop": "19px",\
                    "paddingAll": "12px",\
                    "paddingBottom": "16px"\
                },\
                "body": {\
                    "type": "box",\
                    "layout": "vertical",\
                    "contents": [\
                        {\
                            "type": "box",\
                            "layout": "horizontal",\
                            "contents": [\
                                {\
                                    "type": "text",\
                                    "text": "筆記1",\
                                    "color": "#8C8C8C",\
                                    "size": "sm",\
                                    "wrap": true\
                                }\
                            ],\
                            "flex": 1\
                        }\
                    ],\
                    "spacing": "md",\
                    "paddingAll": "12px"\
                },\
                "styles": {\
                    "footer": {\
                        "separator": false\
                    }\
                }\
            }';
            
            var bubbleCtn = -1;
            var carouselContent = [];
            CategoryObj.each(function(e) {
                PushContent($(this));
            });
            SubCategoryObj.each(function(e) {
                PushContent($(this));
            });
            NoteObj.each(function(e) {
                PushContent($(this));
            });
            
            function PushContent(obj){
                if(bubbleCtn < 9){
                    bubbleCtn++;
                    var ThisNote = JSON.parse(bubbleJSON);
                    ThisNote['header']['backgroundColor'] = BGColorList[bubbleCtn];
                    ThisNote['header']['contents'][0]['text'] = obj.parents('.category_block').find('.category_title').html();
                    ThisNote['header']['contents'][1]['text'] = obj.parents('.subcategory_block').find('.subcategory_title').html();
                    ThisNote['body']['contents'][0]['contents'][0]['text'] = obj.attr('name');
                    var Https = '';
                    if(obj.attr('data').indexOf("http")==-1){
                        Https = 'https://www.google.com.tw/search?q=';
                    }
                    ThisNote['action'] = {
                        "type": 'uri',
                        "uri": Https + encodeURI(obj.attr('data'))
                    };
                    carouselContent.push(ThisNote);
                }
            }
            
            if(bubbleCtn > -1){
                var MsgJSON = JSON.parse(carouselJSON);
                MsgJSON['contents'] = carouselContent;
                var Msg = [{
                    "type": "flex",
                    "altText": '筆記',
                    "contents": MsgJSON
                }];
                if(0){
                    console.log(JSON.stringify(Msg));
                }else{
                    liff.sendMessages(Msg).then(function () {
                        window.alert("訊息已送出");
                    }).catch(function (error) {
                        window.alert(error);
                    });
                }
            }else{
                alert("請選擇要傳送的筆記");
            }
        }

        // sendMessages call
        document.getElementById('send').addEventListener('click', function () {
            /* signaturePad.isEmpty() 失效 */
            if ((touchCount > 0) || (wordCount > 0)) {
                /* 不重複上傳同一張圖 */
                if (dataURL_Backup == '') {
                    var dataURL = signaturePad.toDataURL();
                    dataURL_Backup = dataURL;
                    var blobBin = atob(dataURL.split(',')[1]);
                    blob_array = [];
                    for (let i = 0; i < blobBin.length; i++) {
                        blob_array.push(blobBin.charCodeAt(i));
                    }
                    var fileData = new Blob([new Uint8Array(blob_array)], {type: 'image/png'});
                    // 將file 加至 formData
                    var formData = new FormData();
                    var fileName = $("#userId").html();
                    formData.append('fileData', fileData, fileName);

                    // send ajax request 
                    fetch('img_callback3.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                      .then(resText => $("#pic_url").html(resText))
                }
                var pic_url = $("#pic_url").html();
                liff.sendMessages([{
                    "type": "image",
                    "originalContentUrl": pic_url,
                    "previewImageUrl": pic_url
                }]).then(function () {
                    window.alert("圖片已送出");
                    var userId = $("#userId").html();
                    var displayName = $("#displayName").html();
                    var pictureUrl = $("#pictureUrl").html();
                    var statusMessage = $("#statusMessage").html();
                    location.href = "add_list.php?" + "displayName=" + displayName + "&pic_url=" + pic_url + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId;
                    //location.href = "add_list.php?" + "displayName=" + displayName + "&pic_url=" + pic_url + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId + '&last_pictureUrl=' + last_pictureUrl;
                }).catch(function (error) {
                    if (String(error).indexOf("400") > 0) {
                        window.alert("圖片上傳中，請稍後再試");
                    } else {
                        window.alert(error);
                    }
                });
            } else {
                alert("請先建立一個圖案");
            }
        });

        var lastClickTime = 0;
        var clickTimer;
        function push_pic(obj, all) {
            var nowTime = new Date().getTime();
            if (nowTime - lastClickTime < 400) {
                lastClickTime = 0;
                clickTimer && clearTimeout(clickTimer);
                /*雙擊*/
                dbl_push_pic(obj, all);
            } else {
                lastClickTime = nowTime;
                clickTimer = setTimeout(() => {
                    /*單擊*/
                    if (confirm("確定要送出嗎?") == true) {
                        if (all == obj) {
                            var pic_url = obj.childNodes[0].src;
                        } else {
                            var pic_url = obj.childNodes[1].src;
                        }
                        if (pic_url.substr(pic_url.length - 3) == "gif") {
                            liff.sendMessages([{
                                "type": "video",
                                "originalContentUrl": pic_url.substr(0, pic_url.length - 3) + "mp4",
                                "previewImageUrl": pic_url.substr(0, pic_url.length - 3) + "png"
                            }]).then(function () {
                                window.alert("影片已送出");
                            }).catch(function (error) {
                                if (error.indexOf("400") != -1) {
                                    window.alert('影片上傳中，請稍後再試');
                                } else {
                                    window.alert(error);
                                }
                            });
                        } else {
                            liff.sendMessages([{
                                "type": "image",
                                "originalContentUrl": pic_url,
                                "previewImageUrl": pic_url
                            }]).then(function () {
                                window.alert("圖片已送出");
                            }).catch(function (error) {
                                if (error.indexOf("400") != -1) {
                                    window.alert('圖片上傳中，請稍後再試');
                                } else {
                                    window.alert(error);
                                }
                            });
                        }
                    }
                }, 400);
            }
        }
        function dbl_push_pic(obj, all) {
            var GO = "add_favorite.php?";
            var pic_url = '';
            var favorite = '';
            if (all == obj) {
                pic_url = obj.childNodes[0].src;
                if (obj.childNodes[2].childNodes[0].style.fill == "rgb(206, 61, 61)") {
                    obj.childNodes[2].childNodes[0].style.fill = "#fff";
                    favorite = "false";
                } else {
                    obj.childNodes[2].childNodes[0].style.fill = "rgb(206, 61, 61)";
                    favorite = "true";
                }
            } else {
                pic_url = obj.childNodes[1].src;
                if (obj.childNodes[3].childNodes[1].style.fill == "rgb(206, 61, 61)") {
                    obj.childNodes[3].childNodes[1].style.fill = "#fff";
                    favorite = "false";
                } else {
                    obj.childNodes[3].childNodes[1].style.fill = "rgb(206, 61, 61)";
                    favorite = "true";
                }
            }
            GO = GO + "pic_url=" + pic_url + "&favorite=" + favorite;
            document.getElementById("favorite_iframe").src = GO;
        }
        
        function push_flex(obj) {
            var nowTime = new Date().getTime();
            if (nowTime - lastClickTime < 400) {
                lastClickTime = 0;
                clickTimer && clearTimeout(clickTimer);
                /*雙擊*/
                dbl_push_flex(obj);
            } else {
                lastClickTime = nowTime;
                clickTimer = setTimeout(() => {
                    /*單擊*/
                    SelectModule = obj.children().eq(0).attr('data-json');
                    $('.Select').removeClass('Select');
                    obj.addClass('Select');
                }, 400);
            }
        }
        function dbl_push_flex(obj) {
            var GO = "add_favorite.php?";
            var NO = obj.children().eq(0).attr('NO');
            
            if (obj.children().eq(1).children().eq(0).css('fill') == "rgb(206, 61, 61)") {
                obj.children().eq(1).children().eq(0).css('fill', '#fff');
                favorite = "false";
            } else {
                obj.children().eq(1).children().eq(0).css('fill', 'rgb(206, 61, 61)');
                favorite = "true";
            }
            GO = GO + "module=flex" + "&NO=" + NO + "&favorite=" + favorite;
            document.getElementById("favorite_iframe").src = GO;
        }
        
        function push_category(obj) {
            if(event.target.className == 'category_title'){
                var nowTime = new Date().getTime();
                if (nowTime - lastClickTime < 400) {
                    lastClickTime = 0;
                    clickTimer && clearTimeout(clickTimer);
                    /*雙擊*/
                } else {
                    lastClickTime = nowTime;
                    clickTimer = setTimeout(() => {
                        /*單擊*/
                        if(obj.hasClass("CategorySelect")){
                            obj.removeClass('CategorySelect');
                        }else{
                            obj.addClass('CategorySelect');
                        }
                        obj.find('.SubCategorySelect').removeClass('SubCategorySelect');
                        obj.find('.NoteSelect').removeClass('NoteSelect');
                    }, 400);
                }
            }
        }
        
        function push_subcategory(obj) {
            if(event.target.className == 'subcategory_title'){
                var nowTime = new Date().getTime();
                if (nowTime - lastClickTime < 400) {
                    lastClickTime = 0;
                    clickTimer && clearTimeout(clickTimer);
                    /*雙擊*/
                } else {
                    lastClickTime = nowTime;
                    clickTimer = setTimeout(() => {
                        /*單擊*/
                        var category_block = obj.parents('.category_block');
                        var all_subcategory_block = category_block.find('.subcategory_block');
                        
                        if(category_block.hasClass("CategorySelect")){
                            category_block.removeClass('CategorySelect');
                            all_subcategory_block.addClass('SubCategorySelect');
                            obj.removeClass('SubCategorySelect');
                        }else{
                            if(obj.hasClass("SubCategorySelect")){
                                obj.removeClass('SubCategorySelect');
                            }else{
                                obj.addClass('SubCategorySelect');
                                var SubCategorySelect = category_block.find('.SubCategorySelect');
                                if(all_subcategory_block.length == SubCategorySelect.length){
                                    SubCategorySelect.removeClass('SubCategorySelect');
                                    category_block.addClass('CategorySelect');
                                }
                            }
                        }
                        obj.find('.NoteSelect').removeClass('NoteSelect');
                    }, 400);
                }
            }
        }
        
        function push_note(obj) {
            if(event.target.className == 'note'){
                var nowTime = new Date().getTime();
                if (nowTime - lastClickTime < 400) {
                    lastClickTime = 0;
                    clickTimer && clearTimeout(clickTimer);
                    /*雙擊*/
                    dbl_push_note(obj);
                } else {
                    lastClickTime = nowTime;
                    clickTimer = setTimeout(() => {
                        /*單擊*/
                        var category_block = obj.parents('.category_block');
                        var all_subcategory_block = category_block.find('.subcategory_block');
                        var subcategory_block = obj.parents('.subcategory_block');
                        var all_note_block = subcategory_block.find('.note_block');
                        
                        if(category_block.hasClass("CategorySelect")){
                            category_block.removeClass('CategorySelect');
                            all_subcategory_block.addClass('SubCategorySelect');
                            subcategory_block.removeClass('SubCategorySelect');
                            all_note_block.addClass('NoteSelect');
                            obj.removeClass('NoteSelect');
                        }else{
                            if(subcategory_block.hasClass("SubCategorySelect")){
                                subcategory_block.removeClass('SubCategorySelect');
                                all_note_block.addClass('NoteSelect');
                                obj.removeClass('NoteSelect');
                            }else{
                                if(obj.hasClass("NoteSelect")){
                                    obj.removeClass('NoteSelect');
                                }else{
                                    obj.addClass('NoteSelect');
                                    var NoteSelect = subcategory_block.find('.NoteSelect');
                                    if(all_note_block.length == NoteSelect.length){
                                        NoteSelect.removeClass('NoteSelect');
                                        subcategory_block.addClass('SubCategorySelect');
                                        var SubCategorySelect = category_block.find('.SubCategorySelect');
                                        if(all_subcategory_block.length == SubCategorySelect.length){
                                            SubCategorySelect.removeClass('SubCategorySelect');
                                            category_block.addClass('CategorySelect');
                                        }
                                    }
                                }
                            }
                        }
                    }, 400);
                }
            }
        }
        function dbl_push_note(obj) {
            if(!obj){
                $('#note_press_background').hide();
                obj = $('#'+the_pic.parentNode.id);
            }
            var GO = "add_favorite.php?";
            var NO = obj.attr('NO');
            
            if (obj.children().eq(1).children().eq(0).css('fill') == "rgb(206, 61, 61)") {
                obj.children().eq(1).children().eq(0).css('fill', '#fff');
                favorite = "false";
            } else {
                obj.children().eq(1).children().eq(0).css('fill', 'rgb(206, 61, 61)');
                favorite = "true";
            }
            GO = GO + "module=note" + "&NO=" + NO + "&favorite=" + favorite;
            document.getElementById("favorite_iframe").src = GO;
        }

        function add_favorite() {
            var ele = document.getElementById("push_pic" + the_id);
            if (ele.childNodes.length == 3) {
                dbl_push_pic(ele, ele);
            } else {
                dbl_push_pic(ele);
            }
            $('#press_background').hide();
        }
        
        function flex_add_favorite(){
            var ele = $("#push_flex" + the_id);
            dbl_push_flex(ele);
            $('#flex_press_background').hide();
        }

        $(function () {
            $('#canvas').farbtastic('#color');
        });

        jQuery._farbtastic(function () {
            jQuery.farbtastic('#picker').linkTo('#color');
        });

        function change_color(obj) {
            $("#change_color").show();
        }

        function change_width(obj) {
            $("#change_width").show();
        }

        function the_download(obj) {
            $("#the_download").show();
        }

        function change_word(obj) {
            if (obj.checked) {
                $("#change_word").show();
            } else {
                $('#change_word').hide();
            }
        }

        function select_width(obj) {
            var ele_value = obj.value;
            //signaturePad.minWidth = ele_value;
            signaturePad.maxWidth = ele_value;
            //signaturePad.dotSize = ele_value;

            var data = signaturePad.toData();
            signaturePad.fromData(data);

            $('#change_width').hide();
        }
        
        function choose_user(obj) {
            for (ak = 0; ak < document.getElementById("list_area").childNodes.length; ak++) {
                if (　(obj.value == $("#push_pic" + (ak + 1)).children().eq(1).html()) || (obj.value == '全部')) {
                    $("#push_pic" + (ak + 1)).css('display', 'inline-block');
                } else {
                    $("#push_pic" + (ak + 1)).hide();
                }
            }
        }

        ManagerList = [
            'U34fc54fe77b0195fab8c42b1487f70f6', //(舊)宏
            'U43f8bef06efa4aac5484884c2befe1a8', //(新)宏

        ];
        
        function SelectProject(val) {
            var userId = $("#userId").html();
            if (userId == '') {
                userId = '<?php echo $userId; ?>';
            }
            var displayName = $("#userId").html();
            var pictureUrl = $("#pictureUrl").html();
            var statusMessage = $("#statusMessage").html();
            
            location.href = 'Signature%20Pad%20demo.php?' + 'project=' + val + '&displayName=' + displayName + '&pic_url=' + pic_url + '&pictureUrl=' + pictureUrl + '&statusMessage=' + statusMessage + '&userId=' + userId;
        }

        function List(obj) {
            var userId = $("#userId").html();
            if (userId == '') {
                userId = '<?php echo $userId; ?>';
            }
            var displayName = $("#userId").html();
            var pictureUrl = $("#pictureUrl").html();
            var statusMessage = $("#statusMessage").html();
            if ('<?php echo $userId; ?>' == '') {
                location.href = 'Signature%20Pad%20demo.php?' + 'project=手畫貼圖' + '&displayName=' + displayName + '&pic_url=' + pic_url + '&pictureUrl=' + pictureUrl + '&statusMessage=' + statusMessage + '&userId=' + userId;
            }

            if (ManagerList.indexOf(userId) != '-1') {
                more = 'true';
                store_all_pic = ["<?php echo join("\", \"", $store_all_pic); ?>"];
                store_all_name = ["<?php echo join("\", \"", $store_all_name); ?>"];
                store_all_profile_pic = ["<?php echo join("\", \"", $store_all_profile_pic); ?>"];
                store_all_profile_statusMessage = ["<?php echo join("\", \"", $store_all_profile_statusMessage); ?>"];
                store_all_datetime = ["<?php echo join("\", \"", $store_all_datetime); ?>"];
                store_all_favorite = ["<?php echo join("\", \"", $store_all_favorite); ?>"];

                if (document.getElementById('List_input').checked) {
                    document.getElementById('choose_user').parentNode.style = "display:block;position:absolute;z-index:2;right:0;top:0;";
                } else {
                    document.getElementById('choose_user').parentNode.style = "display:none";
                }

                document.getElementsByClassName('signature-pad--body')[0].removeChild(document.getElementById('list_area'));
                var div = document.createElement('div');
                div.id = "list_area";
                div.style.display = "block";
                //div.style.overflow = "auto";
                document.getElementsByClassName('signature-pad--body')[0].appendChild(div);
                for (ik = 0; ik < store_all_pic.length; ik++) {
                    var div2 = document.createElement('div');
                    if (store_all_pic[ik].substr(store_all_pic[ik].length - 3) == 'mp4') {
                        //div2.innerHTML = "<div class='pic_block' onclick='push_pic(this,this);'><video class='pic' autoplay></video><div class='name'></div><svg width='20' height='20' style='position:absolute;margin-left:-20px;background:#fff;' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 500 500'><path style='transition:fill ease-in .5s;fill:#fff;' stroke='rgb(206, 61, 61)' stroke-width='40' stroke-linejoin='round' d='M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z'></path></svg></div>";
                        div2.innerHTML = "<div class='pic_block' onclick='push_pic(this,this);'><img class='pic'><div class='name'></div><svg width='20' height='20' style='position:absolute;margin-left:-20px;' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 500 500'><path style='transition:fill ease-in .5s;fill:#fff;' stroke='rgb(206, 61, 61)' stroke-width='40' stroke-linejoin='round' d='M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z'></path></svg></div>";
                    } else {
                        div2.innerHTML = "<div class='pic_block' onclick='push_pic(this,this);'><img class='pic'><div class='name'></div><svg width='20' height='20' style='position:absolute;margin-left:-20px;' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 500 500'><path style='transition:fill ease-in .5s;fill:#fff;' stroke='rgb(206, 61, 61)' stroke-width='40' stroke-linejoin='round' d='M412 79c-53-40-146-17-162 68-16-85-109-108-162-68-43 32-55 94-44 137 30 119 194 217 206 224 12-7 176-105 206-224 11-43-1-105-44-137z'></path></svg></div>";
                    }
                    div2.childNodes[0].id = "push_pic" + (ik + 1);
                    div2.childNodes[0].childNodes[0].src = store_all_pic[ik];
                    div2.childNodes[0].childNodes[1].id = "push_name" + (ik + 1);
                    div2.childNodes[0].childNodes[1].innerHTML = store_all_name[ik];
                    div2.childNodes[0].childNodes[2].childNodes[0].id = "heart" + (ik + 1);
                    if (store_all_favorite[ik] == "true") {
                        div2.childNodes[0].childNodes[2].childNodes[0].style.fill = "rgb(206, 61, 61)";
                    } else {
                        div2.childNodes[0].childNodes[2].childNodes[0].style.fill = "#fff";
                    }
                    div.appendChild(div2);
                    div.appendChild(div2.childNodes[0]);
                    div.removeChild(div2);
                }
                add_press();
                //alert(document.getElementById("list_area").childNodes[0].childNodes[0].childNodes[20].src);
            }
            if (obj.checked) {
                document.getElementById("canvas").style.visibility = "hidden";
                $("#list_area").show();
                $('#canvas').hide();
            } else {
                document.getElementById("canvas").style.visibility = "visible";
                $('#list_area').hide();
                $('#canvas').show();
            }
        }

        if (document.getElementById("list_area").childNodes.length > 1) {
            document.getElementById("canvas").style.visibility = "hidden";
            document.getElementById("List_input").checked = true;
        }

        function add_press() {
            for (iu = 0; iu < document.getElementsByClassName('pic_block').length; iu++) {
                var hammer = new Hammer(document.getElementById('push_pic' + (iu + 1)));
                hammer.on('press', function (ev) {
                    the_div = ev.target.parentNode;
                    the_pic = ev.target;
                    the_name = ev.target.parentNode.childNodes[1];
                    the_id = ev.target.parentNode.id.substr(8);
                    if (the_div.childNodes.length != 3) {
                        $('#check_profile').hide();
                    } else {
                        $("#check_profile").show();
                    }
                    if ($("heart" + the_id).css('fill') == "rgb(206, 61, 61)") {
                        $("#add_favorite_btn").html('從我的最愛中移除');
                    } else {
                        $("#add_favorite_btn").html('加入我的最愛');
                    }
                    $("#press_background").show();
                    var outside = $("#signature-pad").outerHeight();
                    var inside = $("#press_button_area").outerHeight();
                    $("#press_button_area").css('top', ((outside - inside) / 2) + 'px');
                });
            }
            for (iu = 0; iu < document.getElementsByClassName('flex_block').length; iu++) {
                var hammer = new Hammer(document.getElementById('push_flex' + (iu + 1)).childNodes[1]);
                hammer.on('press', function (ev) {
                    if(ev.target.tagName == 'SPAN'){
                        the_pic = ev.target.parentNode;
                        the_div = the_pic.parentNode;
                        the_name = the_div.childNodes[1];
                        the_id = the_div.id.substr(9);
                    }else if(ev.target.tagName == 'DIV'){
                        the_pic = ev.target;
                        the_div = the_pic.parentNode;
                        the_name = the_div.childNodes[1];
                        the_id = the_div.id.substr(9);
                    }
                    if ($("flex_heart" + the_id).css('fill') == "rgb(206, 61, 61)") {
                        $("#flex_add_favorite_btn").html('從我的最愛中移除');
                    } else {
                        $("#flex_add_favorite_btn").html('加入我的最愛');
                    }
                    $("#flex_press_background").show();
                    var outside = $("#signature-pad").outerHeight();
                    var inside = $("#flex_press_button_area").outerHeight();
                    $("#flex_press_button_area").css('top', ((outside - inside) / 2) + 'px');
                });
            }
            for (iu = 0; iu < document.getElementsByClassName('category_block').length; iu++) {
                var hammer = new Hammer(document.getElementById('push_category' + (iu + 1)));
                hammer.on('press', function (ev) {
                    if(ev.target.className == 'category_title'){
                        the_div = ev.target.parentNode;
                        the_pic = ev.target;
                        the_name = ev.target.parentNode.childNodes[1];
                        the_id = ev.target.parentNode.id.substr(8);
                        $("#category_press_background").show();
                        if($("#"+the_div.id).attr("UID") == $("#userId").html()){
                            $("#category_press_background").find(".delete").show();
                        }else{
                            $("#category_press_background").find(".delete").hide();
                        }
                        var outside = $("#signature-pad").outerHeight();
                        var inside = $("#category_press_button_area").outerHeight();
                        $("#category_press_button_area").css('top', ((outside - inside) / 2) + 'px');
                    }
                });
            }
            for (iu = 0; iu < document.getElementsByClassName('subcategory_block').length; iu++) {
                var hammer = new Hammer(document.getElementById('push_subcategory' + (iu + 1)));
                hammer.on('press', function (ev) {
                    if(ev.target.className == 'subcategory_title'){
                        the_div = ev.target.parentNode;
                        the_pic = ev.target;
                        the_name = ev.target.parentNode.childNodes[1];
                        the_id = ev.target.parentNode.id.substr(8);
                        $("#subcategory_press_background").show();
                        if($("#"+the_div.id).attr("UID") == $("#userId").html()){
                            $("#subcategory_press_background").find(".delete").show();
                        }else{
                            $("#subcategory_press_background").find(".delete").hide();
                        }
                        var outside = $("#signature-pad").outerHeight();
                        var inside = $("#subcategory_press_button_area").outerHeight();
                        $("#subcategory_press_button_area").css('top', ((outside - inside) / 2) + 'px');
                    }
                });
            }
            for (iu = 0; iu < document.getElementsByClassName('note_block').length; iu++) {
                var hammer = new Hammer(document.getElementById('push_note' + (iu + 1)));
                hammer.on('press', function (ev) {
                    if(ev.target.className == 'note'){
                        the_div = ev.target.parentNode;
                        the_pic = ev.target;
                        the_name = ev.target.parentNode.childNodes[1];
                        the_id = ev.target.parentNode.id.substr(9);
                        if ($("#note_heart" + the_id).css('fill') == "rgb(206, 61, 61)") {
                            $("#note_press_button_area").find('.press_button').eq(0).html('從我的最愛中移除');
                        } else {
                            $("#note_press_button_area").find('.press_button').eq(0).html('加入我的最愛');
                        }
                        $("#note_press_background").show();
                        if($("#"+the_div.id).attr("UID") == $("#userId").html()){
                            $("#note_press_background").find(".delete").show();
                        }else{
                            $("#note_press_background").find(".delete").hide();
                        }
                        var outside = $("#signature-pad").outerHeight();
                        var inside = $("#note_press_button_area").outerHeight();
                        $("#note_press_button_area").css('top', ((outside - inside) / 2) + 'px');
                    }
                });
            }
        }
        add_press();

        var hammer = new Hammer(document.getElementsByClassName('the_profile_pic')[0]);
        hammer.on('press', function (ev) {
            $("#enlarge_pic").show();
            document.getElementById('the_enlarge_pic').src = ev.target.src;
        });

        var hammer = new Hammer(document.getElementById('the_enlarge_pic'));
        hammer.on('press', function (ev) {
            window.open(ev.target.src);
        });

        function check_profile() {
            $("#profile_details").show();
            if ((store_all_profile_pic[the_id - 1] == '') || (store_all_profile_pic[the_id - 1] == 'undefined')) {
                document.getElementById("profile_details").childNodes[1].childNodes[1].src = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0EWMqtjIbLunngIFIiHscmfS11Z8X__traP69mwbmB_f2sxv8';
            } else {
                document.getElementById("profile_details").childNodes[1].childNodes[1].src = store_all_profile_pic[the_id - 1];
            }
            document.getElementById("profile_details").childNodes[1].childNodes[3].innerHTML = store_all_name[the_id - 1];
            if ((store_all_profile_statusMessage[the_id - 1] == '') || (store_all_profile_statusMessage[the_id - 1] == 'undefined')) {
                $('#profile_details').children().eq(1).children().eq(5).hide();
            } else {
                $("#profile_details").show();
                document.getElementById("profile_details").childNodes[1].childNodes[5].innerHTML = store_all_profile_statusMessage[the_id - 1];
            }
            var outside = document.getElementById('signature-pad').offsetHeight;
            var inside = document.getElementById('profile_details').childNodes[1].offsetHeight;
            if (inside < 250) {
                document.getElementById('profile_details').childNodes[1].style.top = "15%";
            } else {
                document.getElementById('profile_details').childNodes[1].style.top = ((outside - inside) / 2) + 'px';
            }
            $('#press_background').hide();
        }

        function check_pic() {
            $("#pic_details").show();
            if (more == 'true') {
                document.getElementById("pic_details").childNodes[1].childNodes[1].innerHTML = store_all_datetime[the_id - 1];
            } else {
                document.getElementById("pic_details").childNodes[1].childNodes[1].innerHTML = store_datetime[the_id - 1];
            }
            document.getElementById("pic_details").childNodes[1].childNodes[3].src = the_pic.src;
            var outside = document.getElementById('signature-pad').offsetHeight;
            var inside = document.getElementById('pic_details').childNodes[1].offsetHeight;
            document.getElementById('pic_details').childNodes[1].style.top = ((outside - inside) / 2) + 'px';
            $('#press_background').hide();
        }

        function press_modify() {
            $('#press_background').hide();
            $('#List').click();
            
            signaturePad.clear();
            getDataUrl(the_pic.src);
            
            touchCount = 1;
        }
        
        function getDataUrl(SRC){
            var canva = document.getElementById("canvas"); 
            
            var image = new Image();
            image.crossOrigin = "anonymous";
            image.src = SRC;
            image.onload=function(){
               canva.getContext("2d").drawImage(image,0,0,canvas.offsetWidth,canvas.offsetHeight);
            };
            
            var dataURL = canva.toDataURL();
            signaturePad.fromDataURL(dataURL);
        }
        
        function flex_press_modify() {
            $('#flex_press_background').hide();
            SelectModule = $("#"+the_pic.id).attr('data-json');
            $('.Select').removeClass('Select');
            $("#"+the_pic.id).parent().addClass('Select');
            ChooseFlex(SelectModule);
        }

        function press_delete() {
            if (confirm("確定要永久刪除嗎?") == true) {
                var userId = $("#userId").html();
                location.href = "del_pic.php?project=手畫貼圖" + "&pic_url=" + the_pic.src + "&userId=" + userId;
            }
        }
        
        function flex_press_delete(){
            if (confirm("確定要永久刪除嗎?") == true) {
                var userId = $("#userId").html();
                location.href = "del_pic.php?project=訊息DIY" + "&module=flex" + "&NO=" + the_pic.id + "&userId=" + userId;
            }
        }

        function pic_press_cancel() {
            $('#pic_details').hide();
        }

        function profile_press_cancel() {
            $('#profile_details').hide();
        }

        function go_pic_link() {
            window.open(the_pic.src);
        }

        function upload_btn(obj) {
            $("#upload_area").show();
        }

        function X1() {
            $('#enlarge_pic').hide();
        }

        function X2() {
            $('#upload_area').hide();
        }

        function upload_file(obj) {
            $("#file_result").html(obj.value);
            if (obj.value != "") {
                $("#get_link").show();
            } else {
                $('#get_link').hide();
            }
        }

        function send_url() {
            var imgur_result = $('#imgur_result').val();
            if (imgur_result != '') {
                if (imgur_result.indexOf("http") != -1) {
                    if ((imgur_result.indexOf(".png") != -1) || (imgur_result.indexOf(".jpg") != -1) || (imgur_result.indexOf(".jpeg") != -1) || (imgur_result.indexOf(".gif") != -1) || (imgur_result.indexOf(".mp4") != -1)) {
                        var userId = $("#userId").html();
                        var displayName = $("#displayName").html();
                        var pictureUrl = $("#pictureUrl").html();
                        var statusMessage = $("#statusMessage").html();
                        var pic_url = $('#imgur_result').val();
                        location.href = "add_list.php?" + "displayName=" + displayName + "&pic_url=" + pic_url + "&pictureUrl=" + pictureUrl + "&statusMessage=" + statusMessage + "&userId=" + userId;
                    } else {
                        alert("目前只支援: [ .png、.jpg、.jpeg、.gif、.mp4 ] ");
                    }
                } else {
                    alert("您輸入的圖片網址有誤!");
                }
            } else {
                alert("圖片網址不得為空!");
            }
        }

        document.getElementById("erase_input").addEventListener("click", function () {
            if (document.getElementById("erase_input").checked) {
                var ctx = canvas.getContext('2d');
                ctx.globalCompositeOperation = 'destination-out';
            } else {
                var ctx = canvas.getContext('2d');
                ctx.globalCompositeOperation = 'source-over'; // default value
            }
        });

        var the_return = document.getElementById("the_return");
        the_return.addEventListener("click", function (event) {
            if (document.getElementById("add_word_input").checked) {
                if (wordCount > 0) {
                    wordCount--;
                }
            } else {
                if (touchCount > 0) {
                    touchCount--;
                }
            }
            var canva = document.getElementById("canvas");
            var cansText = canva.getContext("2d");
            if (document.getElementById("add_word_input").checked) {
                var data = signaturePad.toData();
                signaturePad.fromData(data);
                signaturePad.penColor = "rgba(255, 255, 255, 0)";
                word_list.pop();
            } else {
                var color = document.getElementById("color").style.backgroundColor;
                signaturePad.penColor = color;

                var data = signaturePad.toData();
                if (data) {
                    line_width.pop();
                    data.pop(); // remove the last dot or line
                    signaturePad.fromData(data);
                }
                signaturePad.penColor = color;
            }
            $('#add_word_text').val('');
            $('#add_word_size').val('20');
            $('#color').val('#000000');
            for (ww = 0; ww < word_list.length; ww++) {
                $('#add_word_text').val(word_list[ww][2]);
                $('#add_word_size').val(word_list[ww][3].substr(0, word_list[ww][3].length - 2));
                $('#color').val(word_list[ww][4]);
                document.getElementById("color").style.backgroundColor = word_list[ww][4];
                cansText.font = word_list[ww][3] + " " + "標楷體";
                cansText.fillStyle = word_list[ww][4];
                cansText.fillText(word_list[ww][2], word_list[ww][0], word_list[ww][1]);
            }
            
            if (wordCount>0 || touchCount>0) {
                if(the_pic.src){
                    getDataUrl(the_pic.src);
                }
            }
            /*for(tt=0;tt<data.length;tt++){
                $('#add_word_text').val(word_list[ww][2]);
                $('#add_word_size').val(word_list[ww][3].substr(0, word_list[ww][3].length - 2));
                $('#color').val(word_list[ww][4]);
                document.getElementById("color").style.backgroundColor = word_list[tt][4];
                cansText.font = word_list[tt][3] + " " + "標楷體";
                cansText.fillStyle = word_list[tt][4];
                cansText.fillText(word_list[tt][2], word_list[tt][0], word_list[tt][1]);
            }*/
        });

        var clear_all = wrapper.querySelector("[data-action=clear_all]");
        clear_all.addEventListener("click", function (event) {
            if (confirm("確定要清除嗎?") == true) {
                the_pic = '';
                touchCount = 0;
                wordCount = 0;
                signaturePad.clear();
                line_width = [];
                word_list = [];
            }
        });

        function tips_switch(obj) {
            obj.style.transition = "transform 0.5s ease-in";
            if (obj.style.transform == "rotate(-90deg)") {
                obj.style.transform = "rotate(90deg)";
                obj.style.marginLeft = "0px";
                document.getElementById('tips').style.width = "32px";
                document.getElementById('tips').style.height = "32px";
                $('#tips').children().eq(1).hide();
                $('#tips').children().eq(3).hide();
                $('#tips_area').hide();
            } else {
                obj.style.transform = "rotate(-90deg)";
                obj.style.marginLeft = "5px";
                document.getElementById('tips').style.width = "90%";
                document.getElementById('tips').style.height = "auto";
                $('#tips').children().eq(1).css('display', 'inline-block');
                $('#tips').children().eq(3).css('display', 'inline-block');
                $('#tips_area').css('display', 'inline-grid');
            }
        }

        function slideLine2(box, stf, delay, speed, h) {
            //取得id
            var slideBox = document.getElementById(box);
            if (slideBox.childNodes.length > 1) {
                //預設值 delay:幾毫秒滾動一次(1000毫秒=1秒)
                //       speed:數字越小越快，h:高度
                var delay = delay || 5000, speed = speed || 20, h = h || 19;
                var tid = null, pause = false;
                //setInterval跟setTimeout的用法可以咕狗研究一下~
                var s = function () {
                    tid = setInterval(slide, speed);
                }
                //主要動作的地方
                var slide = function () {
                    //當滑鼠移到上面的時候就會暫停
                    if (pause)
                        return;
                    //滾動條往下滾動 數字越大會越快但是看起來越不連貫，所以這邊用1
                    slideBox.scrollTop += 1;
                    //滾動到一個寬度(h)的時候就停止
                    if (slideBox.scrollTop % h == 0) {
                        //跟setInterval搭配使用的
                        clearInterval(tid);
                        //將剛剛滾動上去的前一項加回到整列的最後一項
                        slideBox.appendChild(slideBox.getElementsByTagName(stf)[0]);
                        //再重設滾動條到最上面
                        slideBox.scrollTop = 0;
                        //延遲多久再執行一次
                        setTimeout(s, delay);
                    }
                }
                //滑鼠移上去會暫停 移走會繼續動
                slideBox.onmouseover = function () {
                    pause = true;
                }
                slideBox.onmouseout = function () {
                    pause = false;
                }
                //起始的地方，沒有這個就不會動囉
                setTimeout(s, delay);
            }
        }
        slideLine2('tips_area', 'div', 5000, 20, 19);

        function componentToHex(c) {
            var hex = c.toString(16);
            return hex.length == 1 ? "0" + hex : hex;
        }

        function rgbToHex(r, g, b) {
            return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
        }

        function add_word(x, y, text, size, color, tf1, width, tf2, family) {
            var canva = document.getElementById("canvas");
            var cansText = canva.getContext("2d");
            var tester = (color.substr(4, color.length - 5)).split(",");
            color = rgbToHex(tester[0].replace(" ", "") * 1, tester[1].replace(" ", "") * 1, tester[2].replace(" ", "") * 1);
            word = [];
            word.splice(0, 0, x);
            word.splice(1, 0, y);
            word.splice(2, 0, text);
            word.splice(3, 0, size);
            word.splice(4, 0, color);
            word.splice(5, 0, family);
            if (tf1 == 'true') {
                wordCount++;
                word_list.push(word);
                word.splice(0, 1, 0);
                word.splice(1, 1, 20);
            } else {
                word_list.splice(word_list.length - 1, 1, word);
            }
            if (document.getElementById("add_word_input").checked == false) {
                if (tf2 == 'true') {
                    line_width.push(width);
                } else {
                    line_width.splice(line_width.length - 1, 1, width);
                }
            }
            //console.log('line_width = ' + line_width);
            //console.log('word_list = ' + word_list);
            for (ww = 0; ww < word_list.length; ww++) {
                cansText.font = word_list[ww][3] + " " + word_list[ww][5];
                //alert('word_list[ww][5] = ' + word_list[ww][5]);
                cansText.fillStyle = word_list[ww][4];
                cansText.fillText(word_list[ww][2], word_list[ww][0], word_list[ww][1]);
            }
            $('#change_word').hide();
            //alert(canva.offsetWidth);//307
            //alert(canva.offsetHeight);//282
        }

        var get_X = 0;
        var get_Y = 0;
        var touches = 0;

        document.getElementById("add_word_input").addEventListener("click", function (event) {
            if (document.getElementById("add_word_input").checked) {
                signaturePad.penColor = "rgba(255, 255, 255, 0)";
            } else {
                var color = document.getElementById("color").style.backgroundColor;
                signaturePad.penColor = color;
            }
        });

        //放在上面
        document.getElementById("canvas").addEventListener("touchstart", function (event) {
            if (document.getElementById("add_word_input").checked) {
                signaturePad.penColor = "rgba(255, 255, 255, 0)";
            } else {
                var color = document.getElementById("color").style.backgroundColor;
                signaturePad.penColor = color;
            }
        });

        //拖曳
        document.getElementById("canvas").addEventListener("touchmove", function (event) {
            touches = event.targetTouches.length;
            if (touches == 1) {
                // 如果这个元素的位置内只有一个手指的话
                var touch = event.targetTouches[0];

                if (document.getElementById("add_word_input").checked) {
                    //
                    get_X = touch.pageX - 25;
                    get_Y = touch.pageY - 40;
                    $('#add_word_x').val(get_X);
                    $('#add_word_y').val(get_Y);
                    var data = signaturePad.toData();
                    signaturePad.fromData(data);
                    signaturePad.penColor = "rgba(255, 255, 255, 0)";
                    add_word(get_X, get_Y, $('#add_word_text').val(), $('#add_word_size').val() + 'px', document.getElementById('color').style.backgroundColor, 'false', $('#select_width').val(), 'false', $('#select_family').val());
                }
            }
        });

        //移开
        document.getElementById("canvas").addEventListener("touchend", function (event) {
            if (touches == 1) {
                if (document.getElementById("add_word_input").checked) {
                    var data = signaturePad.toData();
                    if (data) {
                        data.pop(); // remove the last dot or line
                        if(the_pic.src){
                            getDataUrl(the_pic.src);
                        }
                        signaturePad.fromData(data);
                    }
                    signaturePad.penColor = "rgba(255, 255, 255, 0)";
                    add_word(get_X, get_Y, $('#add_word_text').val(), $('#add_word_size').val() + 'px', document.getElementById('color').style.backgroundColor, 'false', $('#select_width').val(), 'false', $('#select_family').val());
                } else {
                    touchCount++;
                    add_word(get_X, get_Y, $('#add_word_text').val(), $('#add_word_size').val() + 'px', document.getElementById('color').style.backgroundColor, 'false', $('#select_width').val(), 'true', $('#select_family').val());
                }
            }
        });

        function resizeCanvas() {
            //先儲存原先畫的 圖(畫的路徑)
            var sig = signaturePad.toDataURL();
            //更改畫布大小
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            //先清除畫布
            signaturePad.clear();
            //重畫
            signaturePad.fromDataURL(sig);
        }
        window.onresize = resizeCanvas;
        resizeCanvas();
    </script>
</body>


</html>
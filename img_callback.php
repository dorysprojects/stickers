<?php
function imgur_upload($image, $title='') {
    $url = 'https://imgur-apiv3.p.rapidapi.com/3/image';
    //$url = 'https://api.imgur.com/3/image';
    $RapidAPI_key = "y7LwTamKjBmshfmuObHC5BixJvBrp1z2uDVjsnPzhpQ5GKl5rS"; //填入自己的 RapidAPI Key
    //$access_token = "{763370828dda0245c8355628db00ae4352340dbf}"; //填入自己的 access_token
    $client_id = "2c522fbabf4e1cb"; //填入自己的 Client ID
    //$image = "http://i.imgur.com/2btFfXc.png";

    //要用 http header 送出的參數
    $http_header_array = [
            "X-RapidAPI-Key: $RapidAPI_key",
            "Authorization: Client-ID $client_id",
            "Content-Type: application/x-www-form-urlencoded",
    ];
    //要用 post 送出的參數
    $curl_post_array = [
            'image' => $image,
            'title' => $title,
    ];
    //將 http header 與 post 加進 curl 的 option
    $curl_options = [
        CURLOPT_HTTPHEADER => $http_header_array,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($curl_post_array),
    ];
    
    $curl_info = null;
    $curl_result = use_curl_opt($url, $curl_options, $curl_info);

    return $curl_result;
}

function use_curl_opt($url, $options = [], &$curl_info = null) {
    $ch = curl_init();
    
    $default_options = [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_VERBOSE => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 5.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2",
    ];
    
    curl_setopt_array($ch, $default_options);
    curl_setopt_array($ch, $options);
    $curl_result = curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if($curl_result){
        return $curl_result;
    }else{
        return $curl_error;
    }
}

if(isset($_FILES['fileData'])){
    $file = $_FILES['fileData'];
    if($file['name'] == ''){ die('image error'); }

    //讀取上傳的檔案並轉為 base64 字串
    $filepath = $file['tmp_name'];
    $handle = fopen($filepath, "r");
    $data = fread($handle, filesize($filepath));
    $image_base64 = base64_encode($data);

    //呼叫前面寫的 function imgur_upload
    $imgur_result = imgur_upload($image_base64,$file['name']);
    $imgur_result = substr(explode(',', $imgur_result)[26],8,-2);
    $imgur_result = str_replace("\/","/",$imgur_result);
    if(substr($imgur_result,strlen($imgur_result)-2) == "gi"){
        $imgur_result = $imgur_result . "f";
    }

    //顯示 imgur 回傳的 JSON 字串
    echo $imgur_result;
}
?>
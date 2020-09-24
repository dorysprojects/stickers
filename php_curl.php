<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='zh-tw' xml:lang='zh-tw'>


<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <title>LIFF[Add(POST)、Delete(DELETE)、Update(PUT)、Get(GET)]</title>
</head>

<body>


<?php
    //取得access_token
    if(1){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.line.me/v2/oauth/accessToken?",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=1585354463&client_secret=788f447d55b24e1acd595996a50fddeb",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
            echo '<br/>';
        }
    }
    
    $action = 'update';//add、delete、update、view
    
    switch($action){
        //增加liff app
        case 'add':
            $data = array(
                'view' => array(
                    'type' => 'compact',//compact,tall,full
                    'url' => './Signature%20Pad%20demo.html',
                ),
            );
            
            $curl = curl_init();
            
            $result = json_decode($response ,true);
            $access_token = $result['access_token'];
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Bearer '.$access_token,
                    "Content-Type: application/json",
                ),
                CURLOPT_POSTFIELDS => json_encode($data),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
            break;
        //刪除liff app(去下載postman，用它刪除也可以)
        case 'delete':
            $result = json_decode($response ,true);
            $access_token = $result['access_token'];
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps/1585354463-Wm0N82wO",//.../apps/{LIFF_ID}/  改{LIFF_ID}
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Bearer '.$access_token,
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
            break;
        //更新(修改)liff app ，只能10個app所以不用新增，而是用更新的
        //../line_liff/liff_php_curl.php
        case 'update':
            $data = array(
                'type' => 'tall',//compact,tall,full
                'url' => './Signature%20Pad%20demo.php',
            );
            
            $curl = curl_init();
            
            $result = json_decode($response ,true);
            $access_token = $result['access_token'];
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps/1585354463-zvPJ7dLZ/view",//.../apps/{LIFF_ID}/view/  改{LIFF_ID}
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Bearer '.$access_token,
                    "Content-Type: application/json",
                ),
                CURLOPT_POSTFIELDS => json_encode($data),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
                echo '<br/>';
                echo 'update succeed';
            }
            break;
        //查看所有liff app
        case 'view':
            $result = json_decode($response ,true);
            $access_token = $result['access_token'];
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$access_token,
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
            break;
    }
?>


</body>


</html>
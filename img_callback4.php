<?php
date_default_timezone_set("Asia/Taipei");
$fileName = "upload-" . date("YmdHis") . ".png";

$userId = $_GET['userId'];

move_uploaded_file(
    $_FILES["fileData"]["tmp_name"],
    "./upload/" . $fileName
);
$imgur_result = "https://" . $_SERVER['HTTP_HOST'] . "/stickers/upload/" . $fileName;//上傳成功後提示上傳資訊

echo "<script>
            location.href = 'Signature%20Pad%20demo.php?project=手畫貼圖' + '&imgur_result=' + '$imgur_result' + '&userId=' + '$userId';
      </script>";
?>
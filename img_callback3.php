<?php
date_default_timezone_set("Asia/Taipei");
$fileName = "upload-" . date("YmdHis") . ".png";

move_uploaded_file(
    $_FILES["fileData"]["tmp_name"],
    "./upload/" . $fileName
);

echo "https://" . $_SERVER['HTTP_HOST'] . "/stickers/upload/" . $fileName;//上傳成功後提示上傳資訊
?>
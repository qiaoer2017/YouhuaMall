<?php
//url type 参数处理 1:操作成功 2:操作失败
$type = @$_GET['type'];

$type = isset($type) && in_array(intval($type), array(1, 2)) ? intval($type) : 1;
$title = $type == 1 ? '操作成功' : '操作失败';

$msg = isset($_GET['msg']) ? trim($_GET['msg']) : '操作成功';
$url = isset($_GET['url']) ? trim($_GET['url']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?>|M-GALLARY</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/done.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="image_center">
            <?php if ($type == 1): ?>
                <span class="smile_face">:)</span>
            <?php else: ?>
                <span class="smile_face">:(</span>
            <?php endif; ?>
        </div>
        <div class="code">
            <?= $msg ?>
        </div>
        <div class="jump">
            页面在 <strong id="time" style="color: #009f95">3</strong> 秒 后跳转
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY YOUHUA.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        var time = 3;
        var url = "<?=$url ?>" || null;
        setInterval(function () {
            if (time > 1) {
                time--;
                console.log(time);
                $('#time').html(time);
            }
            else {
                $('#time').html(0);
                if (url) {
                    location.href = url;
                } else {
                    history.go(-1);
                }
            }
        }, 1000);

    })
</script>
</html>

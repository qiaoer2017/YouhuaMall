<?php
include_once './lib/fun.php';
$goods_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

if (!$goods_id) {
    msg(2, '参数非法', 'index.php');
}

//根据商品id查询商品信息
$link = mysql_connect_init('localhost', 'root', '123', 'youhua_mall');
$sql = "SELECT * FROM `youhua_goods` WHERE `id`={$goods_id} LIMIT 1";
$result = mysqli_query($link, $sql);
$goods = mysqli_fetch_assoc($result);
if (!$goods) {
    msg(2, '作品不存在');
}

unset($sql, $result);

$sql = "UPDATE `youhua_goods` SET `view`=`view`+1 WHERE id=$goods_id";
$result = mysqli_query($link, $sql);

unset($sql, $result);
$sql = "SELECT * FROM `youhua_user` WHERE `id`={$goods['user_id']} LIMIT 1";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$username = '未知';
if ($row) {
    $username = $row['username'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|imooc</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/detail.css"/>
</head>
<body class="bgf8">
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="#">登录</a></li>
            <li><a href="#">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="section" style="margin-top:20px;">
        <div class="width1200">
            <div class="fl"><img src="<?= $goods['pic'] ?>" width="720px" height="432px"/></div>
            <div class="fl sec_intru_bg">
                <dl>
                    <dt><?= $goods['name'] ?></dt>
                    <dd>
                        <p>发布人：<span><?= $username ?></span></p>
                        <p>发布时间：<span><?= date('Y年m月d日', $goods['create_time']) ?></span></p>
                        <p>修改时间：<span><?= date('Y年m月d日', $goods['update_time']) ?></span></p>
                        <p>浏览次数：<span><?= $goods['view'] ?></span></p>
                    </dd>
                </dl>
                <ul>
                    <li>售价：<br/><span class="price"><?= $goods['price'] ?></span>元</li>
                    <li class="btn"><a href="javascript:;" class="btn btn-bg-red" style="margin-left:38px;">立即购买</a>
                    </li>
                    <li class="btn"><a href="javascript:;" class="btn btn-sm-white" style="margin-left:8px;">收藏</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="secion_words">
        <div class="width1200">
            <div class="secion_wordsCon">
                <?= $goods['content'] ?>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY YOUHUA.INC</p>
</div>
</div>
</body>
</html>


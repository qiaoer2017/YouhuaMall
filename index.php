<?php
include_once "./lib/fun.php";
if ($login = check_login()) {
    $user = $_SESSION['user'];
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$page = max($page, 1);

$page_size = 3;
$offset = ($page - 1) * $page_size;

//数据库连接
$link = mysql_connect_init('localhost', 'root', '123', 'youhua_mall');

$sql = "SELECT COUNT(`id`) AS total FROM `youhua_goods`";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$total = isset($row['total']) ? $row['total'] : 0;

unset($sql, $result, $row);

//查询商品
$sql = "SELECT * FROM `youhua_goods` ORDER BY `id` ASC,`view` DESC LIMIT {$offset}, {$page_size} ";

$result = mysqli_query($link, $sql);

$goods = [];
while ($row = mysqli_fetch_assoc($result)) {
    $goods[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|首页</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <?php if ($login): ?>
                <li><span>管理员：<?= $user['username'] ?></span></li>
                <li><a href="publish.php">发布</a></li>
                <li><a href="logout.php">退出</a></li>
            <?php else: ?>
                <li><a href="login.php">登录</a></li>
                <li><a href="register.php">注册</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="content">
    <div class="banner">
        <img class="banner-img" src="./static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="img-content">
        <ul>
            <?php foreach ($goods as $v): ?>
                <li>
                    <img class="img-li-fix" src="<?= $v['pic'] ?>" alt="">
                    <div class="info">
                        <a href="<?php echo 'detail.php?id=' . $v['id'] ?>"><h3
                                    class="img_title"><?= $v['name'] ?></h3></a>
                        <p>
                            <?= $v['des'] ?>
                        </p>
                        <div class="btn">
                            <a href="edit.php?id=<?= $v['id'] ?>" class="edit">编辑</a>
                            <a href="delete.php?id=<?= $v['id'] ?>" class="del">删除</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>

        </ul>
    </div>
    <?= pages($total, $page, $page_size, 6); ?>
</div>

<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY YOUHUA.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        $('.del').on('click', function () {
            if (confirm('确认删除该画品吗?')) {
                window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>


</html>


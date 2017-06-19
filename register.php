<?php
if (!empty($_POST['username'])) {
    include_once './lib/fun.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (!$username) {
        msg(2, '用户名不能为空');
    }
    if (!$password) {
        msg(2, '密码不能为空');
    }
    if (!$repassword) {
        msg(2, '确认密码不能为空');
    }

    if ($password !== $repassword) {
        msg(2, '两次密码输入不一致');
    }

    //数据库连接操作
    $link = mysql_connect_init('localhost', 'root', '123', 'youhua_mall') or die(mysqli_error($link));
    $sql = "SELECT COUNT(`id`) FROM `youhua_user` WHERE `username`='{$username}'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_row($result);

    //验证用户名是否存在
    if (isset($row[0]) && $row[0] > 0) {
        msg(2, '用户名已存在');
    }

    //密码加密处理
    $password = create_password($password);
    unset($result, $row, $sql);

    //插入数据
    $sql = "INSERT `youhua_user` (`username`, `password`,`create_time`) VALUES('{$username}','{$password}','{$_SERVER['REQUEST_TIME']}')";
    $result = mysqli_query($link, $sql);
    if ($result) {
        $userid = mysqli_insert_id($link);
        msg(1, '注册成功，用户名是' . $username . ', 用户ID为' . $userid, 'index.php');
    } else {
        echo mysqli_error($link);
        msg(2, '注册失败');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户注册|M-GALLARY</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <link rel="stylesheet" type="text/css" href="./static/css/login.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>用户注册</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="register.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" id="password">
                        </div>
                        <div class="login-right">
                            <label class="passwd">确认</label>
                            <input type="password" class="yhmiput" name="repassword"
                                   id="repassword">
                        </div>
                        <div class="login-btn">
                            <button type="submit">注册</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span> ©2017 POWERED BY YOUHUA.INC</p>
</div>

</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val();
            if (username == '' || username.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }

            return true;
        })

    })
</script>
</html>



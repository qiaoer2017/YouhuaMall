<?php
include_once "./lib/fun.php";

session_start();

unset($_SESSION['user']);
msg(1, '退出登录成功！', 'index.php');
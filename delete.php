<?php
include_once "./lib/fun.php";
if (!check_login()) {
    msg(2, '请先登录', 'login.php');
}
$goods_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

//如果id不存在 跳转到商品列表
if (!$goods_id) {
    msg(2, '参数非法', 'index.php');
}

//根据商品id查询商品信息
$link = mysql_connect_init('localhost', 'root', '123', 'youhua_mall');
$sql = "SELECT `id` FROM `youhua_goods` WHERE `id`={$goods_id} LIMIT 1";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
if (!$row) {
    msg(2, '作品不存在');
}
unset($sql, $result);
$sql = "DELETE FROM `youhua_goods` WHERE `id`={$goods_id} LIMIT 1";
if ($result = mysqli_query($link, $sql)) {
    msg(1, '删除成功', 'index.php');
}
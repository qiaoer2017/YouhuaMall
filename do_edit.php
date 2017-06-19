<?php
//编辑商品

include_once './lib/fun.php';
if (!check_login()) {
    msg(2, '请先登录', 'login.php');
}

$user = $_SESSION['user'];

if (!empty($_POST['name'])) {
    //数据库连接
    $link = mysql_connect_init('localhost', 'root', '123', 'youhua_mall');
    if (!$goods_id = intval($_POST['id'])) {
        msg(2, '参数非法');
    }
    //根据商品id查询商品信息
    $sql = "SELECT * FROM `youhua_goods` WHERE `id`={$goods_id} LIMIT 1";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        msg(2, '作品不存在', 'index.php');
    }

    //作品名称
    $name = mysqli_real_escape_string($link, trim($_POST['name']));
    //作品价格
    $price = intval($_POST['price']);
    //作品简介
    $des = mysqli_real_escape_string($link, trim($_POST['des']));
    //作品详情
    $content = mysqli_real_escape_string($link, trim($_POST['content']));
    //用户id
    $user_id = $user['id'];


    /**
     * 验证字段合法性
     */
    $name_length = mb_strlen($name, 'utf-8');
    if ($name_length <= 0 || $name_length > 30) {
        msg(2, '作品名称应该在1-30个字符之间');
    }
    if ($price <= 0 || $price > 999999999) {
        msg(2, '作品价格应该在1-999999999之间');
    }
    $des_length = mb_strlen($des, 'utf-8');
    if ($des_length <= 0 || $des_length > 100) {
        msg(2, '作品简介应该在1-100字符之间');
    }
    if (empty($content)) {
        msg(2, '作品详情不能为空');
    }
    //更新数组
    $update = [
        'name' => $name,
        'price' => $price,
        'des' => $des,
        'content' => $content,
    ];

    //校验商品图片
    //当用户重新上传图片
    if ($_FILES['file']['size'] > 0) {
        $pic = img_upload($_FILES['file']);
        $update['pic'] = $pic;
    }

    //只更新被更改的信息
    foreach ($update as $k => $v) {
        if ($v == $row[$k]) {
            unset($update[$k]);
        }
    }
    if (empty($update)) {
        msg(2, '您没有进行任何更新', "edit.php?id=$goods_id");
    }
    $update['update_time'] = $_SERVER['REQUEST_TIME'];

    //更新sql处理
    $update_sql = "";
    foreach ($update as $k => $v) {
        $update_sql .= "`{$k}`='{$v}',";
    }
    //去除多余逗号
    $update_sql = rtrim($update_sql, ',');
    unset($sql, $result, $row);
    $sql = "UPDATE `youhua_goods` SET {$update_sql} WHERE id=$goods_id";
    if ($result = mysqli_query($link, $sql)) {
//        mysqli_affected_rows($link);//影响行数
        msg(1, '更新成功', 'index.php');
    } else {
        msg(2, '更新失败', 'edit.php?id=' . $goods_id);
    }

} else {
    msg(2, '路由非法', 'index.php');
}
<?php
include_once './lib/fun.php';

session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    msg(2, '请先登录', 'login.php');
}

$user = $_SESSION['user'];

if (!empty($_POST['name'])) {
    //数据库连接
    $link = mysql_connect_init('localhost', 'root', '123', 'youhua_mall');
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
    //图片路径
    $pic = img_upload($_FILES['file']);

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

    //入库处理
    $sql = "INSERT `youhua_goods` (`name`,`price`,`des`,`content`,`pic`,`user_id`,`create_time`,`update_time`,`view`) VALUES('{$name}','{$price}','{$des}','{$content}','{$pic}','{$user_id}','{$_SERVER['REQUEST_TIME']}','{$_SERVER['REQUEST_TIME']}',0)";
    $result = mysqli_query($link, $sql);
    if ($result) {
        msg(1, '添加成功', 'index.php');
    } else {
        echo mysqli_error($link);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|发布画品</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><span>管理员: <?= $user['username'] ?></span></li>
            <li><a href="#">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>发布画品</header>
            <form name="publish-form" id="publish-form" action="publish.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">画品名称</label><input type="text" name="name" id="name" placeholder="请输入画品名称">
                </div>
                <div class="additem">
                    <label id="for-price">价值</label><input type="text" name="price" id="price" placeholder="请输入画品价值">
                </div>
                <div class="additem">
                    <!-- 使用accept html5属性 声明仅接受png gif jpeg格式的文件                -->
                    <label id="for-file">画品</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file"
                                                          name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">画品简介</label><textarea id="des" name="des"
                                                                           placeholder="请输入画品简介"></textarea>
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-content">画品详情</label>
                    <div style="margin-left: 120px" id="container">
                        <textarea id="content" name="content"></textarea>
                    </div>

                </div>
                <div style="margin-top: 20px">
                    <button type="submit">发布</button>
                </div>

            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY YOUHUA.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script src="./static/js/kindeditor/kindeditor-all-min.js"></script>
<script src="./static/js/kindeditor/lang/zh_CN.js"></script>
<script>
    var K = KindEditor;
    K.create('#content', {
        width: '475px',
        height: '400px',
        minWidth: '30px',
        minHeight: '50px',
        items: [
            'undo', 'redo', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'clearhtml',
            'fontsize', 'forecolor', 'bold',
            'italic', 'underline', 'link', 'unlink', '|'
            , 'fullscreen'
        ],
        afterCreate: function () {
            this.sync();
        },
        afterChange: function () {
            //编辑器失去焦点时直接同步，可以取到值
            this.sync();
        }
    });
</script>

<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('画品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

            if (file == '' || file.length <= 0) {
                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
                $('#file').focus();
                return false;

            }

            if (des.length <= 0 || des.length >= 100) {
                layer.tips('画品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入画品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;

        })
    })
</script>
</html>

<?php

/**
 * 数据库连接初始化
 * @param $host
 * @param $username
 * @param $password
 * @param $dbname
 * @return mysqli
 */
function mysql_connect_init($host, $username, $password, $dbname)
{
    //数据库操作
    $link = mysqli_connect($host, $username, $password, $dbname) or die('数据库连接失败');
    mysqli_query($link, 'SET NAMES UTF8');

    return $link;
}

/**
 * 密码加密
 * @param $password
 * @return bool|string
 */
function create_password($password)
{
    if (!$password) {
        return false;
    }
    return md5(md5($password) . 'YOUHUA');
}

/**
 * 跳转到提示页面
 * @param $type 1:成功 2:失败
 * @param null $msg
 * @param null $url
 */
function msg($type, $msg = null, $url = null)
{
    $toUrl = "location:msg.php?type={$type}";
    $toUrl .= $msg ? "&msg={$msg}" : '';
    $toUrl .= $url ? "&url={$url}" : '';
    header($toUrl);
    exit;
}

/**
 * 图片上传
 * @param $file
 * @return string
 */
function img_upload($file)
{
    $now = $_SERVER['REQUEST_TIME'];
    //检查上传文件是否合法
    if (!is_uploaded_file($file['tmp_name'])) {
        msg(2, '请上传符合规范的图像');
    }

    $type = $file['type'];
    //    var_dump($type);
    //    exit();
    if (!in_array(strtolower($type), ["image/png", "image/gif", "image/jpeg", "image/jpg"])) {
        msg(2, '请上传gif、jpg或png格式的图片');
    }
    //上传目录
    $upload_path = './static/file/';
    //上传目录访问url
    $upload_url = '/static/file/';
    //文件目录
    $file_dir = date('Y/m/d/', $now);

    //检查上传目录是否存在
    if (!is_dir($upload_path . $file_dir)) {
        mkdir($upload_path . $file_dir, 0755, true);//递归创建目录
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $img = time() . uniqid() . mt_rand(1000, 9999) . '.' . $ext;
    $img_path = $upload_path . $file_dir . $img;
    $img_url = 'http://localhost' . $upload_url . $file_dir . $img;

    if (!move_uploaded_file($file['tmp_name'], $img_path)) {
        msg(2, '服务器繁忙，请稍后再试');
    }
    return $img_url;

}

/**
 * 检查用户是否登录
 * @return bool
 */
function check_login()
{
    session_start();
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        return false;
    }
    return true;
}

/**
 * 获取当前url
 * @return string
 *
 */
function get_url()
{
    $url = '';
    $url .= $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}

/**
 * 根据page生成url
 * @param $page
 * @param string $url
 * @return string
 */
function page_url($page, $url = '')
{
    $url = empty($url) ? get_url() : $url;

    $pos = strpos($url, '?');
    if (!$pos) {
        $url .= '?page=' . $page;
    } else {
        $query_string = substr($url, $pos + 1);
        //解析query_string为数组
        parse_str($query_string, $query_array);
        if (isset($query_array['page'])) {
            unset($query_array['page']);
        }
        $query_array['page'] = $page;

        //将query_arr重新拼接成query_string
        $query_string = http_build_query($query_array);
        $url = substr($url, 0, $pos) . '?' . $query_string;
    }
    return $url;
}


/**
 * 分页显示
 * @param $total 数据总数
 * @param $current_page 当前页
 * @param $page_size 每页显示条数
 * @param int $show_size 显示按钮数
 * @return string 返回生成的分页HTML代码
 */
function pages($total, $current_page, $page_size, $show_size = 6)
{
    $page_str = '';
    //仅当总数大于每页显示条数，才进行分页处理
    if ($total > $page_size) {
        $total_page = ceil($total / $page_size);
        $current_page = $current_page > $total_page ? $total_page : $current_page;


        $page_str .= '<div class="page-nav">';
        $page_str .= '<ul>';

        //当前页大于1时，存在 首页和上一页按钮
        if ($current_page > 1) {
            $page_str .= "<li><a href='" . page_url(1) . "'>首页</a></li>";
            $page_str .= "<li><a href='" . page_url($current_page - 1) . "'>上一页</a></li>";
        }

        //分页起始显示页面
        $from = max(1, $current_page - intval($show_size / 2));
        //分页结束页
        $to = $from + $show_size - 1;
        //当前结束页大于总页
        if ($to > $total_page) {
            $to = $total_page;
            $from = max(1, $to - $show_size + 1);
        }

        if ($from > 1) {
            $page_str .= "<li>...</li>";
        }
        for ($i = $from; $i <= $to; $i++) {
            if ($i != $current_page) {
                $page_str .= "<li><a href='" . page_url($i) . "'>{$i}</a></li>";
            } else {
                $page_str .= "<li><span class='curr-page'>{$i}</span></li>";
            }
        }


        if ($to < $total_page) {
            $page_str .= "<li>...</li>";
        }


        //当前页小于尾页时，存在 尾页和下一页按钮
        if ($current_page < $total_page) {
            $page_str .= "<li><a href='" . page_url($current_page + 1) . "'>下一页</a></li>";
            $page_str .= "<li><a href='" . page_url($total_page) . "'>尾页</a></li>";
        }


        $page_str .= '</ul>';
        $page_str .= '</div>';


    }
    return $page_str;
}


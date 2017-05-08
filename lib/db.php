<?php
/**
 * 数据库句柄
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4 0004
 * Time: 下午 2:01
 */
error_reporting(0);
header("content-type:text/html;charset=utf8");

/**
 * 为了安全处理利用PDO连接数据库
 * 防止SQL注入
 * 连接服务器数据库
 */
$db = array(
    'dsn'       => 'mysql:host=127.0.0.1;dbname=ttpaobu;prot=3306;charset=utf8',
    'host'      => '127.0.0.1',
    'port'      => 3306,
    'dbname'    => 'restful',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8'
);

// 修改连接默认属性
$options = array(
    // 默认是PDO::ERRORMODE_SLIENT,0， (忽略错误模式)
    PDO::ATTR_ERRMODE               =>  PDO::ERRMODE_EXCEPTION,
    // 默认是PDO::FETCH_BOTH, 4
    PDO::ATTR_DEFAULT_FETCH_MODE    =>  PDO::FETCH_ASSOC,
);

// 抛出连接服务器异常信息
try {
    $pdo = new PDO($db['dsn'], $db['username'], $db['password'], $optins);
} catch (PDOException $e) {
    echo json_encode(array('id'=>$e->getCode(), 'msg'=>$e->getMessage()));
    return;
}
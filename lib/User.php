<?php
// 引入错误码类
require_once __DIR__.'/ErrorCode.php';
/**
 * 用户类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4 0004
 * Time: 下午 2:47
 */
class User {
    /**
     * 数据库连接句柄
     */
    private $_db;

    /**
     * 构造方法,数据库连接句柄
     * User constructor.
     * @param PDO $_db The database
     */
    public function __construct($_db)
    {
        $this->_db = $_db;
    }

    /**
     * 用户登录
     * @param string $username The username
     * @param string $password The password
     */
    public function login($username, $password)
    {
        if (empty($username)) {
            Response::err(ErrorCode::USERNAME_CANNOT_EMPTY, '用户名不能为空');
        }
        if (empty($password)) {
            Response::err(ErrorCode::PASSWORD_CANNOT_EMPTY, '密码不能为空');
        }
        $length = mb_strlen($password);
        if ($length < 6 || $length > 20) {
            Response::err(ErrorCode::PASSWORD_LENGTH, '密码长度不合法');
        }

        $sql = 'SELECT * FROM `user` WHERE `username`=:username AND `password`=:password LIMIT 1';
        $password = $this->_md5($password);
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        if (!$stmt->execute()) {
            Response::err(ErrorCode::SERVER_INTERNAL_ERROR, '服务器内部错误');
        }
        $user = $stmt->fetch();
        if (!$user) {
            Response::err(ErrorCode::USERNAME_OR_PASSWORD_INVALID, '用户名或密码错误');
        }
        unset($user['password']);
        return $user;
    }

    public function  register($username, $password) {
        if (empty($username)) {
            Response::err(ErrorCode::USERNAME_CANNOT_EMPTY, '用户名不能为空');
        }
        if (empty($password)) {
            Response::err(ErrorCode::PASSWORD_CANNOT_EMPTY, '密码不能为空');
        }
        $length = mb_strlen($password);
        if ($length < 6 || $length > 20) {
            Response::err(ErrorCode::PASSWORD_LENGTH, '密码长度不合法');
        }
        if ($this->_isUsernameExists($username)) {
            Response::err(ErrorCode::USERNAME_EXISTS, '用户名已经存在');
        }
        // 写入数据
        $sql = 'INSERT INTO `user` SET `username`=:username,`password`=:password,`addtime`=:addtime';
        $addtime = time();
        $password = $this->_md5($password);
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':addtime', $addtime);
        if (!$stmt->execute()) {
            Response::err(ErrorCode::REGISTER_FAIL, '注册失败');
        }
        return array(
            'userId'   => $this->_db->lastInsertId(),
            'username' => $username,
            'addtime'  => $addtime
        );
    }


    /**
     * md5加密
     * @param string $string The string
     * @param string string $key The key
     * @return string description_of_the_return_value
     */
    private function _md5($string, $key='ming') {
        return md5($string.$key);
    }

    /**
     * 判断用户名是否存在
     * @param string $username The username
     * @return bool
     */
    private function _isUsernameExists($username) {
        $exists = false;
        // : 是一个占位符,为了防止sql注入
        $sql = 'SELECT * FROM `user` WHERE `username`=:username LIMIT 1';
        $stmt = $this->_db->prepare($sql); // 进行预处理
        $stmt->bindParam(':username', $username); // 绑定变量
        $stmt->execute(); // 执行查询
        $result = $stmt->fetch();
        // 以数组索引的方式返回
        if (!empty($result)) {
            $exists = true;
        }
        return $exists;
    }
}
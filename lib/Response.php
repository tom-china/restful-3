<?php
/**
 * 错误码JSON处理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4 0004
 * Time: 下午 2:51
 */
class Response {

    static public function json($code,$msg,$data) {
        if (!is_numeric($code)) {
            return '';
        }
        $result = array(
            'code'	=> $code,
            'msg'   => $msg,
            'result'  => $data
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    static public function err($code,$msg) {
        if (!is_numeric($code)) {
            return '';
        }

        $array = array(
            'code'	=> $code,
            'msg'   => $msg
        );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }
}

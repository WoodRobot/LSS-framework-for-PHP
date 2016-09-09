<?php
return array (
    //验证码位数
    'CODE_LEN' => 4,
    //默认时区
    'DEFAULT_TIME_ZONE' => 'PRC',
    //是否开启SESSION
    'SESSION_AUTO_STATRT' => true,
    'VAR_ACTION' => 'a',
    'VAR_CONTROLLER' => 'c',
    //是否开启日志
    'SAVE_LOG' => true,
    //错误跳转地址
    'ERROR_URL' => '',
    //错误提示信息
    'ERROR_MSG' => '系统错误!',
    //自动加载Common/Lib目录下文件，支持多个文件
    'AUTO_LOAD_FILE' => array(),
    //数据库信息
    'DB_CHARSET' => 'utf8',
    'DB_HOST' => 'localhost',
    'DB_PORT' => '3306',
    'DB_PWD' => 'root',
    'DB_USER' => 'root',
    'DB_DATEBASE' => 'manager',
    'DB_PREFIX' => '',
    //Smarty
    'SMARTY_ON' => true,
    'LEFT_DELIMITER' =>  '{',
    'RIGHT_DELIMITER' =>  '}',
    'CACHE_ON' => true,
    'CACHE_TIME' => 1,
    'CORE_TPL' => 'LSS'
    );
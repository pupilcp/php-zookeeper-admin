<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$loadedServices = [];

/**
 * 加载服务类.
 *
 * @param string $serviceName 服务类名
 * @param array  $params      参数集合
 *
 * @throws
 *
 * @return object
 */
function loadService($serviceName, $params = [])
{
    global $loadedServices;
    $file = APPPATH . 'services' . DIRECTORY_SEPARATOR . $serviceName . 'Service.php';
    if (is_file($file)) {
        if (!isset($loadedServices[$serviceName])) {
            include_once $file;
            $loadedServices[$serviceName] = new $serviceName(...$params);
        }

        return $loadedServices[$serviceName];
    } else {
        throw new Exception('Service ' . $file . ' Not Exists', 1);
    }
}

/**
 * 输出json.
 *
 * @param int    $code    响应编号
 * @param string $message 提示信息
 * @param array  $data    数据集合
 *
 * @throws
 */
function echoJson($code, $message, $data = null)
{
    echo json_encode([
        'code'      => $code,
        'message'   => $message,
        'data'      => $data,
        'timestamp' => time(),
    ]);
    exit();
}

/**
 * 分页创建.
 *
 * @param mixed $baseUrl
 * @param mixed $pageFlag
 *
 * @return string
 */
function getRequestUri($baseUrl = '', $pageFlag = 'page')
{
    $uri      = $_SERVER['REQUEST_URI'];
    $uriRow   = explode('?', $uri);
    $preUri   = $uriRow[0];
    $queryStr = '';
    if (isset($uriRow[1])) {
        $params = explode('&', $uriRow[1]);
        foreach ($params as $k => $p) {
            if (0 === strpos($p, $pageFlag . '=')) {
                unset($params[$k]);
            }
        }
        if (count($params) > 0) {
            $queryStr = implode('&', $params);
        }
    }

    return $baseUrl . $preUri . ($queryStr ? '?' . $queryStr : '');
}

/**
 * 判断当前用户的某个action是否有权限访问.
 *
 * @param string $action 操作标识   格式： controller_action
 *
 * @return bool
 */
function checkAcl($action)
{
    if (ADMINISTRATOR_ROLE == $_SESSION['role_name']) {
        return true;
    }
    if (empty($_SESSION['role_acl'])) {
        return false;
    }
    $acls = json_decode($_SESSION['role_acl'], true);

    return in_array($action, $acls);
}

<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Base extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->accessAcl();
    }

    /**
    * 访问限制过滤
    */
    private function accessAcl()
    {
        $controllerId = strtolower($this->router->fetch_class());
        $actionId     = strtolower($this->router->fetch_method());
        $user         = $this->session->userdata();
        if (empty($user['username'])) {
			if (IS_AJAX) {
				echoJson(99999, 'Please Login');
			} else {
				header('Location:/login/index');
				return false;
            }
        }
        if (ADMINISTRATOR_ROLE == $user['role_name']) {
            return true;
        }
        //刷新权限
        if (!$this->refreshLoginUserAcl()) {
            $user = $this->session->userdata();
        }
        //访问受限，特殊处理node查看权限
        if ('node' == $controllerId && in_array($actionId, ['getnodesinfo', 'getnodedetail'])) {
            $actionId = 'index';
        }
        $acl = $controllerId . '_' . $actionId;
        if (!empty($user['role_acl'])) {
			$aclList = json_decode($user['role_acl'], true);
			$configAcls = include_once APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.php';
			$actionAll  = [];
			foreach ($configAcls as $config) {
				foreach ($config['action'] as $action) {
					$actionAll[] = $action['acl'];
				}
			}
            if (in_array($actionAll) && in_array($acl, $aclList)) {
                return true;
            }
        }
        if (IS_AJAX) {
            echoJson(99999, 'Forbidden Access 403');
        } else {
            header('Location:/manage/error');
            return false;
        }
    }

    //刷新session中的权限,5分钟获取
    private function refreshLoginUserAcl()
    {
        $user = $this->session->userdata();
        if (time() - (int) $user['acl_time'] >= 300) {
            $dbstatement = $this->db->select('role_acl')->where('id', $user['role_id'])->get('zk_role');
            $data        = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
            $this->session->set_userdata('role_acl', $data['role_acl']);
            $this->session->set_userdata('acl_time', time());

            return false;
        }

        return true;
    }
}

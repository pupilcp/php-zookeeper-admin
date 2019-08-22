<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

include_once 'Base.php';
class Manage extends Base
{
    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->load->view('manage/index');
    }

    /**
     * Left menu.
     */
    public function menu()
    {
        $menu = include_once APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'menu.php';
        //获取登录用户权限
        $user = $this->session->userdata();
        if (ADMINISTRATOR_ROLE == $user['role_name']) {
            echo json_encode($menu);
        } elseif (empty($user['role_acl'])) {
            echo json_encode([$menu[0]]);
        } else {
            $roleAcls   = json_decode($user['role_acl'], true);
            $configAcls = include_once APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.php';
            $actionAll  = [];
            foreach ($configAcls as $config) {
                foreach ($config['action'] as $action) {
                    $actionAll[] = $action['acl'];
                }
            }
            foreach ($menu as $key => $item) {
                if (!empty($item['list'])) {
                    $count = 0;
                    foreach ($item['list'] as $k => $u) {
                        $url = str_replace('/', '_', ltrim($u['url'], '/'));
                        if (in_array($url, $actionAll)) {
                            if (in_array($url, $roleAcls)) {
                                $count++;
                            } else {
                                unset($menu[$key]['list'][$k]);
                            }
                        } else {
                            $count++;
                        }
                    }
                    if (0 == $count) {
                        unset($menu[$key]);
                    }
                }
            }
            echo json_encode($menu);
        }
    }

    public function error()
    {
        $this->load->view('manage/error');
    }
}

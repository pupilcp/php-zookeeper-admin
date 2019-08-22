<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     *
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $user = $this->session->userdata();
        //var_dump($user);die;
        if (!empty($user['username'])) {
            header('Location:/manage/index');
        }
        $this->load->view('login/login');
    }

    public function signin()
    {
        $uname       = trim($this->input->post('uname'));
        $pwd         = trim($this->input->post('pwd'));
        $dbstatement = $this->db->from('zk_user u')->join('zk_role r', 'u.role_id=r.id', 'left')->select('u.id user_id,u.username,u.password,u.is_active,u.role_id,r.role_name,r.is_active role_active,r.is_delete role_delete,r.role_acl')
                    ->where('u.username', $uname)->where('u.is_delete', 0)->get();
        $user = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
        if (empty($user)) {
            echoJson(1001, '用户名或密码错误');
        }
        if (0 == $user['is_active']) {
            echoJson(1002, '当前用户被禁用');
        }
        if (1 == $user['role_delete'] || 0 == $user['role_active']) {
            echoJson(1003, '当前用户角色已失效');
        }
        if ($user['password'] != md5(md5($pwd) . config_item('passwd_encrypt_key'))) {
            echoJson(1001, '用户名或密码错误');
        }
        //登录成功
        unset($user['password']);
        $user['acl_time'] = $user['login_time'] = time();
        $this->session->set_userdata($user);
        $this->db->set(['login_time' => $user['login_time']])->where('id', $user['user_id'])->update('zk_user');
        echoJson(1000, '登录成功');
    }

    public function logout()
    {
        $user = $this->session->userdata();
        $this->session->unset_userdata(array_keys($user));
        header('Location:/login/index');
    }
}

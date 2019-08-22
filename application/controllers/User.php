<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

include_once 'Base.php';
class User extends Base
{
    private $tableName     = 'zk_user';
    private $roleTableName = 'zk_role';

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $keyword = trim($this->input->get('keyword'));
        $page    = (int) $this->input->get('page');
        if (empty($page)) {
            $page = 1;
        }
        $pageSize = 10;
        $this->db->from($this->tableName)->select('id,username,is_active,email,role_id,create_time,login_time');
        if (!empty($keyword)) {
            $this->db->where('username like ', '%' . $keyword . '%')->or_where('email', $keyword);
        }
        $this->db->where('is_delete', 0);
        $db    = clone $this->db;
        $total = $this->db->count_all_results();
        //查询数据
        $this->db    = $db;
        $dbstatement = $this->db->limit($pageSize, ($page - 1) * $pageSize)->get();
        $users       = $dbstatement->result_id->fetchAll(PDO::FETCH_ASSOC);
        //获取角色ID对应的角色名
        $dbstatement = $this->db->from($this->roleTableName)->select('id,role_name')->where('is_delete', 0)->get();
        $roles       = $dbstatement->result_id->fetchAll(PDO::FETCH_ASSOC);
        $roles       = array_column($roles, null, 'id');

        $this->load->library('paginator', ['totalRows' => $total, 'listRows' => $pageSize, 'url' => getRequestUri(), 'nowPage' => $page, 'config' => []], 'paginator');
        $this->load->view('user/index', [
            'total'    => $total,
            'users'    => $users,
            'page'     => $page,
            'roles'    => $roles,
            'pageSize' => $pageSize,
            'keyword'  => $keyword,
            'pageLink' => $this->paginator->show(),
        ]);
    }

    /**
     * 添加.
     */
    public function create()
    {
        if (IS_POST) {
            $pwd = trim($this->input->post('pwd'));
            if (true !== ($result = $this->verify($pwd))) {
                echoJson(1001, $result);
            } else {
                $data['username']    = trim($this->input->post('name'));
                $data['password']    = md5(md5($pwd) . config_item('passwd_encrypt_key'));
                $data['email']       = trim($this->input->post('email'));
                $data['is_active']   = (int) trim($this->input->post('state'));
                $data['role_id']     = (int) $this->input->post('role');
                $data['create_time'] = time();
                $data['update_time'] = $data['create_time'];
                try {
                    $dbstatement = $this->db->from($this->tableName)->select('id')->where('username', $data['username'])->where('is_delete', 0)->get();
                    $user        = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
                    if (!empty($user)) {
                        throw new Exception('该用户名已存在', 1003);
                    }
                    if ($this->db->insert($this->tableName, $data)) {
                        echoJson(1000, 'success');
                    } else {
                        echoJson(1002, '编辑用户失败');
                    }
                } catch (Throwable $t) {
                    echoJson($t->getCode(), $t->getMessage());
                }
            }
        } else {
            //获取角色列表
            $dbstatement = $this->db->from($this->roleTableName)->select('id,role_name')->where('is_delete', 0)->get();
            $roles       = $dbstatement->result_id->fetchAll(PDO::FETCH_ASSOC);
            $this->load->view('user/edit', ['roles' => $roles]);
        }
    }

    /**
     * 编辑.
     */
    public function update()
    {
        if (IS_POST) {
            $pwd = trim($this->input->post('pwd'));
            if (true !== ($result = $this->verify($pwd, false))) {
                echoJson(1001, $result);
            } else {
                //$data['username'] = trim($this->input->post('name'));
                if (!empty($pwd)) {
                    $data['password'] = md5(md5($pwd) . config_item('passwd_encrypt_key'));
                }
                $data['email']       = trim($this->input->post('email'));
                $data['is_active']   = (int) trim($this->input->post('state'));
                $data['role_id']     = (int) $this->input->post('role');
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', (int) $this->input->post('userId'))->update($this->tableName)) {
                        echoJson(1000, 'success');
                    } else {
                        echoJson(1002, '添加用户失败');
                    }
                } catch (Throwable $t) {
                    echoJson($t->getCode(), $t->getMessage());
                }
            }
        } else {
            $userId      = (int) $this->input->get('id');
            $dbstatement = $this->db->from($this->tableName)->select('id,username,email,is_active,role_id')->where('id', $userId)->where('is_delete', 0)->get();
            $data        = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
            //获取角色列表
            $dbstatement   = $this->db->from($this->roleTableName)->select('id,role_name')->where('is_delete', 0)->get();
            $roles         = $dbstatement->result_id->fetchAll(PDO::FETCH_ASSOC);
            $data['roles'] = $roles;
            $this->load->view('user/edit', $data);
        }
    }

    /**
     * 启用.
     */
    public function active()
    {
        if (IS_POST) {
            $userId = (int) $this->input->post('userId');
            if (empty($userId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_active']   = 1;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $userId)->update($this->tableName)) {
                        echoJson(1000, '启用成功');
                    } else {
                        echoJson(1003, '启用失败');
                    }
                } catch (Throwable $t) {
                    echoJson($t->getCode(), $t->getMessage());
                }
            }
        } else {
            echoJson(1002, 'Error Request Type');
        }
    }

    /**
     * 禁用.
     */
    public function forbid()
    {
        if (IS_POST) {
            $userId = (int) $this->input->post('userId');
            if (empty($userId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_active']   = 0;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $userId)->update($this->tableName)) {
                        echoJson(1000, '禁用成功');
                    } else {
                        echoJson(1003, '禁用失败');
                    }
                } catch (Throwable $t) {
                    echoJson($t->getCode(), $t->getMessage());
                }
            }
        } else {
            echoJson(1002, 'Error Request Type');
        }
    }

    /**
     * 删除.
     */
    public function delete()
    {
        if (IS_POST) {
            $userId = (int) $this->input->post('userId');
            if (empty($userId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_delete']   = 1;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $userId)->update($this->tableName)) {
                        echoJson(1000, '删除成功');
                    } else {
                        echoJson(1003, '删除失败');
                    }
                } catch (Throwable $t) {
                    echoJson($t->getCode(), $t->getMessage());
                }
            }
        } else {
            echoJson(1002, 'Error Request Type');
        }
    }

    /**
     * 校验表单数据.
     *
     * @param bool   $isCreate 是否为添加用户操作
     * @param string $pwd      密码
     *
     * @return mixed
     */
    private function verify($pwd, $isCreate = true)
    {
        $config = [
            [
                'field'  => 'name',
                'rules'  => 'required|max_length[20]',
                'errors' => [
                    'required'   => '用户名不能为空',
                    'max_length' => '用户名不能超过20个字符',
                ],
            ],
            [
                'field'  => 'email',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => '邮箱不能为空',
                    'valid_email' => '邮箱格式错误',
                ],
            ],
        ];
        if ($isCreate || !empty($pwd)) {
            $config[] = [
                'field'  => 'pwd',
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => '密码不能为空',
                    'min_length' => '密码至少输入8个字符',
                ],
            ];
        }
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            return validation_errors();
        } else {
            return true;
        }
    }
}

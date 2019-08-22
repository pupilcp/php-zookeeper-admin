<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

include_once 'Base.php';
class Role extends Base
{
    private $tableName = 'zk_role';

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
        $this->db->from($this->tableName)->select('id,role_name,is_active,create_time,update_time');
        if (!empty($keyword)) {
            $this->db->where('role_name like ', '%' . $keyword . '%');
        }
        $this->db->where('is_delete', 0);
        $db    = clone $this->db;
        $total = $this->db->count_all_results();

        $this->db    = $db;
        $dbstatement = $this->db->limit($pageSize, ($page - 1) * $pageSize)->get();
        $roles       = $dbstatement->result_id->fetchAll(PDO::FETCH_ASSOC);
        $this->load->library('paginator', ['totalRows' => $total, 'listRows' => $pageSize, 'url' => getRequestUri(), 'nowPage' => $page, 'config' => []], 'paginator');
        $this->load->view('role/index', [
            'total'    => $total,
            'roles'    => $roles,
            'page'     => $page,
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
            $data['role_name']    = trim($this->input->post('name'));
            $data['is_active']    = (int) trim($this->input->post('state'));
            $data['create_time']  = time();
            $data['update_time']  = $data['create_time'];
            $acl                  = (array) $this->input->post('acl');
            $data['role_acl']     = !empty($acl) ? json_encode(array_values($acl)) : '';
            try {
                $dbstatement = $this->db->from($this->tableName)->select('id')->where('role_name', $data['role_name'])->where('is_delete', 0)->get();
                $role        = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
                if (!empty($role)) {
                    throw new Exception('该角色名已存在', 1003);
                }
                if ($this->db->insert($this->tableName, $data)) {
                    echoJson(1000, 'success');
                } else {
                    echoJson(1002, '添加角色失败');
                }
            } catch (Throwable $t) {
                echoJson($t->getCode(), $t->getMessage());
            }
        } else {
            $acls = include_once APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.php';
            $this->load->view('role/edit', ['acls' => $acls]);
        }
    }

    /**
     * 编辑.
     */
    public function update()
    {
        if (IS_POST) {
            //$data['role_name']   = trim($this->input->post('name'));
            $data['is_active']   = (int) trim($this->input->post('state'));
            $data['update_time'] = time();
            $acl                 = (array) $this->input->post('acl');
            $data['role_acl']    = !empty($acl) ? json_encode(array_values($acl)) : '';
            try {
                if (false !== $this->db->set($data)->where('id', (int) $this->input->post('roleId'))->update($this->tableName)) {
                    echoJson(1000, 'success');
                } else {
                    echoJson(1002, '修改角色失败');
                }
            } catch (Throwable $t) {
                echoJson($t->getCode(), $t->getMessage());
            }
        } else {
            $roleId      = (int) $this->input->get('id');
            $dbstatement = $this->db->from($this->tableName)->select('id,role_name,role_acl,is_active')->where('id', $roleId)->where('is_delete', 0)->get();

            $data             = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
            $acls             = include_once APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acl.php';
            $data['acls']     = $acls;
            $data['roleAcls'] = !empty($data['role_acl']) ? json_decode($data['role_acl'], true) : [];
            unset($data['role_acl']);
            $this->load->view('role/edit', $data);
        }
    }

    /**
     * 启用.
     */
    public function active()
    {
        if (IS_POST) {
            $roleId = (int) $this->input->post('roleId');
            if (empty($roleId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_active']   = 1;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $roleId)->update($this->tableName)) {
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
            $roleId = (int) $this->input->post('roleId');
            if (empty($roleId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_active']   = 0;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $roleId)->update($this->tableName)) {
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
            $roleId = (int) $this->input->post('roleId');
            if (empty($roleId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_delete']   = 1;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $roleId)->update($this->tableName)) {
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
     * @param bool   $isCreate 是否为添加角色操作
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
                    'required'   => '角色名不能为空',
                    'max_length' => '角色名不能超过20个字符',
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

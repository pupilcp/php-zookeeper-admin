<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

include_once 'Base.php';
class Config extends Base
{
    private $tableName = 'zk_config';

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
        $this->db->from($this->tableName)->select('id,name,content,intro,create_time,update_time,update_user');
        if (!empty($keyword)) {
            $this->db->where('intro like ', '%' . $keyword . '%')->or_where('name', $keyword);
        }
        $this->db->where('is_delete', 0);
        $db    = clone $this->db;
        $total = $this->db->count_all_results();

        $this->db    = $db;
        $dbstatement = $this->db->limit($pageSize, ($page - 1) * $pageSize)->get();
        $configs     = $dbstatement->result_id->fetchAll(PDO::FETCH_ASSOC);
        $this->load->library('paginator', ['totalRows' => $total, 'listRows' => $pageSize, 'url' => getRequestUri(), 'nowPage' => $page, 'config' => []], 'paginator');
        $this->load->view('config/index', [
            'total'    => $total,
            'configs'  => $configs,
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
            $data['name']        = trim($this->input->post('name'));
            $data['intro']       = trim($this->input->post('intro'));
            $data['content']     = trim($this->input->post('content'));
            $data['create_time'] = time();
            $data['update_time'] = $data['create_time'];
            try {
                $dbstatement = $this->db->from($this->tableName)->select('id')->where('name', $data['name'])->where('is_delete', 0)->get();
                $config      = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
                if (!empty($config)) {
                    throw new Exception('该配置名已存在', 1003);
                }
                if ($this->db->insert($this->tableName, $data)) {
                    echoJson(1000, 'success');
                } else {
                    echoJson(1002, '编辑配置失败');
                }
            } catch (Throwable $t) {
                echoJson($t->getCode(), $t->getMessage());
            }
        } else {
            $this->load->view('config/edit');
        }
    }

    /**
     * 编辑.
     */
    public function update()
    {
        if (IS_POST) {
            //$data['name']   = trim($this->input->post('name'));
            $data['intro']       = trim($this->input->post('intro'));
            $data['content']     = trim($this->input->post('content'));
            $data['update_time'] = time();
            $data['update_user'] = $this->session->userdata('username');
            try {
                if (false !== $this->db->set($data)->where('id', (int) $this->input->post('configId'))->update($this->tableName)) {
                    echoJson(1000, 'success');
                } else {
                    echoJson(1002, '修改配置失败');
                }
            } catch (Throwable $t) {
                echoJson($t->getCode(), $t->getMessage());
            }
        } else {
            $configId      = (int) $this->input->get('id');
            $dbstatement   = $this->db->from($this->tableName)->select('id,name,intro,content')->where('id', $configId)->where('is_delete', 0)->get();

            $data = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
            $this->load->view('config/edit', $data);
        }
    }

    /**
     * 删除.
     */
    public function delete()
    {
        if (IS_POST) {
            $configId = (int) $this->input->post('configId');
            if (empty($configId)) {
                echoJson(1001, 'Error Param');
            } else {
                $data['is_delete']   = 1;
                $data['update_time'] = time();
                try {
                    if (false !== $this->db->set($data)->where('id', $configId)->update($this->tableName)) {
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
}

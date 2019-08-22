<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

include_once 'Base.php';
class Node extends Base
{
    public function __construct()
    {
        parent::__construct();
		//zookeeper扩展检测
		if(!extension_loaded('zookeeper')){
			exit('Please Install Zookeeper Extension <a href="javascript:history.back();">[返回]</a>');
		}

		$dbstatement = $this->db->from('zk_config')->select('id,content')->where('name', 'zookeeper_url')->where('is_delete', 0)->get();
        $data        = $dbstatement->result_id->fetch(PDO::FETCH_ASSOC);
        if (empty($data['content'])) {
            exit('请先完善zookeeper服务地址的配置，<a href="/config/update?id=' . $data['id'] . '">点击配置</a>');
        }
        $this->load->library('ZookeeperClient', ['address' => trim($data['content'])], 'zk');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $path = rtrim(trim($this->input->get('path')), '/');
        if (empty($path)) {
            $path = '/';
        }
        $stat     = [];
        $nodeVal  = $this->zk->get($path, $stat);
        $nodeName = $this->getNodeExtName($path);
        //权限
        $acls      = $this->session->userdata['role_acl'];
        $actionAcl = ['create' => 0, 'update' => 0, 'delete' => 0];
        if (ADMINISTRATOR_ROLE == $this->session->userdata['role_name']) {
            $actionAcl['create'] = 1;
            $actionAcl['update'] = 1;
            $actionAcl['delete'] = 1;
        } elseif (!empty($acls)) {
            $aclsArr = json_decode($acls, true);
            if (in_array('node_createnode', $aclsArr)) {
                $actionAcl['create'] = 1;
            }
            if (in_array('node_updatenode', $aclsArr)) {
                $actionAcl['update'] = 1;
            }
            if (in_array('node_deletenode', $aclsArr)) {
                $actionAcl['delete'] = 1;
            }
        }
        $this->load->view('node/index', [
            'nodeName'  => $nodeName,
            'nodeVal'   => $nodeVal,
            'childNum'  => $stat['numChildren'] ?? 0,
            'nodePath'  => $path,
            'actionAcl' => $actionAcl,
        ]);
    }

    /**
     * 根据路径获取节点信息.
     */
    public function getNodesInfo()
    {
        $path = trim($this->input->get('path'));
        if (empty($path)) {
            echoJson(1001, 'Error Param');
        }
        $childList = $this->zk->getChildren($path);
        if (!empty($childList)) {
            $data = [];
            foreach ($childList as $node) {
                $nodePath = rtrim($path, '/') . '/' . $node;
                $stat     = [];
                $nodeVal  = $this->zk->get($nodePath, $stat);
                $data[]   = [
                    'nodeName' => $this->getNodeExtName($nodePath),
                    'nodePath' => $nodePath,
                    'nodeVal'  => $nodeVal ?? '',
                    'childNum' => $stat['numChildren'] ?? 0,
                ];
            }
            echoJson(1000, 'Success', $data);
        } else {
            echoJson(999, 'Empty ChildNode');
        }
    }

    /**
     * 根据路径获取节点详细信息.
     */
    public function getNodeDetail()
    {
        $path = trim($this->input->get('path'));
        if (empty($path)) {
            echoJson(1001, 'Error Param');
        }
        $stat     = [];
        $nodeVal  = $this->zk->get($path, $stat) ?? '';
        echoJson(1000, 'Success', array_merge(['nodeVal' => $nodeVal], $stat));
    }

    /**
     * 创建节点.
     */
    public function createNode()
    {
        $path = trim($this->input->post('path'));
        $val  = trim($this->input->post('val'));
        $attr = trim($this->input->post('attr'));
        if (empty($path) || empty($val)) {
            echoJson(1001, 'Error Param');
        }
        $sequence = null;
        if (1 == $attr) {
            $sequence = Zookeeper::SEQUENCE;
        }
        $rs = $this->zk->makeNode($path, $val, [], $sequence);
        if ($rs) {
            echoJson(1000, 'Success');
        } else {
            echoJson(1002, 'CREATE FAIL');
        }
    }

    /**
     * 更新节点.
     */
    public function updateNode()
    {
        $path = trim($this->input->post('path'));
        $val  = trim($this->input->post('val'));
        if (empty($path) || empty($val)) {
            echoJson(1001, 'Error Param');
        }
        $rs = $this->zk->set($path, $val);
        if ($rs) {
            echoJson(1000, 'Success');
        } else {
            echoJson(1002, 'Update Fail');
        }
    }

    /**
     * 删除节点.
     */
    public function deleteNode()
    {
        $path = trim($this->input->post('path'));
        if (empty($path)) {
            echoJson(1001, 'Error Param');
        }
        try {
            $rs = $this->zk->deleteNode($path);
            if (null === $rs) {
                echoJson(1002, 'Node Not Exist');
            } elseif ($rs) {
                echoJson(1000, 'Success');
            } else {
                echoJson(1003, 'Delete Fail');
            }
        } catch (Throwable $e) {
            if ($e->getCode() == -111) {
                echoJson(1004, 'Error: ChildNode Not Empty');
            } else {
                echoJson(1003, 'Delete Fail');
            }
        }
    }

    /**
     * 获取节点后缀名.
     *
     * @param string $path 节点路径
     *
     * @return string
     */
    private function getNodeExtName($path)
    {
        $nodeName = '/';
        if ('/' != $path) {
            $pathArr  = explode('/', $path);
            $nodeName = $pathArr[count($pathArr) - 1];
        }

        return $nodeName;
    }
}

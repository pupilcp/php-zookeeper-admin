<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Node extends CI_Controller
{
    public function __construct(){
       parent::__construct();
       $this->load->library('ZookeeperClient', ['address' => '192.168.11.87:2181'], 'zk');
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
        $this->load->view('node/index', [
            'nodeName' => $nodeName,
            'nodeVal'  => $nodeVal,
            'childNum' => $stat['numChildren'] ?? 0,
            'nodePath' => $path,
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
        $val = trim($this->input->post('val'));
        $attr = trim($this->input->post('attr'));
        if (empty($path) || empty($val)) {
            echoJson(1001, 'Error Param');
        }
        $sequence = null;
        if($attr == 1){
            $sequence = Zookeeper::SEQUENCE;
        }
        $rs = $this->zk->makeNode($path, $val, [], $sequence);
        if($rs){
            echoJson(1000, 'Success');
        }else{
            echoJson(1002, 'CREATE FAIL');
        }
    }

    /**
     * 更新节点.
     */
    public function updateNode()
    {
        $path = trim($this->input->post('path'));
        $val = trim($this->input->post('val'));
        if (empty($path) || empty($val)) {
            echoJson(1001, 'Error Param');
        }
        $rs = $this->zk->set($path, $val);
        if($rs){
            echoJson(1000, 'Success');
        }else{
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
        try{
            $rs = $this->zk->deleteNode($path);
            if($rs === null){
                echoJson(1002, 'Node Not Exist');
            }else if($rs){
                echoJson(1000, 'Success');
            }else{
                echoJson(1003, 'Delete Fail');
            }
        }catch(Throwable $e){
            if($e->getCode() == -111){
                echoJson(1004, 'Error: ChildNode Not Empty');
            }else{
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

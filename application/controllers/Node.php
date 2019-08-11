<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Node extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->view('node/index');
	}

	/**
	 */
	public function getlist()
	{
		include_once APPPATH . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'ZookeeperClient.php';
		$zk = new ZookeeperClient('192.168.233.130:2181');
				var_dump($zk);

		echo '<pre>';var_dump($zk->getChildren('/'));
	}
}

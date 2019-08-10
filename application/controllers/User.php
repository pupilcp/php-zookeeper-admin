<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->view('user/index');
	}

	/**
	 * 添加
	 */
	public function create()
	{
		$this->load->view('user/edit');
	}

	/**
	 * 编辑
	 */
	public function update()
	{
		$this->load->view('user/edit');
	}
}

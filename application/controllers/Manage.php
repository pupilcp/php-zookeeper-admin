<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->view('manage/index');
	}

	/**
	 * Left menu
	 */
	public function menu()
	{
		$menu = include_once APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'menu.php';
		echo json_encode($menu);
	}
}

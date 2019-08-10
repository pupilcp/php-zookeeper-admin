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
	public function create()
	{

	}
}

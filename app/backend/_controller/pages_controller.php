<?php


class PagesController extends Controller
{

	public function index($param)
	{
		$this->view('pages/index', $data);
	}

}
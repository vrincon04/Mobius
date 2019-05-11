<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		
		if (isset($_SESSION['grants']['pos']))
			redirect('pos');
			
		$this->data = [
			'styles' => [
				'public/plugins/morrisjs/morris.css',
			],
			'scripts' => [
				'public/plugins/jquery-countto/jquery.countTo.js',
				'public/plugins/raphael/raphael.min.js',
				'public/plugins/morrisjs/morris.js',
				'public/plugins/chartjs/Chart.bundle.js',
				'public/plugins/flot-charts/jquery.flot.js',
				'public/plugins/flot-charts/jquery.flot.resize.js',
				'public/plugins/flot-charts/jquery.flot.pie.js',
				'public/plugins/flot-charts/jquery.flot.categories.js',
				'public/plugins/flot-charts/jquery.flot.time.js',
				'public/plugins/jquery-sparkline/jquery.sparkline.js',
			]
		];
		parent::index();
	}
}

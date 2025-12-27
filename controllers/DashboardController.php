<?php

require_once 'AppController.php';

class DashboardController extends AppController
{
        public function index()
        {
                $this->render('dashboard');
        }
}

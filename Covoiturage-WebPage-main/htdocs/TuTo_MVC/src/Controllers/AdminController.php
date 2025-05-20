<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Logger;
use App\Models\Database;

class AdminController extends Controller
{
    private $logger;
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $this->render('controlpanel');
    }
}

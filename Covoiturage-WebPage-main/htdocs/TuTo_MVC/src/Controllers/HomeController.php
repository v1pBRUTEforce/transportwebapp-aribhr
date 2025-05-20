<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Logger;
use App\Models\Database;

class HomeController extends Controller
{
    private $logger;
    private $db;

    public function __construct(Logger $logger, Database $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    public function index()
    {
        $sql = "SELECT * FROM trajets";
        $results = $this->db->query($sql);
        $trajetsArray = $results->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('index' , [
            'trajets' => $trajetsArray,
        ]);

        
    }
}

<?php

namespace App\Models;

class Logger
{
    public function log(string $message): void
    {
        echo "LOG: $message\n";
    }
}
<?php

namespace App\Http\Controllers;
use App\Support\Message;
abstract class Controller
{

    /**
     * @var Message|null
     */
    protected $message;

    public function __construct()
    {
        $this->message = new Message();
    }
}

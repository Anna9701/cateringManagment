<?php

class ErrorsController extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        $this->tag->setTitle('Oops!');
    }

    public function show404Action()
    {
    }
    
    public function show401Action()
    {
    }
    
    public function show500Action()
    {
    }
}

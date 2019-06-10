<?php

namespace common\services;


interface EmailServiceInterface
{
    public function send($to, $subject, $views, $data);
}
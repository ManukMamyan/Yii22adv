<?php

namespace common\services;

use yii\base\Component;

class EmailService extends Component
{
    /**
     * @param string $to
     * @param string $subject
     * @param [] $views
     * @param [] $data
     */
    public function send($to, $subject, $views, $data)
    {
        \Yii::$app
            ->mailer
            ->compose($views, $data)
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }
}
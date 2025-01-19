<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        throw new HttpException(403, 'Forbidden');
    }
    
}

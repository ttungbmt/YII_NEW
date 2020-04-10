<?php
namespace drought\controllers;

class SiteController extends AppController
{
    public $layout = '@app/views/layouts/home';

    public function actionIndex(){
        return $this->render('index');
    }
}
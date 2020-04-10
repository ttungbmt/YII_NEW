<?php
namespace drought\controllers;

use common\controllers\BackendController;

class AppController extends BackendController {
    public function actionV1(){
        return $this->render('v1');
    }
}
<?php
namespace drought\controllers\admin;

use drought\controllers\AppController;


class SiteController extends AppController
{
    public function actionIndex()
    {
        $a = [1,2,3];
        return $this->render('index');
    }

    public function actionChangelog()
    {
        return $this->render('changelog');
    }

    public function actionTest()
    {
        return $this->render('test');
    }

    public function actionContact()
    {
        return $this->render('contact');
    }
}
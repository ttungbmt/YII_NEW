<?php
/**
 * Created by PhpStorm.
 * User: THANHTUNG
 * Date: 27-Dec-17
 * Time: 10:44 AM
 */

namespace common\modules\auth\controllers;

use common\controllers\BackendController;

class AuthController extends BackendController
{
    protected function access(){
        return [
            'admin' => ['index']
        ];
    }
}
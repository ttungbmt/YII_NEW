<?php
namespace common\components;
use yii\base\Component;
use yii\web\UnauthorizedHttpException;

class Access extends Component {
    public function beforeUpdate($sender){
       $this->checkGuest();
    }

    public function beforeDelete($sender){
        $this->checkGuest();
    }

    protected function checkGuest(){
        if(role('guest')){
            throw new UnauthorizedHttpException('Bạn không đủ quyền truy cập');
        };
    }
}
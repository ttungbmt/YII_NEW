<?php
namespace common\modules\notifications\controllers;

use Carbon\Carbon;
use common\controllers\BackendController;
use common\models\User;
use common\modules\notifications\forms\FilterForm;
use common\modules\notifications\models\Notification;
use common\notifications\UserNoty;
use yii\db\Expression;

/**
 * Default controller for the `notifications` module
 */
class DefaultController extends BackendController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FilterForm();
        $dataProvider = $searchModel->search(app('request.queryParams'));

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    public function actionRead($id){
        $model = Notification::findOne($id);
        $model->markAsRead();
        return $this->asJson(['status' => 'OK', 'datetime' => $model->readAtDiffText]);
    }

    public function actionReadAll(){
        Notification::updateAll(['read_at' => new Expression('NOW()')],
            'notifiable_id = :notifiable_id', ['notifiable_id' => user()->id]
        );

        return $this->goBack();
    }

    public function actionRemove($id){
        Notification::deleteAll('id = :id', ['id' => new Expression($id)]);
    }

    public function actionRemoveAll(){
        $ids = collect(request('ids', [1,2]))->implode(',');
        Notification::deleteAll('id IN (:ids)', ['ids' => new Expression($ids)]);
    }
}

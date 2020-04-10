<?php
namespace drought\controllers\admin;

use drought\controllers\AppController;
use drought\forms\FileManagerForm;
use drought\models\Gallery;
use Yii;
use yii\web\UploadedFile;

class FileManagerController extends AppController
{
    public function actionIndex()
    {
        $model = new FileManagerForm();
//        $model = FileManagerForm::findOne(3);

        if (Yii::$app->request->isPost) {
            $model->load($_POST);
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->upload()) {
                $model->save();
            }
        }

        return $this->render('index', compact('model'));
    }

    public function actionView($id)
    {
        $model = Gallery::findOrFail($id);
        return $this->render('view', compact('model'));
    }
}
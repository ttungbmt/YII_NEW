<?php
namespace drought\controllers\admin;

use drought\controllers\AppController;
use drought\models\Gallery;
use drought\models\search\GallerySearch;
use Illuminate\Support\Str;
use ttungbmt\actions\CreateAction;
use ttungbmt\actions\DeleteAction;
use ttungbmt\actions\IndexAction;
use ttungbmt\actions\UpdateAction;
use ttungbmt\actions\ViewAction;
use ttungbmt\gdal\Gdal;
use ttungbmt\REST\Geoserver\Yii2\Facade\Geoserver;
use ttungbmt\support\facades\Http;

class GalleryController extends AppController
{
    protected $modelClass = 'drought\models\Gallery';

    public function actions() {
        $model = $this->modelClass;

        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => GallerySearch::class,
                'requestParams' => ['type' => 1]
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $model,
                'modal' => [
                    'title' => 'Xem chi tiết {id}',
                    'footer' => '{close}'
                ]
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $model,
                'scenario' => $model::SCENARIO_UPLOAD,
                'redirectUrl' => ['update-meta', 'id' => ':primaryKey'],
                'handler' => 'saveImg'
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $model,
                'scenario' => $model::SCENARIO_UPLOAD,
                'redirectUrl' => ['update-meta', 'id' => ':primaryKey'],
                'handler' => 'saveImg'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $model,
                'handler' => 'deleteAllRelated',
            ]
        ];
    }

    public function actionUpdateMeta($id){
        $model = (new $this->modelClass)->findOne($id);

        $gdal = new Gdal();
        $file = $model->getUploadPath();

        if(file_exists($file)){
            $model->metadata = json_decode($gdal->gdalinfo($file, ['-json'])->run(), true);
            $model->save();
        }

        return $this->redirect(['index']);
    }

}

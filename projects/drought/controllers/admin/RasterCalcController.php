<?php
namespace drought\controllers\admin;

use drought\controllers\AppController;
use drought\models\Gallery;
use drought\models\search\GallerySearch;
use OneOffTech\GeoServer\Auth\Authentication;
use OneOffTech\GeoServer\GeoServer;
use ttungbmt\actions\CreateAction;
use ttungbmt\actions\DeleteAction;
use ttungbmt\actions\IndexAction;
use ttungbmt\actions\UpdateAction;
use ttungbmt\actions\ViewAction;

class RasterCalcController extends AppController
{
    protected $modelClass = 'drought\models\Gallery';

    public function actions() {
        $model = $this->modelClass;

        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => GallerySearch::class,
                'requestParams' => ['type' => 2]
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
                'scenario' => $model::SCENARIO_CALC,
                'handler' => [$model, 'saveRasterCalc']
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $model,
                'scenario' => $model::SCENARIO_CALC,
                'handler' => [$model, 'saveRasterCalc']
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $model,
                'handler' => 'deleteAllRelated'
            ]
        ];
    }
}

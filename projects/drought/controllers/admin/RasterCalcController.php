<?php

namespace drought\controllers\admin;

use drought\controllers\AppController;
use drought\models\Gallery;
use drought\models\search\GallerySearch;
use SimpleXMLElement;
use ttungbmt\actions\CreateAction;
use ttungbmt\actions\DeleteAction;
use ttungbmt\actions\IndexAction;
use ttungbmt\actions\UpdateAction;
use ttungbmt\actions\ViewAction;
use ttungbmt\support\facades\Http;


class RasterCalcController extends AppController
{
    protected $modelClass = 'drought\models\Gallery';

    public function actions()
    {
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
                'handler' => 'saveRasterCalc'
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $model,
                'scenario' => $model::SCENARIO_CALC,
                'handler' => 'saveRasterCalc'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $model,
                'handler' => 'deleteAllRelatedCalc'
            ]
        ];
    }

    public function actionSaveClasses($id){
        $classes = request()->input('classes');
        if(empty($classes)) return $this->redirect(['admin/raster-calc/update',  'id' => $id]);

        $model = Gallery::findOne($id);
        $name = request()->input('name');
        $model->symbology = $classes;
        $model->save();

        $sld = $this->renderPartial('style_sld', compact('classes', 'name'));

        try {
            $client = function (){ return Http::withBasicAuth('admin', 'geoserver');};
            $url_delete = 'http://localhost:8080/geoserver/rest/workspaces/drought/styles/'.$name.'?purge=true';
            $url_create = 'http://localhost:8080/geoserver/rest/workspaces/drought/styles';
            $url_update_style_layer = 'http://localhost:8080/geoserver/rest/layers/drought:'.$name;

            $response0 = $client()
                ->contentType('application/xml')
                ->send('PUT', $url_update_style_layer, ['body' => '<layer><defaultStyle><name>generic</name></defaultStyle></layer>']);
            $response1 = $client()->send('DELETE', $url_delete);
            $response2 = $client()->contentType('application/vnd.ogc.sld+xml')->send('POST', $url_create, ['body' => $sld]);
            $response3 = $client()
                ->contentType('application/xml')
                ->send('PUT', $url_update_style_layer, ['body' => '<layer><defaultStyle><name>'.$name.'</name></defaultStyle></layer>']);
        } catch (\Exception $e){
            dd($e);
        }


        return $this->redirect(['admin/raster-calc/update',  'id' => $id]);
    }
}

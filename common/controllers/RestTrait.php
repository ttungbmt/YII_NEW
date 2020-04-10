<?php
namespace common\controllers;
use yii\helpers\Html;

trait RestTrait
{
    protected $modelClass = '';

    protected function restValidate(&$model){
        if($model->load(request()->post())){
            if(in_array('geom', $model->attributes())){
                $model->geom = data_get(json_decode(data_get($model, 'geoJSONTxt')), 'coordinates');
            }
            if($model->validate()) return true;
        }
        return false;
    }

    protected function restSave(&$model){
        $model->save();
    }


    protected function renderRest($v = null, $params = []){

        $passes = false; $primaryKey = null;
        $params = collect($params);
        $action = app('controller.action.id');
        list ($view, $model) = [data_get($v, '0'), data_get($v, 'model')];
        list ($id, $title) = [$params->get('id'), $params->get('title')];
        $btnClose = Html::button('Đóng', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);

        if($model){
            $passes = $this->restValidate($model);
            $primaryKey = head($model->primaryKey());
            $redirect = ['view', 'id' => $model->{$primaryKey}];
        }


        if(request()->isAjax){
            $data = collect(['title' => $title]);

            if(in_array($action, ['create', 'update', 'view'])){
                $data = $data->put('content', $this->renderAjax($view, ['model' => $model]));
            }

            switch ($action){
                case 'create':
                    if($passes){
                        $this->restSave($model);

                        $footer = $btnClose.Html::a('Tiếp tục thêm mới', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);

                        $data = $data->merge([
                            'forceReload' => '#crud-datatable-pjax',
                            'content' => '<span class="text-success">Đã tạo thành công</span>',
                        ]);

                    } else {
                        $footer = $btnClose.Html::button('Lưu',['class'=>'btn btn-primary','type'=>"submit"]);
                    }

                    $data = $data->merge(['footer' => $footer]);
                    break;
                case 'update':
                    if($passes){
                        $this->restSave($model);
                        $footer = $btnClose.Html::a('Chỉnh sửa', ['update', 'id' => $model->{$primaryKey}], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);
                        // Fix ---
                        $data = $data->merge([
                            'forceReload' => '#crud-datatable-pjax',
                            'content' => $this->renderAjax($redirect[0], ['model' => $model])
                        ]);

                    } else {
                        $footer = $btnClose.Html::button('Cập nhật',['class'=>'btn btn-primary','type'=>"submit"]);
                    }

                    $data = $data->merge(['footer' => $footer]);
                    break;
                case 'view':
                    $data = $data->merge(['footer' => $footer = $btnClose.Html::a('Chỉnh sửa', ['update', 'id' => $model->{$primaryKey}], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])]);
                    break;
                case 'delete':
                    $data = $data
                        ->merge([
                            'forceReload' => '#crud-datatable-pjax',
                            'forceClose' => true
                        ]);
                    break;
                default:
                    break;
            }

            $data = $data->merge($params);
            return $this->renderJson($data->all());
        }

        if(request()->isPost && $passes && in_array($action, ['update', 'create'])){
            $this->restSave($model);
            $url[] = 'index';
            if($params->get('goBack')){
                $url['page'] = request('page', 1);
            }
            return $this->redirect($url);
        }



        if($action == 'delete'){
            return  $this->redirect(['index']);
        }

        $restData = collect(['title' => $title])->merge(array_slice($v, 1));
        return $this->render($view, $restData->all());

    }


    public function renderCrud($view = [], $params = [])
    {
        $request = app('request');
        $view = collect($view);
        $action = app('controller.action.id');

        if ($request->isAjax) {
            return $this->ajaxCRUD($view, $params);
        }

        if ($request->isPost && in_array($action, ['update'])) {
            return $this->redirect(['view', 'id' => $view->get('model')->id]);
        }

        if ($action == 'delete') {
            return $this->redirect(['index']);
        }

        return $this->render($view->get(0), $view->slice(1)->all());
    }

//    protected function ajaxCRUD(Collection $view, $params)
//    {
//        app('response')->format = Response::FORMAT_JSON;
//        $action = $this->getActionCrud($view); // Return view nếu method là actionUpdate
//
//        $model = $view->get('model');
//        $btnClose = Html::button('Đóng', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
//
//        $id = $model ? $model->id : null;
//        $modal = [];
//
//
//        switch ($action) {
//            case 'create':
//                $modal['title'] = "Thêm mới " . \Yii::t('pcd', 'Dm Benh') . " #" . $id;
//
//                if (request()->isGet || $model->hasErrors()) {
//                    $modal['footer'] = (
//                        $btnClose .
//                        Html::button('Lưu', ['class' => 'btn btn-primary', 'type' => "submit"])
//                    );
//                } else {
//                    $modal['content'] = '<span class="text-success">Thêm mới thành công</span>';
//                    $modal['footer'] = (
//                        $btnClose .
//                        Html::a('Thêm tiếp', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
//                    );
//                }
//
//                break;
//            case 'update':
//                $modal['title'] = "Chỉnh sửa " . \Yii::t('pcd', 'Dm Benh') . " #" . $id;
//                $modal['footer'] = (
//                    $btnClose .
//                    Html::button('Lưu', ['class' => 'btn btn-primary', 'type' => "submit"])
//                );
//                $action = 'view';
//                break;
//            case 'view':
//                $modal['title'] = Yii::t('pcd', 'Dm Benh') . " #" . $id;
//                $modal['footer'] = (
//                    $btnClose .
//                    Html::a('Chỉnh sửa', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
//                );
//                break;
//            case 'delete':
//                $modal['forceClose'] = true;
//                return $params + $modal;
//                break;
//            default:
//                break;
//        }
//
//
//        $this->forceReload($action, $modal);
//        return $params + $modal + [
//                'content' => $this->renderAjax($view->get(0), $view->slice(1)->all())
//            ];
//    }

//    protected function forceReload($action, &$modal){
//        $isDeleted = ($action == 'delete');
//        $isCreated = request()->isPost && $action == 'create';
//        $isUpdated = request()->isPost && $action == 'view';
//
//        if($isDeleted || $isCreated || $isUpdated){
//            $modal['forceReload'] = '#crud-datatable-pjax';
//        }
//    }
//
//    protected function getActionCrud(Collection $view)
//    {
//        $action = app('controller.action.id');
//        return (in_array($action, ['update']) && $view->get(0) == 'view') ? 'view' : $action;
//    }
}
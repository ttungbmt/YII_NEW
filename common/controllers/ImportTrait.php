<?php
namespace common\controllers;
use common\forms\ImportForm;
use common\forms\UploadForm;
use common\libs\excel\Excel;
use gsnc\models\DmMaunc;
use kartik\helpers\Html;
use yii\base\Model;
use yii\data\ArrayDataProvider;

trait ImportTrait
{
    protected $data = [];
    protected $errors = [];


    protected function opt($name, $default = null){
        return data_get($this->options(), $name, $default);
    }

    protected function data2Model($data){
        return collect($data)->map(function ($i){

            $model = new $this->modelImportClass(array_keys($i));
            $this->rules($model);
//            $model->scenario =  ImportForm::SCENARIO;
            $model->setAttributes($i);
            return $model;
        });
    }

    protected function prepareData()
    {
       // Nạp dữ liệu vào đây, k nạp vào rules, để tránh vòng lặp
    }

    protected function rules(&$model){

    }

    protected function extraHeader()
    {
        return [];
    }

    protected function data_set($name, $value){
        $this->data[$name] = $value;

        return $this->data[$name];
    }

    protected function data_get($name, $default = null){
        return data_get($this->data, $name, $default);
    }

    protected function setAttrs(&$data, $fields, $value)
    {
        $d = $data;
        foreach ($fields as $k => $f) {
            foreach ($value as $i => $v) {
                if (trim($data[$f]) == $i) {
                    $d[$f] = $v;
                }
            }
        }
        $data = $d;
    }

    public function actionIndex()
    {
        $model = new UploadForm();
        $excel = new Excel();
        if ($model->load(request()->post())) {
            if ($model->validateForm()) {
                $fileName = $model->file->tempName;
                $type = request('type');
                $this->prepareData();

                $startDataRow = $this->opt('startDataRow', 4);
                $exHeader = collect($excel->readSheetHeader($fileName, $startDataRow))->filter()->values();
                $header = collect($this->opt('header'))->merge($this->extraHeader());
                $exData = collect($excel->import($fileName, $startDataRow));

                # Validate header
                $diff = $exHeader->diff($header);

                if ($diff->isNotEmpty()) {
                    $this->data['messages'] = "Excel k đúng định dạng. Không tồn tại các cột: <b>" . $diff->implode(',') . "</b>. Vui lòng kiểm tra mã các cột";
                    return $this->renderJson(['html' => $this->renderAjax('@common/themes/admin/admin/import/_errors', $this->data)]);
                }
                # Validate data
                $this->data['models'] = $this->data2Model($exData);

                $this->data_set('page', request()->post('page', 0));
                $this->data['dataProvider'] = new ArrayDataProvider([
                    'allModels'  => $this->data['models']->all(),
                    'pagination' => ($type == 'save-all') ? false : [
                        'pageSize' => $this->opt('pagination.pageSize', 20),
                        'page'     => $this->data['page']
                    ]
                ]);
                $passes = Model::validateMultiple($this->data['models']);

                switch ($type) {
                    case 'preview':
                        $pages = collect(explode(',', request()->post('pages', null)))->filter(function ($item) {
                            return $item !== '';
                        })->all();

                        if (in_array($this->data['page'], $pages)) {
                            $this->data_set('message', 'Dữ liệu đã được nhập');
                            $this->data_set('hideBtnImport', true);
                        }
                        break;
                    case 'save':
                    case 'save-all':
                        $models = ($type == 'save') ? $this->data['dataProvider']->getModels() : $this->data['models'];
                        if ($passes && $this->validateModels($models)) {
                            $count = count($models);
                            $this->data['message'] = 'Đã lưu thành công ' . Html::a($count . ' đối tượng', '#', ['class' => 'alert-link']);
                        } else {
                            return $this->renderJson(['html' => $this->renderAjax('@common/themes/admin/admin/import/_errors', $this->data)]);
                        }
                        break;
                }

                return $this->renderJson([
                    'html' => $this->renderAjax('_preview', $this->data)
                ]);
            }

            $this->data['models'] = [$model];
            return $this->renderJson(['html' => $this->renderAjax('@common/themes/admin/admin/import/_errors', $this->data)]);
        }

        $this->data_set('sample', $this->opt('sample'));
        return $this->render('index', array_merge(['model' => $model, 'errors' => $this->errors], $this->data));
    }


}
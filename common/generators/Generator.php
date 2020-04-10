<?php
namespace common\generators;

use Yii;
use yii\gii\CodeFile;

class Generator extends \johnitvn\ajaxcrud\generators\Generator
{
    public $template = 'crud';

    public $title;

    public $baseControllerClass = 'common\controllers\MyController';

    public $enableMap = false;

    public $enableHc = false;

    public $enableAjax = true;

    public $quan;

    public  $phuong;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['enableMap', 'enableHc', 'enableAjax'], 'boolean'],
            [['title', 'quan', 'phuong'], 'string']
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::rules(), [
            'enableMap' => 'Enable Map',
        ]);
    }

    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');
        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }

        return $files;
    }

    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/themes/admin/admin/' . $this->getControllerID());
        } else {
            return Yii::getAlias($this->viewPath);
        }
    }
}
<?php
namespace common\supports\grid;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \kartik\grid\ActionColumn
{
    public $width = '100px';



    public $deleteOptions = [
        'role' => 'modal-remote',
        'title' => 'Xóa',
        'data-confirm' => false,
        'data-method' => false,
        'data-request-method' => 'post',
        'data-toggle' => 'tooltip',
        'data-confirm-title' => 'Bạn có chắc chắn?',
        'data-confirm-message' => 'Bạn có muốn xóa đối tượng này không'
    ];

    protected function initDefaultButtons()
    {
        $this->setDefaultButton('view', Yii::t('kvgrid', 'View'), 'eye');
        $this->setDefaultButton('update', Yii::t('kvgrid', 'Update'), 'pencil7');
        $this->setDefaultButton('delete', Yii::t('kvgrid', 'Delete'), 'trash');
    }

    public function renderDataCell($model, $key, $index)
    {
        $options = $this->fetchContentOptions($model, $key, $index);
        $content = Html::tag('div', $this->renderDataCellContent($model, $key, $index), ['class' => 'list-icons']);
        return Html::tag('td', $content, $options);
    }

    protected function setDefaultButton($name, $title, $icon)
    {
        if (isset($this->buttons[$name])) {
            return;
        }
        $this->buttons[$name] = function ($url) use ($name, $title, $icon) {
            $opts = "{$name}Options";
            $options = ['title' => $title, 'aria-label' => $title, 'data-pjax' => '0'];
            if ($name === 'delete') {
                $item = isset($this->grid->itemLabelSingle) ? $this->grid->itemLabelSingle : Yii::t('kvgrid', 'item');
                $options['data-method'] = 'post';
                $options['data-confirm'] = Yii::t('kvgrid', 'Are you sure to delete this {item}?', ['item' => $item]);
            }
            $options = array_replace_recursive($options, $this->buttonOptions, $this->$opts);
            $label = $this->renderLabel($options, $title, ['class' => "icon-{$icon}"]);
            $link = Html::a($label, $url, $options);
            if ($this->_isDropdown) {
                $options['tabindex'] = '-1';
                return "<li>{$link}</li>\n";
            } else {
                return $link;
            }
        };
    }
}
<?php
namespace common\libs\activitylog;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class LogBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeInsert($event)
    {
        activity(Log::CABENH)
            ->performedOn($event->sender)
            ->causedBy(auth()->current())
            ->log('Thêm mới ca bệnh');
    }

    public function afterUpdate($event)
    {
        activity(Log::CABENH)
            ->performedOn($event->sender)
            ->causedBy(auth()->current())
            ->log('Cập nhật ca bệnh');
    }

    public function beforeDelete($event)
    {
        activity(Log::CABENH)
            ->performedOn($event->sender)
            ->causedBy(auth()->current())
            ->log('Xóa ca bệnh');
    }
}
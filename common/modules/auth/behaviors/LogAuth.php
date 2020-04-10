<?php
namespace common\modules\auth\behaviors;

use common\libs\activitylog\Log;
use ttungbmt\noty\Noty;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class LogAuth extends Behavior
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
        activity(Log::USER)
            ->causedBy(user()->identity)
            ->log('Thêm mới tài khoản người dùng');
    }

    public function afterUpdate($event)
    {
        activity(Log::USER)
            ->causedBy(user()->identity)
            ->log('Cập nhật tài khoản người dùng');

        session()->setFlash(Noty::TYPE_SUCCESS, 'Cập nhật thành công');
    }

    public function beforeDelete($event)
    {
        activity(Log::USER)
            ->causedBy(user()->identity)
            ->log('Xóa tài khoản người dùng');
    }
}
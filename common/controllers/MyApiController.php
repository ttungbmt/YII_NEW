<?php
/**
 * Created by PhpStorm.
 * User: THANHTUNG
 * Date: 10-Dec-17
 * Time: 9:14 AM
 */

namespace common\controllers;

use Carbon\Carbon;
use common\models\User;
use yii\db\Query;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class MyApiController extends Controller
{
    protected $request;
    public $dataMap = [];

    public function init()
    {
        parent::init();
        $this->request = \Yii::$app->request;

    }

    public function behaviors()
    {
        $behavior = [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];

        return array_merge(parent::behaviors(), $behavior);
    }

    public function actionActivity(){

        $activity = (new Query())
            ->select('causer_id, MAX(created_at) AS time_login')
            ->from('activity_log')
            ->where(['description' => 'Đăng nhập hệ thống'])
            ->groupBy(['activity_log.causer_id']);

        $activity_log = (new Query())
            ->select('activity.*, u.username')
            ->from(['activity' => $activity])
            ->leftJoin(['u' => 'user'], 'u.id  = activity.causer_id')
            ->orderBy('activity.time_login DESC')
            ->limit('10')
            ->all()
        ;

        $activity_log = collect($activity_log)->map(function ($item){
           return [
               'causer_id' => $item['causer_id'],
               'username' => $item['username'] ? $item['username'] : 'Unknown',
               'recent_time' => $item['time_login'] ?  (new Carbon($item['time_login']))->diffForHumans() : '',
           ];
        })->all();

        return $this->renderPartial('@app/views/api/thongke/_activity', [
            'activity_log' => $activity_log,
        ]);
    }

    protected function setAuthData(){
        $userId = user()->id;
        $user = User::findOne($userId);
        if ($user) {
            $this->dataMap['user'] = array_merge($user->info->toArray(), ['username' => $user->username]);
            $this->dataMap['roles'] = array_keys(auth()->getRolesByUser($userId));
            $this->dataMap['permissions'] = array_keys(auth()->getPermissionsByUser($userId));
        }
    }

    protected function setAppData(){
        $this->dataMap['app'] = [
            'title' => params('app_name'),
            'logo_url' => params('assets.logo')
        ];

        $this->dataMap['nav_links'] = api('nav_links');
    }

}
<?php
namespace common\libs\activitylog;


use common\libs\activitylog\models\Activity;
use pcd\services\DebugService;
use yii\base\Model;

class ActivityLogger
{
    /** @var \Illuminate\Auth\AuthManager */
    protected $auth;

    protected $logName = '';

    /** @var bool */
    protected $logEnabled;

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $performedOn;

    /** @var \Illuminate\Database\Eloquent\Model */

    protected $causedBy;

    /** @var \Illuminate\Support\Collection */
    protected $properties;

    public function __construct(
//        AuthManager $auth, Repository $config
    )
    {
//        $this->auth = $auth;
        $this->properties = collect();
//        $authDriver = $config['laravel-activitylog']['default_auth_driver'] ?? $auth->getDefaultDriver();
//        $this->causedBy = $auth->guard($authDriver)->user();

        $this->logName = 'default';
        $this->logEnabled = true;
    }

    public function performedOn(Model $model)
    {
        $this->performedOn = $model;
        return $this;
    }

    public function on(Model $model)
    {
        return $this->performedOn($model);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int|string $modelOrId
     *
     * @return $this
     */
    public function causedBy($modelOrId)
    {
        $this->causedBy = $modelOrId;

        return $this;
    }

    public function by($modelOrId)
    {
        return $this->causedBy($modelOrId);
    }

    /**
     * @param array|\Illuminate\Support\Collection $properties
     *
     * @return $this
     */
    public function withProperties($properties)
    {
        $this->properties = collect($properties);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function withProperty(string $key, string $value)
    {
        $this->properties->put($key, $value);

        return $this;
    }

    public function useLog(string $logName)
    {
        $this->logName = $logName;

        return $this;
    }

    public function inLog(string $logName)
    {
        return $this->useLog($logName);
    }

    public function log(string $description)
    {

        if (!$this->logEnabled) {
            return;
        }
        $activity = new Activity();

        if ($this->performedOn) {
            $model = $this->performedOn;
            $activity->subject_id = $model->getPrimaryKey();
            $activity->subject_type= get_class($model);
        }
        if ($this->causedBy) {
            $user = $this->causedBy;
            $activity->causer_id = $user->getPrimaryKey();
            $activity->causer_type= get_class($user);
        }
        $activity->properties = $this->properties;
        $activity->description = $this->replacePlaceholders($description, $activity);
        $activity->log_name = $this->logName;
        $activity->save();

        return $activity;
    }

    protected function replacePlaceholders(string $description, Activity $activity): string
    {
        return preg_replace_callback('/:[a-z0-9._-]+/i', function ($match) use ($activity) {
            $match = $match[0];
            $attribute = (string)string($match)->between(':', '.');
            if (!in_array($attribute, ['subject', 'causer', 'properties'])) {
                return $match;
            }
            $propertyName = substr($match, strpos($match, '.') + 1);
            $attributeValue = $activity->$attribute;
            if (is_null($attributeValue)) {
                return $match;
            }
            $attributeValue = $attributeValue->toArray();
            return array_get($attributeValue, $propertyName, $match);
        }, $description);
    }
}
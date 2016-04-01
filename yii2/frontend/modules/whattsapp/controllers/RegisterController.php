<?php

namespace api\modules\whattsapp\controllers;

use frontend\controllers\BaseController;
use common\models\DeviceWhattsApp;

class RegisterController extends BaseController {

    public $modelClass = 'DeviceWhattsapp';
    
    public function actions() {
        return array_merge(parent::actions(), [
            'index' => [
		'class' => 'api\modules\whattsapp\views\register\IndexAction',
		'modelClass' => $this->modelClass,
            ],
            'update' => [
		'class' => 'api\modules\whattsapp\views\register\UpdateAction',
		'modelClass' => $this->modelClass,
            ],
            'validate' => [
		'class' => 'api\modules\whattsapp\views\register\ValidateAction',
		'modelClass' => $this->modelClass,
            ],
            'sync' => [
		'class' => 'api\modules\whattsapp\views\register\SyncAction',
		'modelClass' => $this->modelClass,
            ],
        ]);
    }
}

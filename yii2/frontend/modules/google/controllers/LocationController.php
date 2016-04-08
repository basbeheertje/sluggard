<?php

namespace frontend\modules\google\controllers;

use frontend\controllers\BaseController;
use common\models\GoogleUser;

class LocationController extends BaseController {

    public $modelClass = 'GoogleUser';
    
    public function actions() {
        return array_merge(parent::actions(), [
            'index' => [
		'class' => 'api\modules\google\views\register\IndexAction',
		'modelClass' => $this->modelClass,
            ],
            'update' => [
		'class' => 'api\modules\google\views\register\UpdateAction',
		'modelClass' => $this->modelClass,
            ],
            'callback' => [
		'class' => 'api\modules\google\views\register\CallbackAction',
		'modelClass' => $this->modelClass,
            ],
        ]);
    }
}

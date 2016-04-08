<?php
namespace frontend\controllers;

use Yii;
use frontend\controllers\BaseController;
use common\models\User;

/**
 * User controller
 * @author Bas van Beers
 * @copyright (c) 2016, Bas van Beers
 */
class UserController extends BaseController{
    
    public $defaultAction = 'index';
    public $modelClass = 'User';
    
    public function init(){
        parent::init();
        \Yii::$app->params['sidebaritems'] = [
            [
                'label' => \Yii::t('app', 'profile'),
                'url'   => '/user/profile',
            ],
            [
                'label' => \Yii::t('app', 'google'),
                'url'   => '/user/google',
            ],
            [
                'label' => \Yii::t('app', 'google'),
                'url'   => '/user/google',
            ],
            [
                'label' => \Yii::t('app', 'google'),
                'url'   => '/user/google',
            ],
        ];
    }
    
    public function actionIndex(){
        
        $model = User::findOne(Yii::$app->user->identity->id);
        
        return $this->render('index', [
            'model' => $model,
        ]);
        
    }
    
}
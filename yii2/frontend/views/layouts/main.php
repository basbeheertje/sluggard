<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
//use yii\bootstrap\Nav;
use macgyer\yii2materializecss\widgets\Nav;
use macgyer\yii2materializecss\widgets\NavBar;
//use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\views\layouts\header;
use frontend\widgets\SideBarWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php
            NavBar::begin([
                'brandLabel' => Html::img('@web/images/header-logo.png', ['alt'=>\Yii::$app->params['applicationname']]),//\Yii::$app->params['applicationname'],
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top dark-primary-color',
                ],
            ]);
            $menuItems = [
                ['label' => \Yii::t('app', 'Home'), 'url' => ['/site/index']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => \Yii::t('app', 'Login'), 'url' => ['/site/login']];
            } else {
                //$menuItems[] = ['label' => \Yii::t('app', 'IMDB'), 'url' => ['/imdb/index/index']];
                $menuItems[] = ['label' => \Yii::t('app', 'Contacts'), 'url' => ['/contact/index']];
                $menuItems[] = ['label' => \Yii::t('app', 'Domotica'), 'url' => ['/domotica/index']];
                $menuItems[] = ['label' => \Yii::t('app', 'Movies'), 'url' => ['/movie/index']];
                $menuItems[] = ['label' => \Yii::t('app', 'Music'), 'url' => ['/music/index']];
                $menuItems[] = ['label' => \Yii::t('app', 'Profile'), 'url' => ['/user/index']];
                $menuItems[] = ['label' => \Yii::t('app', 'Logout'), 'url' => ['/site/logout']];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-right dark-primary-color'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>
        <div style="margin-top:64px;display:inline-bock;float:left;width:100%;">
            &nbsp;
        </div>
        <?php 

            if(isset(\Yii::$app->params['sidebaritems'])){
        
                $sidebaritems = \Yii::$app->params['sidebaritems'];

                echo SideBarWidget::widget(
                    [
                        'items' => isset($sidebaritems) ? $sidebaritems : [],
                    ]
                );
            
            }

        ?>
        <?= $content ?>
        <div class="row">
            <div class="col s12 m12">
                <?= Alert::widget() ?>
            </div>
        </div>
        <!--<footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; <?= \Yii::$app->params['vendor']; ?> <?= date('Y') ?></p>
                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>-->
        <?php
        
            yii\bootstrap\Modal::begin([
                'headerOptions' => ['id' => 'modalHeader'],
                'id' => 'modal',
                'size' => 'modal-lg',
                //keeps from closing modal with esc key or by clicking out of the modal.
                // user must click cancel or X to close
                //'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
                /*'clientOptions' => [
                    //'backdrop' => 'static', 
                    'keyboard' => true                    
                ],
                'closeButton' => [
                    'tag' => 'a',
                    'lable' => Yii::t('app','Close')
                ],*/
            ]);
            echo "<div id='modalContent'></div>";
            yii\bootstrap\Modal::end();
            
        ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
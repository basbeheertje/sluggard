<?php

    use frontend\widgets\IntroWidget;
    
    /* @var $this yii\web\View */
    $this->title = \Yii::$app->params['applicationname'];

?>
<?php
    
    echo IntroWidget::widget(
        [
            'title' => \Yii::t('app', 'This is') . ' ' . \Yii::$app->params['applicationname'],
            'text'=> \Yii::$app->params['applicationname'] . ' ' . \Yii::t('app', 'is created by') . ' ' . \Yii::$app->params['author']
         ]
    );
            
 ?>
<div class="container">
    <div class="col-lg-6 card accent-color ">
        <div class="card-content white-text">
            <span class="card-title">
                <?php echo \Yii::t('app', 'Lots of integrations'); ?>
            </span>
            <p>
                <?php echo \Yii::t('app', 'There are many integrations possible.'); ?>
            </p>
        </div>
    </div>
</div>
<?php

    use common\widgets\MaterialIconWidget;
    use frontend\widgets\IntroWidget;

    /* @var $this yii\web\View */
    $this->title = \Yii::$app->params['applicationname'];

?>
<?= IntroWidget::widget(['title' => 'Instellingen','text'=>'Dit is een test']) ?>
<div class="row">
    <div class="col s12 m12">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-github-alt', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </div>
</div>

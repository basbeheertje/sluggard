<?php

    use common\widgets\MaterialIconWidget;
    use frontend\widgets\IntroWidget;

    /* @var $this yii\web\View */
    $this->title = \Yii::$app->params['applicationname'];

?>
<?= IntroWidget::widget(['title' => 'Instellingen','text'=>'Dit is een test']) ?>
<div class="row">
    <a class="col s6 m3" href="/google/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-google', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
    <a class="col s6 m3" href="/device/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-mobile', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
    <a class="col s6 m3" href="/linkedin/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-linkedin', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
    <a class="col s6 m3" href="/facebook/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-facebook', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
</div>
<div class="row">
    <a class="col s6 m3" href="/twitter/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-twitter', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
    <a class="col s6 m3" href="/dropbox/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-dropbox', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
    <div class="col s6 m3" href="/github/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-github-alt', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </div>
    <a class="col s6 m3" href="/owncloud/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-cloud-upload', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
</div>
<div class="row">
    <a class="col s6 m3" href="/directadmin/index">
        <?= MaterialIconWidget::widget(['iconcode' => 'fa fa-server', 'backgroundcolor' => '#448AFF', 'shadow' => 3, 'iconcolor'=>'white']) ?>
    </a>
</div>
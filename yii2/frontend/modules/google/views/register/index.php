<?php

    //use common\widgets\MaterialIconWidget;
    use macgyer\yii2materializecss\widgets\grid\GridView;
    use macgyer\yii2materializecss\widgets\Button;
    use frontend\assets\AppAsset;
    use \yii\data\ArrayDataProvider;
    use yii\helpers\Html;
    use yii\helpers\Url;

?>
<div class="row">
    <div class="col s12 m12">
        <?php
         
            echo Html::button(\Yii::t('app', 'Add'), ['value' => Url::to(['register/create']), 'title' => \Yii::t('app', 'Add'), 'class' => 'showModalButton btn btn-success']);
            
        ?>
    </div>
    <div class="col s12 m12">
        <?php

            $dataProvider = new ArrayDataProvider([
                'allModels' => $GoogleUsers,
                'pagination' => [
                    'pagesize' => 10,
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'email',
                    'name',
                    'created_at:date',
                ],
            ]);

         ?>
    </div>
</div>
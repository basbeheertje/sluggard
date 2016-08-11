<?php

    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use macgyer\yii2materializecss\widgets\Dropdown;
    use yii\helpers\ArrayHelper;

    /** @var ActiveForm $form */
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'create-phoneTypes-form'
        ]
    ]);

?>
<?php
    
    echo $form->field($model, 'name')->textInput();
    
?>
<div class="form-group">
    <?= Html::submitButton('Save', [
        'class' => 'btn btn-primary',
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
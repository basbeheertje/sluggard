<?php

    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

    /** @var ActiveForm $form */
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'create-googleuser-form'
        ]
    ]);

    echo $form->field($model, 'email')->textInput();
    echo $form->field($model, 'password')->passwordInput();
    
?>
<div class="form-group">
    <?= Html::submitButton('Save', [
        'class' => 'btn btn-primary',
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
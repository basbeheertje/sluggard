<?php

    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use macgyer\yii2materializecss\widgets\Dropdown;
    use yii\helpers\ArrayHelper;

    /** @var ActiveForm $form */
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'create-device-form'
        ]
    ]);

?>
<?php
    
    echo $form->field($model, 'name')->textInput();
    echo $form->field($model, 'brand')->textInput();
    echo $form->field($model, 'imei')->textInput();
    echo $form->field($model, 'mac')->textInput();
    echo $form->field($model, 'ip')->textInput();
    echo $form->field($model, 'number')->textInput();
    echo $form->field($model, 'brand')->textInput();
    echo $form->field($model, 'version')->textInput();
    
?>
<?php
    
    $arrayHelper = ArrayHelper::map($device_types,'id','name');
    echo Html::activeDropDownlist($model, 'device_type',$arrayHelper); 
    
?>
<div class="form-group">
    <?= Html::submitButton('Save', [
        'class' => 'btn btn-primary',
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
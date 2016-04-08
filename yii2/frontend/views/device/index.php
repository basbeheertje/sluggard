<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

    foreach($devices as $device){
        
?>
 <div class="row">
    <div class="col s12 m3">
        <div class="card">
            <div class="card-image">
                <img src="/images/<?php echo str_replace(' ','',trim(strtolower($device->deviceType->name))); ?>.png">
                <span class="card-title"><?php echo $device->name; ?></span>
            </div>
            <div class="card-content">
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','Type'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->name; ?></span>
                </div>
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','Imei'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->imei; ?></span>
                </div>
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','Mac Address'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->mac; ?></span>
                </div>
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','IP Address'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->ip; ?></span>
                </div>
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','Number'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->number; ?></span>
                </div>
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','Brand'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->brand; ?></span>
                </div>
                <div class="row">
                    <span class="col s6 m6"><?= \Yii::t('app','Version'); ?></span>
                    <span class="col s6 m6"><?= $device->deviceType->version; ?></span>
                </div>
            </div>
            <div class="card-action">
                <a href="/device/remove?id=<?php echo $GoogleUser->id; ?>"><?php echo \Yii::t('app','Remove'); ?></a>
            </div>
        </div>
    </div>
</div>
<?php
        
    }

?>
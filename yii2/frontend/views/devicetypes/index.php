<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

    foreach($devicetypes as $devicetype){
        
?>
 <div class="row">
    <div class="col s12 m3">
        <div class="card">
            <div class="card-image">
                <img src="/images/<?php echo str_replace(' ','',trim(strtolower($devicetype->name))); ?>.png">
                <span class="card-title"><?php echo $devicetype->name; ?></span>
            </div>
            <div class="card-action">
                <a href="/devicetypes/delete?id=<?php echo $devicetype->id; ?>"><?php echo \Yii::t('app','Remove'); ?></a>
            </div>
        </div>
    </div>
</div>
<?php
        
    }

?>
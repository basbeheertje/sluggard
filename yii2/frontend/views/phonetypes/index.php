<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

    foreach($phonetypes as $phonetype){
        
?>
 <div class="row">
    <div class="col s12 m3">
        <div class="card">
            <div class="card-image">
                <img src="/images/<?php echo str_replace(' ','',trim(strtolower($phonetype->name))); ?>.png">
                <span class="card-title"><?php echo $phonetype->name; ?></span>
            </div>
            <div class="card-action">
                <a href="/phonetypes/delete?id=<?php echo $phonetype->id; ?>"><?php echo \Yii::t('app','Remove'); ?></a>
            </div>
        </div>
    </div>
</div>
<?php
        
    }

?>
<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?>
<div class="row">
    <div class="col m6 s6">
        <?= \Yii::t('app','Location'); ?>
    </div>
    <div class="col m6 s6">
        <?php
    
            echo SwitchButton::widget([
                'name'      => 'location',
                'id'        => 'locationSwitchButton',
                'checked'   => $GoogleUser->location,
                'value'     => 1,
                'offText'   => '',
                'onText'    => '',
            ]);

        ?>
    </div>
</div>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('#locationSwitchButton label .lever').click(function(){

            setTimeout(function(){

                var locationSwitchButton = jQuery('#locationSwitchButton label input[type=checkbox]').is(':checked');
                if(locationSwitchButton === true){
                    locationSwitchButton = 1;
                }else{
                    locationSwitchButton = 0;
                }

                jQuery.ajax({
                    type: "POST",
                    url: "/google/index/update/",
                    data: {
                        'GoogleUser' : {
                            'id': <?php echo $GoogleUser->id; ?>,
                            'location': locationSwitchButton
                        }
                    }
                });
            }, 50);
        });
        
    });
    
</script>
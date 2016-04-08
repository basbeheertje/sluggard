<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?>
<div class="row">
    <div class="col m6 s6">
        <?php echo \Yii::t('app','Calendar'); ?>
    </div>
    <div class="col m6 s6">
        <?php
    
            echo SwitchButton::widget([
                'name'      => 'calendar',
                'id'        => 'calendarSwitchButton',
                'checked'   => $GoogleUser->calendar,
                'value'     => 1,
                'offText'   => '',
                'onText'    => '',
            ]);

        ?>
    </div>
</div>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('#calendarSwitchButton label .lever').click(function(){

            setTimeout(function(){

                var calendarSwitchButton = jQuery('#calendarSwitchButton label input[type=checkbox]').is(':checked');
                if(calendarSwitchButton === true){
                    calendarSwitchButton = 1;
                }else{
                    calendarSwitchButton = 0;
                }

                jQuery.ajax({
                    type: "POST",
                    url: "/google/index/update/",
                    data: {
                        'GoogleUser' : {
                            'id': <?php echo $GoogleUser->id; ?>,
                            'calendar': calendarSwitchButton
                        }
                    }
                });
            }, 50);
        });
        
    });
    
</script>
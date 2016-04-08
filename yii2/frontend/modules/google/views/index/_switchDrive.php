<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?>
<div class="row">
    <div class="col m6 s6">
        Drive
    </div>
    <div class="col m6 s6">
        <?php
    
            echo SwitchButton::widget([
                'name'      => 'drive',
                'id'        => 'driveSwitchButton',
                'checked'   => $GoogleUser->drive,
                'value'     => 1,
                'offText'   => '',
                'onText'    => '',
            ]);

        ?>
    </div>
</div>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('#driveSwitchButton label .lever').click(function(){

            setTimeout(function(){

                var driveSwitchButton = jQuery('#driveSwitchButton label input[type=checkbox]').is(':checked');
                if(driveSwitchButton === true){
                    driveSwitchButton = 1;
                }else{
                    driveSwitchButton = 0;
                }

                jQuery.ajax({
                    type: "POST",
                    url: "/google/index/update/",
                    data: {
                        'GoogleUser' : {
                            'id': <?php echo $GoogleUser->id; ?>,
                            'drive': driveSwitchButton
                        }
                    }
                });
            }, 50);
        });
        
    });
    
</script>
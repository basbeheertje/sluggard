<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?>
<div class="row">
    <div class="col m6 s6">
        Google+
    </div>
    <div class="col m6 s6">
        <?php
    
            echo SwitchButton::widget([
                'name'      => 'plus',
                'id'        => 'plusSwitchButton',
                'checked'   => $GoogleUser->plus,
                'value'     => 1,
                'offText'   => '',
                'onText'    => '',
            ]);

        ?>
    </div>
</div>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('#plusSwitchButton label .lever').click(function(){

            setTimeout(function(){

                var plusSwitchButton = jQuery('#plusSwitchButton label input[type=checkbox]').is(':checked');
                if(plusSwitchButton === true){
                    plusSwitchButton = 1;
                }else{
                    plusSwitchButton = 0;
                }

                jQuery.ajax({
                    type: "POST",
                    url: "/google/index/update/",
                    data: {
                        'GoogleUser' : {
                            'id': <?php echo $GoogleUser->id; ?>,
                            'plus': plusSwitchButton
                        }
                    }
                });
            }, 50);
        });
        
    });
    
</script>
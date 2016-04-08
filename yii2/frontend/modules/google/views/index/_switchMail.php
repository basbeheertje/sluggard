<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?>
<div class="row">
    <div class="col m6 s6">
        Gmail
    </div>
    <div class="col m6 s6">
        <?php
    
            echo SwitchButton::widget([
                'name'      => 'mail',
                'id'        => 'mailSwitchButton',
                'checked'   => $GoogleUser->mail,
                'value'     => 1,
                'offText'   => '',
                'onText'    => '',
            ]);

        ?>
    </div>
</div>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('#mailSwitchButton label .lever').click(function(){

            setTimeout(function(){

                var mailSwitchButton = jQuery('#mailSwitchButton label input[type=checkbox]').is(':checked');
                if(mailSwitchButton === true){
                    mailSwitchButton = 1;
                }else{
                    mailSwitchButton = 0;
                }

                jQuery.ajax({
                    type: "POST",
                    url: "/google/index/update/",
                    data: {
                        'GoogleUser' : {
                            'id': <?php echo $GoogleUser->id; ?>,
                            'mail': mailSwitchButton
                        }
                    }
                });
            }, 50);
        });
        
    });
    
</script>
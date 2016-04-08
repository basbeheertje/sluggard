<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?>
<div class="row">
    <div class="col m6 s6">
        <?php echo \Yii::t('app','Contacts'); ?>
    </div>
    <div class="col m6 s6">
        <?php
    
            echo SwitchButton::widget([
                'name'      => 'contacts',
                'id'        => 'contactsSwitchButton',
                'checked'   => $GoogleUser->contacts,
                'value'     => 1,
                'offText'   => '',
                'onText'    => '',
            ]);

        ?>
    </div>
</div>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('#contactsSwitchButton label .lever').click(function(){

            setTimeout(function(){

                var contactsValue = jQuery('#contactsSwitchButton label input[type=checkbox]').is(':checked');
                if(contactsValue === true){
                    contactsValue = 1;
                }else{
                    contactsValue = 0;
                }

                jQuery.ajax({
                    type: "POST",
                    url: "/google/index/update/",
                    data: {
                        'GoogleUser' : {
                            'id': <?php echo $GoogleUser->id; ?>,
                            'contacts': contactsValue
                        }
                    }
                });
            }, 50);
        });
        
    });
    
</script>
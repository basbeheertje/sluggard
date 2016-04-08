<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;
    use common\components\GoogleAPIHelper;
    use common\components\GoogleCalendarHelper;
    use common\components\GoogleContactsHelper;
    use common\components\GoogleDriveHelper;
    use common\components\GoogleHistoryAPIHelper;
    use common\components\GoogleMailHelper;
    use common\components\GooglePlusHelper;

    foreach($GoogleUsers as $GoogleUser){
        
?>
 <div class="row">
    <div class="col s12 m3">
        <div class="card">
            <div class="card-image">
                <img src="<?php echo $GoogleUser->picture; ?>">
                <span class="card-title"><?php echo $GoogleUser->name; ?></span>
            </div>
            <div class="card-content">
                <strong><?= \Yii::t('app','Synchronization'); ?></strong><br/>
                <?php
                
                    if(GoogleAPIHelper::isEnabled()){
                
                        if(GoogleContactsHelper::isEnabled()){
                            echo $this->render('_switchContacts',['GoogleUser'=>$GoogleUser]);
                        }
                        
                        if(GoogleCalendarHelper::isEnabled()){
                            echo $this->render('_switchCalendar',['GoogleUser'=>$GoogleUser]);
                        }
                          
                        if(GoogleDriveHelper::isEnabled()){
                            echo $this->render('_switchDrive',['GoogleUser'=>$GoogleUser]);
                        }
                        
                        if(GoogleMailHelper::isEnabled()){
                            echo $this->render('_switchMail',['GoogleUser'=>$GoogleUser]);
                        }
                        
                        if(GooglePlusHelper::isEnabled()){
                            echo $this->render('_switchPlus',['GoogleUser'=>$GoogleUser]);
                        }
                        
                        if(GoogleHistoryAPIHelper::isEnabled()){
                            echo $this->render('_switchLocation',['GoogleUser'=>$GoogleUser]);
                        }
                        
                    }
                    
                ?>
            </div>
            <div class="card-action">
                <a href="/google/index/remove?id=<?php echo $GoogleUser->id; ?>"><?php echo \Yii::t('app','Remove'); ?></a>
            </div>
        </div>
    </div>
</div>
<?php
        
    }

?>
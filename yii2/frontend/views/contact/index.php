<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;

?> 
<div class="row">
    <div class="col s12 m12">
        <table class="bordered striped highlight responsive-table">
            <thead>
                <tr>
                    <th data-field="photo"></th>
                    <th data-field="name"><?php echo \Yii::t('app', 'Name'); ?></th>
                    <th data-field="place"><?php echo \Yii::t('app', 'Place'); ?></th>
                    <th data-field="company"><?php echo \Yii::t('app', 'Company'); ?></th>
                    <th data-field="number"><?php echo \Yii::t('app', 'Phonenumber'); ?></th>
                    <th data-field="mail"><?php echo \Yii::t('app', 'Mail address'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                    foreach($contacts as $contact){
                
                ?>
                <tr>
                    <td><img height="20px" width="20px" src="<?php echo $contact->image; ?>" /></td>
                    <td><?php echo $contact->main_name; ?></td>
                    <td><?php echo $contact->place; ?></td>
                    <td><?php echo $contact->company; ?></td>
                    <td><?php echo $contact->phonenumber; ?></td>
                    <td><?php echo $contact->mailaddress; ?></td>
                </tr>
                <?php
                
                    }
                    
                ?>
            </tbody>
        </table>
    </div>
</div>
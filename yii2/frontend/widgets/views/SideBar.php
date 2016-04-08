<div id="sidebar-hamburger" class="btn-floating btn-large waves-effect waves-light red">
    <i class="material-icons">view_headline</i>
</div>
<ul id="nav-sidebarmenu" class="side-nav fixed" style="top:64px;">
    <?php 
        foreach($items as $item){
    ?>
    <li class="bold">
        <a <?php if(isset($item['title'])){ echo 'title="'.$item['title'].'" '; } ?> <?php if(isset($item['value'])){ echo 'value="'.$item['value'].'" '; } ?><?php if(isset($item['url'])){ echo 'href="'.$item['url'].'" ';} ?> class="waves-effect waves-teal <?php if(isset($item['class'])){echo $item['class'];} ?>">
            <?php if(isset($item['icon'])){ echo '<i class="fa '.$item['icon'].'"></i>&nbsp;'; } ?><?php echo $item['label']; ?>
        </a>
    </li>
    <?php 
        } 
    ?>
    <li onclick="jQuery('#nav-sidebarmenu').toggle();" style="cursor:pointer;" class="bold">
        <a class="waves-effect waves-teal">
            <i class="fa fa-arrow-left"></i> <?php echo \Yii::t('app', 'Close'); ?>
        </a>
    </li>
</ul>
<script type="text/javascript">
    window.onload = function() {
        jQuery('#nav-sidebarmenu').toggle();
        jQuery('#sidebar-hamburger').click(
            function(){
                jQuery('#nav-sidebarmenu').toggle();
            }
        );
    };
</script>
<ul id="nav-mobile" class="side-nav fixed" style="top:64px;">
    <?php 
        foreach($items as $item){
    ?>
    <li class="bold">
        <a href="<?php echo $item['url']; ?>" class="waves-effect waves-teal">
            <?php echo $item['label']; ?>
        </a>
    </li>
    <?php 
        } 
    ?>
</ul>
<script type="text/javascript">
    window.onload = function() {
        jQuery('#nav-mobile').toggle();
    };
</script>
<?php

    use macgyer\yii2materializecss\widgets\form\SwitchButton;
    use frontend\modules\imdb\components\IMDBHelper;

    $maxcolumns = 6;
    $columncount = 0;
    
    foreach($movies as $movie){
        
        unset($firstTorrent);
        
        $image = $movie->getImage();
        if(!empty($movie->torrents)){
            $firstTorrent = $movie->getBestTorrent();
            if($firstTorrent === false){
                unset($firstTorrent);
            }
        }
        
        if($image !== false and isset($firstTorrent) and !empty($firstTorrent)){
        
            if($columncount === 0){
                echo '<div class="row">';
            }
        
?>
    <div class="col s12 m2">
        <div class="card">
            <div class="card-image">
                <img src="<?php echo $movie->getImage(); ?>" />
                <span class="card-title"><?php echo $movie->title; ?></span>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col s12 m12">
                        <?php echo \Yii::t('app','Year'); ?>: <?php echo $movie->year; ?>
                    </div>
                    <div class="col s12 m12">
                        <?php echo \Yii::t('app','Rating'); ?>: <?php echo $movie->rating; ?>
                    </div>
                    <div class="col s12 m12">
                        <?php echo \Yii::t('app','Rank'); ?>: <?php echo $movie->rank; ?>
                    </div>
                </div>
            </div>
            <div class="card-action" data-movieid="<?php echo $movie->id; ?>" <?php
                
                foreach($firstTorrent as $key => $value){
                    if(!is_array($value) and !is_object($value)){
                        echo 'data-'.$key.'="'.$value.'" ';
                    }
                }
                
            ?>>
                <a title="<?php echo \Yii::t('app','View torrent'); ?>" href="<?php echo $firstTorrent->url; ?>" target="_blank" style="margin-right:0px;">
                    <?php echo IMDBHelper::formatBytes($firstTorrent->size,2); ?>&nbsp;
                </a>
                <i class="fa-arrow-up fa"></i><?php echo $firstTorrent->seeds; ?>&nbsp;
                <a title="<?php echo \Yii::t('app','Download magnet'); ?>" style="margin-right:0px;" class="magnetlink" data-magnetfile="<?php echo urlencode($firstTorrent->magnet); ?>" href="javascript:void(0);">
                    <i class="fa fa-magnet"></i>
                </a>&nbsp;
                <a title="<?php echo \Yii::t('app','Download torrent'); ?>" style="margin-right:0px;" class="torrentlink" data-torrentfile="<?php echo $firstTorrent->torrentfile; ?>" href="javascript:void(0);">
                    <i class="fa fa-file"></i>
                </a>
                <?php if($firstTorrent->hasSubtitle()){ ?>
                <i class="fa fa-ticket" title="<?php echo count($movie->subtitles); ?> subtitels"></i>
                <?php 
                
                    }
                
                ?>
            </div>
        </div>
    </div>
<?php
        
            $columncount++;

            if($columncount === $maxcolumns){
                echo "</div>";
                $columncount = 0;
            }

        }
        
    }
    
    if($columncount !== 0){
        echo "</div>";
    }

?>
<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function(event){
        
        jQuery('.magnetlink').click(function(){
            
            var TMPparent = jQuery(jQuery(this).parent().get(0));
            
            jQuery.ajax({
                type: "POST",
                url: "/imdb/index/downloadtorrent/",
                data: {
                    'Torrent' : {
                        'site': jQuery(TMPparent).data('site'),
                        'calendar': jQuery(TMPparent).data('size'),
                        'url': jQuery(TMPparent).data('url'),
                        'title': jQuery(TMPparent).data('title'),
                        'magnet': jQuery(this).data('magnetfile')
                    },
                    'Movie' : {
                        'id': jQuery(TMPparent).data('movieid'),
                    }
                },
                success: function(data){
                    addMessage('<?php echo \Yii::t('app','Magnet is downloading'); ?>');
                }
            });
            
        });
        
        jQuery('.torrentlink').click(function(){
            var TMPparent = jQuery(jQuery(this).parent().get(0));
            
            jQuery.ajax({
                type: "POST",
                url: "/imdb/index/downloadtorrent/",
                data: {
                    'Torrent' : {
                        'site': jQuery(TMPparent).data('site'),
                        'calendar': jQuery(TMPparent).data('size'),
                        'url': jQuery(TMPparent).data('url'),
                        'title': jQuery(TMPparent).data('title'),
                        'torrent': jQuery(this).data('magnetfile')
                    },
                    'Movie' : {
                        'id': jQuery(TMPparent).data('torrentfile'),
                    }
                },
                success: function(data){
                    addMessage('<?php echo \Yii::t('app','Torrent is downloading'); ?>');
                }
            });
        });
        
    });
    
</script>
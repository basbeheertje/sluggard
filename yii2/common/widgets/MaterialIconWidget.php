<?php

    namespace common\widgets;

    use yii\base\Widget;
    use yii\helpers\Html;

    /**
     * Class MaterialIconWidget
     * @property string $iconcode
     * @property string $iconcolor
     * @property string $backgroundcolor
     * @property int $size
     * @property int $fontsize
     * @property int $round
     * @property int $shadow
     */
    class MaterialIconWidget extends Widget
    {
        public $iconcode;
        public $iconcolor;
        public $backgroundcolor;
        public $size;
        public $fontsize;
        public $round;
        public $shadow;

        public function init()
        {
            parent::init();
            if ($this->iconcode === null) {
                $this->iconcode = \Yii::$app->params['widgets']['MaterialIcon']['iconcode'];
            }
            if ($this->iconcolor === null) {
                $this->iconcolor = \Yii::$app->params['widgets']['MaterialIcon']['iconcolor'];
            }
            if ($this->backgroundcolor === null) {
                $this->backgroundcolor = \Yii::$app->params['widgets']['MaterialIcon']['backgroundcolor'];
            }
            if ($this->size === null) {
                $this->size = \Yii::$app->params['widgets']['MaterialIcon']['size'];
            }
            if ($this->fontsize === null) {
                $this->fontsize = \Yii::$app->params['widgets']['MaterialIcon']['fontsize'];
            }
            if ($this->round === null) {
                $this->round = \Yii::$app->params['widgets']['MaterialIcon']['round'];
            }
            if ($this->shadow === null) {
                $this->shadow = \Yii::$app->params['widgets']['MaterialIcon']['shadow'];
            }
        }

        public function run()
        {
            
            /** @var array $shadows */
            $shadows = [];
            
            /** @var int $i */
            $i = 0;
            while ($i < $this->shadow) {
                /** @var int $red */
                $red = 163 + round(((226 - 163) / $this->shadow) * $i);
                //$red = 255 - round(((255 - 0) / $this->shadow) * $i);
                
                /** @var int $green */
                $green = 21 + round(((29 - 21) / $this->shadow) * $i);
                //$green = 255 - round(((255 - 0) / $this->shadow) * $i);
                
                /** @var int $yellow */
                $yellow = 69 + round(((96 - 69) / $this->shadow) * $i);
                //$yellow = 255 - round(((255 - 0) / $this->shadow) * $i);
                
                $alpha = 1 - (round((100 / $this->shadow) * $i) / 100 );
                
                $shadows[] = 'rgba('.$red.', '.$green.', '.$yellow.','.$alpha.') '.$i.'px '.$i.'px 0px';
                $i++;
            }
            
            if(!empty($shadows)){
                $shadows = 'text-shadow: '.implode(',',$shadows).';';
            }else{
                $shadows = '';
            }
            
            return $this->render('MaterialIcon',[
                'iconcode'  => $this->iconcode,
                'iconcolor' => $this->iconcolor,
                'backgroundcolor' => $this->backgroundcolor,
                'size'              => $this->size,
                'fontsize'          => $this->fontsize,
                'round'             => $this->round,
                'shadow'            => $shadows
            ]);
        }
    }
<?php

    namespace frontend\widgets;

    use yii\base\Widget;
    use yii\helpers\Html;

    /**
     * Class IntroWidget
     * @property string $class
     * @property string $title
     * @property string $text
     * @property string $textclass
     */
    class IntroWidget extends Widget
    {
        public $class;
        public $title;
        public $text;
        public $textclass;
        
        public function init()
        {
            parent::init();
            if ($this->textclass === null) {
                $this->textclass = 'text-primary-color';
            }
            if ($this->class === null) {
                $this->class = 'default-primary-color';
            }
        }
        
        public function run()
        {
            return $this->render('Intro',[
                'title'         => $this->title,
                'class'          => $this->class,
                'textclass'          => $this->textclass,
                'text'          => $this->text
            ]);
        }
    }
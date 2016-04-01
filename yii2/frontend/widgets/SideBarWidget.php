<?php

    namespace frontend\widgets;

    use yii\base\Widget;
    use yii\helpers\Html;

    /**
     * Class SideBarWidget
     * @property array $items
     * @property string $class
     * @property string $textclass
     * @property string $logo
     */
    class SideBarWidget extends Widget
    {
        public $items;
        public $class;
        public $textclass;
        public $logo;
        
        public function init()
        {
            parent::init();
            if ($this->class === null) {
                $this->class = 'text-primary-color';
            }
            if ($this->textclass === null) {
                $this->textclass = 'text-primary-color';
            }
        }
        
        public function run()
        {
            return $this->render('SideBar',[
                'items'         => $this->items,
                'clas'          => $this->class,
                'textclass'     => $this->textclass,
                'logo'          => $this->logo
            ]);
        }
    }
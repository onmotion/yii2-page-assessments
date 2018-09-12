<?php
/**
 * Created by PhpStorm.
 * User: kozhevnikov
 * Date: 02/07/2018
 * Time: 14:26
 */

namespace common\modules\assessments\widget;

class AssessmentWidgetAsset extends \yii\web\AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets/dist';
        parent::init();
    }

//    public $publishOptions = [
//        'forceCopy' => true //dev
//    ];

    public $js = [
        'build.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'onmotion\vue\VueAsset',
        'onmotion\vue\VueResourceAsset',
    ];
}
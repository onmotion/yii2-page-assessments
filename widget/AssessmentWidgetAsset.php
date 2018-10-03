<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-page-assessments
 */

namespace onmotion\assessments\widget;

class AssessmentWidgetAsset extends \yii\web\AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets/dist';

        parent::init();
    }

    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV //dev
    ];

    public $js = [
        'build.js',
    ];

    public $depends = [
        'onmotion\vue\VueAsset',
        'onmotion\vue\VueResourceAsset',
    ];
}
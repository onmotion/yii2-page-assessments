<?php
/**
 * Created by PhpStorm.
 * User: kozhevnikov
 * Date: 02/07/2018
 * Time: 14:33
 */
/** @var $this \yii\web\View */
/** @var $assessments string */
/** @var $actions string */
/** @var $maxValue string */
/** @var $fluent string */
/** @var $messages string */

?>

    <div id="page-assessment" class="page-assessment-container <?= $fluent == 'true' ? 'fluent' : '' ?>" >
        <page-assessment></page-assessment>
    </div>

<?php

$this->registerJs(<<<JS
window.assessments = {};
window.assessments.assessments = $assessments;
window.assessments.actions = $actions;
window.assessments.maxValue = $maxValue;
window.assessments.fluent = $fluent;
window.assessments.messages = $messages;
JS
    , $this::POS_BEGIN);
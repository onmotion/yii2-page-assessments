<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-page-assessments
 */

/** @var $this \yii\web\View */
/** @var $assessments string */
/** @var $actions string */
/** @var $fluent string */
/** @var $messages string */
/** @var $id string */
/** @var $timeout integer */


?>

    <div id="<?= json_decode($id) ?>" class="page-assessment-container <?= $fluent == 'true' ? 'fluent' : '' ?>" >
        <page-assessment></page-assessment>
    </div>

<?php
$containerId = json_decode($id);
$this->registerJs(<<<JS
window.$containerId = {};
window.$containerId.assessments = $assessments;
window.$containerId.actions = $actions;
window.$containerId.fluent = $fluent;
window.$containerId.messages = $messages;
window.assessmentContainerId = $id;

Vue.http.options.emulateJSON = true; // application/x-www-form-urlencoded

setTimeout(function(e) {
  new Vue({
  el: '#' + $id,
});
}, $timeout);


JS
    );


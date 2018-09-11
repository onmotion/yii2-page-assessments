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
/** @var $fluent string */
/** @var $messages string */
/** @var $id string */


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

new Vue({
  el: '#' + window.assessmentContainerId,
 // render: h => h(App)
});

JS
    );


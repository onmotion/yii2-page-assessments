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
/** @var $icons integer */

?>

<div id="<?= json_decode($id) ?>" class="page-assessment-container <?= $fluent == 'true' ? 'fluent' : 'static' ?>">
    <page-assessment :assessments-prop="props"></page-assessment>
</div>

<?php
$containerId = json_decode($id);
$this->registerJs(<<<JS
Vue.http.options.emulateJSON = true; // application/x-www-form-urlencoded

setTimeout(function(e) {
  new Vue({
  el: '#' + $id,
  data() {
      return {
          props: {
              id: $id,
              assessments: $assessments,
              actions: $actions,
              fluent: $fluent,
              messages: $messages,
              icons: $icons
          }
      }
  }
});
}, $timeout);


JS
);


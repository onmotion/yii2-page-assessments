<?php
/**
 * Created by PhpStorm.
 * User: kozhevnikov
 * Date: 02/07/2018
 * Time: 14:23
 */

namespace onmotion\assessments\widget;

use onmotion\assessments\models\Assessment;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 *
 * @property ActiveRecord|null $model
 */
class AssessmentWidget extends Widget
{

    public $model;
    public $questions;
    public $fluent = false;
    public $actions = [
        'save' => null,
    ];
    public $id = 'page-assessment';
    public $timeout = 1500;

    public function init()
    {
        \Yii::setAlias('@photoReportWidgetRoot', __DIR__);

        // set up i8n
        if (empty(\Yii::$app->i18n->translations['assessments'])) {
            \Yii::$app->i18n->translations['assessments'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }

        parent::init();
    }

    public function getViewPath()
    {
        return \Yii::getAlias('@photoReportWidgetRoot/views');
    }

    public function beforeRun()
    {
        if (empty($this->questions)) throw new InvalidConfigException('questions must be set');
        $this->actions['save'] = $this->actions['save'] ?: Url::toRoute(['/assessments/default/save']);
        //  $this->actions['update'] = $this->actions['update'] ?: Url::toRoute(['/assessments/default/update']);

        $this->questions = array_map(function ($item) {
            if (is_array($item)) {
                if (!isset($item['title'])) throw new InvalidConfigException('a title not found in the questions array item');
                $item['allowComment'] = $item['allowComment'] ?? false;
            } else {
                $title = $item;
                $item = [];
                $item['title'] = $title;
                $item['allowComment'] = false;
            }
            return $item;
        }, $this->questions);

        return parent::beforeRun();
    }


    public function run()
    {
        parent::run();

        AssessmentWidgetAsset::register($this->getView());

        $answeredQuestions = Assessment::getAnsweredQuestionsForUser(ArrayHelper::getColumn($this->questions, 'title'), (\Yii::$app->request->pathInfo ?: '/'));

        $this->questions = array_filter($this->questions, function ($item) use ($answeredQuestions) {
            return !in_array($item['title'], $answeredQuestions);
        });

        if (!$this->questions) {
            return null;
        }

        $assessments = [];
        foreach ($this->questions as $assessmentQuestion) {
            $assessment = new Assessment([
                'assessment_user_id' => \Yii::$app->getUser()->getId() ?? null,
                'assessment_user_ip' => \Yii::$app->request->userIP ?? null,
                'assessment_question' => $assessmentQuestion['title'],
                'assessment_url' => \Yii::$app->request->pathInfo ?: '/',
                'maxValue' => ArrayHelper::getValue($assessmentQuestion, 'maxValue', 5)
            ]);
            if ($assessmentQuestion['allowComment']) {
                $assessment->assessment_comment = '';
            }

            if ($this->model) {
                $assessment->assessment_object_class = get_class($this->model);
                $assessment->assessment_object_id = $this->model->primaryKey ?? null;
            }

            $assessments[] = $assessment->toArray();
        }

        $assessments = json_encode($assessments);
        $actions = json_encode($this->actions);
        $fluent = json_encode($this->fluent);
        $messages = json_encode([
            'commentPrompt' => \Yii::t('assessments', 'You can leave a comment...'),
            'successMessage' => \Yii::t('assessments', 'Thank you for your vote!')
        ]);
        $id = json_encode($this->getId());

        echo $this->render('index', ['assessments' => $assessments, 'actions' => $actions,
             'fluent' => $fluent, 'messages' => $messages, 'id' => $id, 'timeout' => $this->timeout]);
    }


}
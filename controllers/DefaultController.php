<?php

namespace common\modules\assessments\controllers;

use common\modules\assessments\models\Assessment;
use common\modules\assessments\models\AssessmentQuestion;
use yii\base\UserException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Default controller for the `assessments` module
 */
class DefaultController extends Controller
{
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

    /**
     * Create new vote
     *
     */
    public function actionSave()
    {
        $post = \Yii::$app->request->post();
        $model = new Assessment();
        if ($model->load($post, '') && $model->validate()){
            if (isset($post['assessment_id'])){
                $existingModel = Assessment::findOne($post['assessment_id']);
                if ($existingModel){
                     $existingModel->load($post, '');
                    $model = $existingModel;
                }
            }
            return $model->save(false) ? $model->assessment_id : false;

        }else{
            throw new UserException(current((array)$model->getFirstErrors()));
        }


    }

    public function actionUpdate()
    {
        return 'update';
    }
}

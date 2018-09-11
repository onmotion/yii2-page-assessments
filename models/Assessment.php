<?php

namespace common\modules\assessments\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "assessment".
 *
 * @property int $assessment_id
 * @property int $assessment_object_id
 * @property int $assessment_object_class
 * @property int $assessment_value
 * @property string $assessment_comment
 * @property string $assessment_question
 * @property int $assessment_user_id
 * @property string $assessment_user_ip
 * @property string $assessment_url
 * @property string $assessment_created_at
 * @property string $assessment_updated_at
 * @property boolean $assessment_is_declined
 */
class Assessment extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assessment';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['maxValue']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assessment_url'], 'required'],
            [['assessment_is_declined'], 'boolean'],
            [['assessment_object_id', 'assessment_value', 'assessment_user_id'], 'integer'],
            [['assessment_created_at', 'assessment_updated_at'], 'safe'],
            [['assessment_value', 'assessment_comment'], 'default', 'value' => null],
            [['assessment_comment', 'assessment_question', 'assessment_url', 'assessment_object_class'], 'string', 'max' => 255],
            [['assessment_user_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'assessment_id' => 'Assessment ID',
            'assessment_object_type' => 'Assessment Object Type',
            'assessment_object_id' => 'Assessment Object ID',
            'assessment_value' => 'Assessment Value',
            'assessment_user_id' => 'Assessment User ID',
            'assessment_created_at' => 'Assessment Created At',
            'assessment_updated_at' => 'Assessment Updated At',
            'assessment_assessment_question_id' => 'Assessment Assessment Question ID',
        ];
    }

    /**
     * Return an array with answered questions
     * @param array $questions
     * @param null $userIdOrIp
     * @return array
     */
    static function getAnsweredQuestionsForUser(array $questions, string $url, $userIdOrIp = null)
    {
        $userIdOrIp = $userIdOrIp ?: (\Yii::$app->getUser()->getId() ?? \Yii::$app->request->userIP);
        $q = self::find()
            ->select(['assessment_question'])
            ->andWhere(['assessment_question' => $questions])
            ->andWhere(['assessment_url' => $url]);

        if (is_numeric($userIdOrIp)){
            $q->andWhere(['assessment_user_id' => $userIdOrIp]);
        } else {
            $q->andWhere(['assessment_user_ip' => $userIdOrIp]);
        }

        return $q->column();
    }


}

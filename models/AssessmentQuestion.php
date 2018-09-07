<?php

namespace common\modules\assessments\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * This is the model class for table "assessment_question".
 *
 * @property int $assessment_question_id
 * @property string $assessment_question_question
 * @property bool $assessment_question_is_active
 * @property string $assessment_question_object_type
 * @property string $assessment_question_type
 * @property string $assessment_question_url
 * @property int $assessment_question_max_value
 * @property bool $assessment_question_is_commentable
 * @property string $assessment_question_created_at
 *
 * @property Assessment[] $assessments
 */
class AssessmentQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assessment_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assessment_question_is_active', 'assessment_question_is_commentable'], 'boolean'],
            [['assessment_question_object_type'], 'required'],
            [['assessment_question_type'], 'string'],
            [['assessment_question_max_value'], 'integer'],
            [['assessment_question_created_at'], 'safe'],
            [['assessment_question_question', 'assessment_question_object_type', 'assessment_question_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'assessment_question_id' => 'Assessment Question ID',
            'assessment_question_question' => 'Assessment Question Question',
            'assessment_question_is_active' => 'Assessment Question Is Active',
            'assessment_question_object_type' => 'Assessment Question Object Type',
            'assessment_question_type' => 'Assessment Question Type',
            'assessment_question_max_value' => 'Assessment Question Max Value',
            'assessment_question_is_commentable' => 'Assessment Question Is Commentable',
            'assessment_question_created_at' => 'Assessment Question Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssessments()
    {
        return $this->hasMany(Assessment::className(), ['assessment_question_id' => 'assessment_question_id']);
    }

    /**
     * @param Model $model
     * @param $userId
     * @return array|AssessmentQuestion[]
     */
//    static function getAvailableQuestionsForModelAndUser(Model $model, $userId)
//    {
//        return self::find()->leftJoin(Assessment::tableName(), "assessment_assessment_question_id=assessment_question_id and (assessment_user_id = $userId or assessment_user_ip = $userId)")
//            ->andWhere(['assessment_question_is_active' => true])
//            ->andWhere(['assessment_question_object_type' => get_class($model)])
//            ->andWhere(['assessment_value' => null])
//            ->all();
//    }

    static function getAvailableQuestionsForUser($userId)
    {
        $current = Url::current();

        return self::find()
            ->leftJoin(Assessment::tableName(), "assessment_assessment_question_id=assessment_question_id 
        and (assessment_user_id = $userId or assessment_user_ip = $userId)")
            ->andWhere(['assessment_question_is_active' => true])
            ->andWhere(['assessment_question_url' => $current])
            ->andWhere(['assessment_value' => null])
            ->all();

    }
}

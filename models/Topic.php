<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\services\TransliteratorService;
use yii\db\ActiveRecord;
use yii\db\Exception;

class Topic extends ActiveRecord
{
    const MODEL_NAME = 'topic';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE_TEXT = 'Скрытая';
    const STATUS_ACTIVE_TEXT = 'Активная';

    public static function tableName()
    {
        return '{{%topic}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    public function rules()
    {
        return [
            [['name', 'status'], 'trim'],
            [['name', 'status'], 'required'],
            ['name', 'string', 'min' => Yii::$app->params['articleMinValue'], 'max' => Yii::$app->params['articleMaxValue']],
            ['name', 'match', 'pattern' => Yii::$app->params['articlePattern'], 'message' => Yii::$app->params['articlePatternMessage']],
            ['name', function ($attribute) {
                $result = self::find()->where([
                    'name' => $this->name,
                    'project_id' => Project::findModel()->id
                ])->one();
                if ($result !== null && $result->id != $this->id) {
                    $this->addError($attribute, 'Имя темы «' . $this->name . '» уже используется.');
                }
            }],
            [['description', 'code'], 'string'],
            [['description', 'code'], 'trim'],
            ['status', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание кода',
            'code' => 'Пример кода',
            'status' => 'Статус'
        ];
    }

    public function beforeSave($insert)
    {
        $this->slug = TransliteratorService::execute($this->name);

        return parent::beforeSave($insert);
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public function getTopicItems()
    {
        return $this->hasMany(TopicItem::class, ['topic_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['topic_id' => 'id'])
            ->with(['attachments', 'tags', 'author'])
            ->orderBy(['updated_at' => SORT_DESC]);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => self::STATUS_ACTIVE_TEXT,
            self::STATUS_INACTIVE => self::STATUS_INACTIVE_TEXT
        ];
    }

    /**
     * @param null $id
     * @param null $url
     * @return Topic|null
     * @throws Exception
     */
    public static function findModel($id = null, $url = null)
    {
        if (isset($id)) {
            $model = self::findOne($id);
        } else {
            $model = self::findOne([
                'project_id' => Project::findModel($id, $url)->id,
                'slug' => isset($url) ? explode('/', $url)[3] : Yii::$app->request->get('topicSlug')
            ]);
        }

        if ($model === null) {
            throw new Exception('Model not found.');
        }

        return $model;
    }
}
<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\services\TransliteratorService;
use yii\db\ActiveRecord;
use yii\db\Exception;

class TopicItem extends ActiveRecord
{
    const MODEL_NAME = 'topic_item';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE_TEXT = 'Скрытый';
    const STATUS_ACTIVE_TEXT = 'Активный';

    public static function tableName()
    {
        return '{{%topic_item}}';
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
                    'project_id' => Project::findModel()->id,
                    'topic_id' => $this->topic->id
                ])->one();
                if ($result !== null && $result->id != $this->id) {
                    $this->addError($attribute, 'Имя раздела «' . $this->name . '» уже используется.');
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

    protected function getAuthorId()
    {
        return Yii::$app->user->identity->getId();
    }

    public function getTopic()
    {
        return $this->hasOne(Topic::class, ['id' => 'topic_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['topic_item_id' => 'id']);
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
     * @return TopicItem|null
     * @throws Exception
     */
    public static function findModel($id = null, $url = null)
    {
        if (isset($id)) {
            $model = self::findOne($id);
        } else {
            $model = self::findOne([
                'topic_id' => Topic::findModel($id, $url)->id,
                'slug' => isset($url) ? explode('/', $url)[4] : Yii::$app->request->get('topicItemSlug')
            ]);
        }

        if ($model === null) {
            throw new Exception('Model not found.');
        }

        return $model;
    }
}
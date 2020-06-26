<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\services\TransliteratorService;
use yii\db\ActiveRecord;
use yii\db\Exception;

class Project extends ActiveRecord
{
    const MODEL_NAME = 'project';

    public static function tableName()
    {
        return '{{%project}}';
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
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'unique'],
            ['name', 'string', 'min' => Yii::$app->params['articleMinValue'], 'max' => Yii::$app->params['articleMaxValue']],
            ['name', 'match', 'pattern' => Yii::$app->params['articlePattern'], 'message' => Yii::$app->params['articlePatternMessage']],
            [['description', 'code'], 'string'],
            [['description', 'code'], 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание кода',
            'code' => 'Пример кода'
        ];
    }

    public function beforeSave($insert)
    {
        $this->slug = TransliteratorService::execute($this->name);

        return parent::beforeSave($insert);
    }

    public function getTopics()
    {
        return $this->hasMany(Topic::class, ['project_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['project_id' => 'id']);
    }

    /**
     * @param null $id
     * @param null $url
     * @return Project|null
     * @throws Exception
     */
    public static function findModel($id = null, $url = null)
    {
        if (isset($id)) {
            $model = self::findOne($id);
        } else {
            $model = self::findOne(['slug' => isset($url) ? explode('/', $url)[2] : \Yii::$app->request->get('slug')]);
        }

        if ($model === null) {
            throw new Exception('Model not found.');
        }

        return $model;
    }
}
<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use app\services\TransliteratorService;

class Comment extends ActiveRecord
{
    const IMAGES_DIR = 'img/comments/';

    const COMMENTS_DEV = 0;
    const COMMENTS_BUSINESS = 1;

    const AUTHOR_PERMISSION_DENIED = 0;
    const AUTHOR_PERMISSION_GRANTED = 1;

    public $article_model;
    public $article_id;
    public $count_dev;
    public $count_business;
    public $_attachments;
    public $author_permission;

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    public static function tableName()
    {
        return '{{%comment}}';
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'text' => 'Комментарий',
            '_attachments' => 'Файлы'
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 100],
            [['text'], 'string'],
            [['title', 'text'], 'required'],
            [['author_id', 'tab'], 'required'],
            [['project_id', 'topic_id', 'topic_item_id'], 'default', 'value' => null],
            [['_attachments'], 'file', 'maxFiles' => 10]
        ];
    }

    public function init()
    {
        parent::init();
        if (isset($this->article_model) && isset($this->article_id)) {
            $this->count_dev = $this->getCount(self::COMMENTS_DEV);
            $this->count_business = $this->getCount(self::COMMENTS_BUSINESS);
        }
    }

    /**
     * @param $tab
     * @return int
     */
    private function getCount($tab)
    {
        return (int)static::find()
            ->where([$this->article_model . '_id' => $this->article_id])
            ->andWhere(['tab' => $tab])
            ->count();
    }

    /**
     * @param $extension
     * @return string
     */
    private function setImage($extension)
    {
        if (file_exists(self::IMAGES_DIR . $extension . '.png')) {
            return self::IMAGES_DIR . $extension . '.png';
        }
        return self::IMAGES_DIR . 'file' . '.png';
    }

    /**
     * @throws InvalidConfigException
     */
    public function updateData()
    {
        $this->updated_at = Yii::$app->formatter->asDatetime($this->updated_at, 'short');
        $this->author_permission = $this->author_id == Yii::$app->user->identity->id ? self::AUTHOR_PERMISSION_GRANTED : self::AUTHOR_PERMISSION_DENIED;
        return $this;
    }

    /**
     * Upload comment attachments if present
     */
    public function uploadAttachments()
    {
        $this->_attachments = UploadedFile::getInstances($this, '_attachments');

        if ($this->_attachments) {
            $uploadDir = Attachment::ATTACHMENTS_DIR;

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($this->_attachments as $file) {
                $commentDir = 'comment-' . $this->id . '/';
                $fileName = TransliteratorService::execute($file->baseName) . '.' . $file->extension;

                if (!file_exists($uploadDir . $commentDir)) {
                    mkdir($uploadDir . $commentDir, 0777, true);
                }

                if ($file->saveAs($uploadDir . $commentDir . $fileName)) {
                    $attachment = new Attachment();
                    $attachment->comment_id = $this->id;
                    $attachment->image = $this->setImage($file->extension);
                    $attachment->file = $fileName;
                    $attachment->save();
                    unset($attachment);
                }
            }
        }
    }

    /**
     * @param $tagIds array|null
     * @return bool
     */
    public function tagsChanged(&$tagIds)
    {
        $tags = $this->tags;
        if (isset($tagIds) && !empty($tags)) {
            $tagIds = array_map('intval', $tagIds);
            $_tagIds = ArrayHelper::getColumn($tags, function ($tag) {
                return $tag->id;
            });
            $diff = array_merge(array_diff($tagIds, $_tagIds), array_diff($_tagIds, $tagIds));
            return !empty($diff);
        } elseif (!isset($tagIds) && empty($tags)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('comment_tag', ['comment_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::class, ['comment_id' => 'id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }
}
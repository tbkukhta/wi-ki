<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "attachment".
 *
 * @property int $comment_id
 * @property int $file
 */
class Attachment extends ActiveRecord
{
    const ATTACHMENTS_DIR = 'uploads/attachments/';

    public static function tableName()
    {
        return '{{%attachment}}';
    }

    /**
     * Recursive delete all files in directory and directory itself
     *
     * @param $dir
     * @return bool
     */
    public static function deleteFolder($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::deleteFolder("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    /**
     * Delete file and record
     *
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function deleteAttachmentFile()
    {
        $commentDir = self::ATTACHMENTS_DIR . '/comment-' . $this->comment_id;

        if ($this->delete() && file_exists($commentDir . '/' . $this->file)) {
            unlink($commentDir . '/' . $this->file);

            $attachmentsCount = self::find()->where(['comment_id' => $this->comment_id])->count();
            if ($attachmentsCount < 1) {
                self::deleteFolder($commentDir);
            }
        }
    }
}
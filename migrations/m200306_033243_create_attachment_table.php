<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachment}}`.
 */
class m200306_033243_create_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attachment}}', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer()->notNull(),
            'image' => $this->string('130')->notNull(),
            'file' => $this->string(255)->notNull()
        ]);

        $this->createIndex(
            'idx-attachment-comment_id',
            'attachment',
            'comment_id'
        );

        $this->addForeignKey(
            'fk-attachment-comment_id',
            'attachment',
            'comment_id',
            'comment',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-attachment-comment_id',
            'attachment'
        );

        $this->dropIndex(
            'idx-attachment-comment_id',
            'attachment'
        );

        $this->dropTable('{{%attachment}}');
    }
}
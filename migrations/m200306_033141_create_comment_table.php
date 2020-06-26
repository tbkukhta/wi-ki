<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m200306_033141_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'project_id' => $this->integer()->defaultValue(null),
            'topic_id' => $this->integer()->defaultValue(null),
            'topic_item_id' => $this->integer()->defaultValue(null),
            'tab' => $this->integer()->notNull()->defaultValue(0),
            'title' => $this->string(200)->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer(30),
            'updated_at' => $this->integer(30)
        ]);

        $this->createIndex(
            'idx-comment-author_id',
            'comment',
            'author_id'
        );

        $this->addForeignKey(
            'fk-comment-author_id',
            'comment',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comment-project_id',
            'comment',
            'project_id'
        );

        $this->addForeignKey(
            'fk-comment-project_id',
            'comment',
            'project_id',
            'project',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comment-topic_id',
            'comment',
            'topic_id'
        );

        $this->addForeignKey(
            'fk-comment-topic_id',
            'comment',
            'topic_id',
            'topic',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comment-topic_item_id',
            'comment',
            'topic_item_id'
        );

        $this->addForeignKey(
            'fk-comment-topic_item_id',
            'comment',
            'topic_item_id',
            'topic_item',
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
            'fk-comment-topic_item_id',
            'comment'
        );

        $this->dropIndex(
            'idx-comment-topic_item_id',
            'comment'
        );

        $this->dropForeignKey(
            'fk-comment-topic_id',
            'comment'
        );

        $this->dropIndex(
            'idx-comment-topic_id',
            'comment'
        );

        $this->dropForeignKey(
            'fk-comment-project_id',
            'comment'
        );

        $this->dropIndex(
            'idx-comment-project_id',
            'comment'
        );

        $this->dropForeignKey(
            'fk-comment-author_id',
            'comment'
        );

        $this->dropIndex(
            'idx-comment-author_id',
            'comment'
        );

        $this->dropTable('{{%comment}}');
    }
}
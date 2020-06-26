<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%topic}}`.
 */
class m200306_032917_create_topic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%topic}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'description' => $this->text(),
            'code' => $this->text(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'created_at' => $this->integer(30),
            'updated_at' => $this->integer(30)
        ]);

        $this->createIndex(
            'idx-topic-project_id',
            'topic',
            'project_id'
        );

        $this->addForeignKey(
            'fk-topic-project_id',
            'topic',
            'project_id',
            'project',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-topic-author_id',
            'topic',
            'author_id'
        );

        $this->addForeignKey(
            'fk-topic-author_id',
            'topic',
            'author_id',
            'user',
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
            'fk-topic-project_id',
            'topic'
        );

        $this->dropIndex(
            'idx-topic-project_id',
            'topic'
        );

        $this->dropForeignKey(
            'fk-topic-author_id',
            'topic'
        );

        $this->dropIndex(
            'idx-topic-author_id',
            'topic'
        );

        $this->dropTable('{{%topic}}');
    }
}
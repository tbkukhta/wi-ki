<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%topic_item}}`.
 */
class m200306_033026_create_topic_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%topic_item}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull(),
            'topic_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(100)->notNull(),
            'description' => $this->text(),
            'code' => $this->text(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'created_at' => $this->integer(30),
            'updated_at' => $this->integer(30)
        ]);

        $this->createIndex(
            'idx-topic_item-project_id',
            'topic_item',
            'project_id'
        );

        $this->addForeignKey(
            'fk-topic_item-project_id',
            'topic_item',
            'project_id',
            'project',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-topic_item-topic_id',
            'topic_item',
            'topic_id'
        );

        $this->addForeignKey(
            'fk-topic_item-topic_id',
            'topic_item',
            'topic_id',
            'topic',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-topic_item-author_id',
            'topic_item',
            'author_id'
        );

        $this->addForeignKey(
            'fk-topic_item-author_id',
            'topic_item',
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
            'fk-topic_item-project_id',
            'topic_item'
        );

        $this->dropIndex(
            'idx-topic_item-project_id',
            'topic_item'
        );

        $this->dropForeignKey(
            'fk-topic_item-author_id',
            'topic_item'
        );

        $this->dropIndex(
            'idx-topic_item-author_id',
            'topic_item'
        );

        $this->dropTable('{{%topic_item}}');
    }
}
<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m200306_032559_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(),
            'description' => $this->text(),
            'code' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->createIndex(
            'idx-project-author_id',
            'project',
            'author_id'
        );

        $this->addForeignKey(
            'fk-project-author_id',
            'project',
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
            'fk-project-author_id',
            'topic'
        );

        $this->dropIndex(
            'idx-project-author_id',
            'topic'
        );

        $this->dropTable('{{%project}}');
    }
}
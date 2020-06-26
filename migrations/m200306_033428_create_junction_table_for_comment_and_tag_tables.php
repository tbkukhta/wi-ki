<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment_tag}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%comment}}`
 * - `{{%tag}}`
 */
class m200306_033428_create_junction_table_for_comment_and_tag_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment_tag}}', [
            'comment_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'PRIMARY KEY(comment_id, tag_id)'
        ]);

        // creates index for column `comment_id`
        $this->createIndex(
            '{{%idx-comment_tag-comment_id}}',
            '{{%comment_tag}}',
            'comment_id'
        );

        // add foreign key for table `{{%comment}}`
        $this->addForeignKey(
            '{{%fk-comment_tag-comment_id}}',
            '{{%comment_tag}}',
            'comment_id',
            '{{%comment}}',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            '{{%idx-comment_tag-tag_id}}',
            '{{%comment_tag}}',
            'tag_id'
        );

        // add foreign key for table `{{%tag}}`
        $this->addForeignKey(
            '{{%fk-comment_tag-tag_id}}',
            '{{%comment_tag}}',
            'tag_id',
            '{{%tag}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%comment}}`
        $this->dropForeignKey(
            '{{%fk-comment_tag-comment_id}}',
            '{{%comment_tag}}'
        );

        // drops index for column `comment_id`
        $this->dropIndex(
            '{{%idx-comment_tag-comment_id}}',
            '{{%comment_tag}}'
        );

        // drops foreign key for table `{{%tag}}`
        $this->dropForeignKey(
            '{{%fk-comment_tag-tag_id}}',
            '{{%comment_tag}}'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            '{{%idx-comment_tag-tag_id}}',
            '{{%comment_tag}}'
        );

        $this->dropTable('{{%comment_tag}}');
    }
}
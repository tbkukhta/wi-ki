<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m200306_032302_init
 */
class m200306_032302_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->notNull()->unique(),
            'first_name' => $this->string(50)->notNull(),
            'last_name' => $this->string(50)->notNull()->defaultValue(''),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'role' => $this->string(30)->notNull()->defaultValue(User::ROLE_USER),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer(30)->defaultValue(time()),
            'updated_at' => $this->integer(30)->defaultValue(time())
        ], $tableOptions);

        $this->insert('user', [
            'id' => 1,
            'login' => Yii::$app->params['adminLogin'], //admin
            'first_name' => 'Администратор',
            'auth_key' => 'CS4vNR_zI5tzSh9i-F3TklT2A2-rduhu',
            'password_hash' => '$2y$13$LzkeB/sWgt8aycVyxNmpGu4v05KheUrlxV9mJk7vN.y3xhKiCnrvq', //123456
            'email' => Yii::$app->params['adminEmail'], //noemail@noemail.com
            'role' => User::ROLE_ADMIN
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
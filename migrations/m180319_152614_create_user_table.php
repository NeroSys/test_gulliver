<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180319_152614_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'lastName' => $this->string(),
            'male' => $this->boolean(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('user_profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'codePostal' => $this->integer(),
            'country' => $this->string(),
            'city' => $this->string(),
            'street' => $this->string(),
            'houseNumber' => $this->integer(11),
            'flatNumber' => $this->integer(11)

        ], $tableOptions);

        $this->createTable('countries', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string()

        ], $tableOptions);

        $this->createIndex('FK_user_profile', '{{%user_profile}}', 'user_id');

        $this->addForeignKey(
            'FK_user_profile_user', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE'
        );

    }

    public function down()
    {
        $this->dropTable('countries');
        $this->dropTable('user_profile');
        $this->dropTable('user');
    }
}

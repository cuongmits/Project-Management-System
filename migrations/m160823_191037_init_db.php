<?php

use yii\db\Migration;

class m160823_191037_init_db extends Migration
{
    public function up()
    {
        $this->createTable('cities', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'city' => $this->string(60)->notNull()->unique(),
        ]);

        $this->createTable('users', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'fullname' => $this->string(60)->notNull(),
            'email' => $this->string(100)->notNull(),
            'city_id' => $this->bigInteger()->unsigned(),
        ]);

        //create index for column 'city_id' of table 'users'
        $this->createIndex('idx-users-city_id', 'users', 'city_id');
        //add foreign key for table 'users'
        $this->addForeignKey('fk-users-city_id', 'users', 'city_id', 'cities', 'id', 'RESTRICT');

        $this->createTable('projects', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(200),
            'description' => $this->text(),
            'status' => $this->string(60),
        ]);

        $this->createTable('roles', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'role' => $this->string(60),
        ]);

        $this->createTable('project_user', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'project_id' => $this->bigInteger()->unsigned(), //need to check need 20 or not???
            'user_id' => $this->bigInteger()->unsigned(),
            'role_id' => $this->bigInteger()->unsigned(),
        ]);

        //create index for column 'project_id' of table 'project_user'
        $this->createIndex('idx-project_user-project_id', 'project_user', 'project_id');
        //add foreign key for table 'project_user'
        $this->addForeignKey('fk-project_user-project_id', 'project_user', 'project_id', 'projects', 'id', 'RESTRICT');

        //create index for column 'user_id' of table 'project_user'
        $this->createIndex('idx-project_user-user_id', 'project_user', 'user_id');
        //add foreign key for table 'project_user'
        $this->addForeignKey('fk-project_user-user_id', 'project_user', 'user_id', 'users', 'id', 'RESTRICT');

        //create index for column 'role_id' of table 'project_user'
        $this->createIndex('idx-project_user-role_id', 'project_user', 'role_id');
        //add foreign key for table 'project_user'
        $this->addForeignKey('fk-project_user-role_id', 'project_user', 'role_id', 'roles', 'id', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('project_user');
        $this->dropTable('roles');
        $this->dropTable('projects');
        $this->dropTable('users');
        $this->dropTable('cities');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

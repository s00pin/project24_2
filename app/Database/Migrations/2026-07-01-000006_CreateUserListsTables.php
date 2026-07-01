<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserListsTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'slug']);
        $this->forge->addUniqueKey(['user_id', 'name']);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_lists', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'list_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'media_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'media_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['list_id', 'media_type']);
        $this->forge->addUniqueKey(['list_id', 'media_type', 'media_id']);
        $this->forge->addForeignKey('list_id', 'user_lists', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_list_entries', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_list_entries', true);
        $this->forge->dropTable('user_lists', true);
    }
}

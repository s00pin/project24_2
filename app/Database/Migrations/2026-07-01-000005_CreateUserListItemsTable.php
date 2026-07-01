<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserListItemsTable extends Migration
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
            'media_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'comment'    => 'media|show',
            ],
            'media_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'list_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'favorite|watchlist',
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
        $this->forge->addKey(['user_id', 'list_type']);
        $this->forge->addUniqueKey(['user_id', 'media_type', 'media_id', 'list_type']);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_list_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_list_items', true);
    }
}

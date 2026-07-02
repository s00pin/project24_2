<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserLikesTable extends Migration
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
        $this->forge->addKey(['media_type', 'media_id']);
        $this->forge->addUniqueKey(['user_id', 'media_type', 'media_id']);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_likes', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_likes', true);
    }
}

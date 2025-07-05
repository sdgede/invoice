<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Adjustment extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'type' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'stok_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'who_created' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'who_updated' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('adjustments');
        
    }

    public function down()
    {
        //
    }
}

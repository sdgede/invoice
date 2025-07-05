<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Invoices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'code_invoice' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'discount' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Belum Bayar', 'Lunas', 'DP'],
                'default' => 'Belum Bayar',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'who_created' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('invoices');
        $this->forge->addForeignKey('who_created', 'users', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
         $this->forge->dropTable('invoices', true);
    }
}

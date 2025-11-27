<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSymptomHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'symptom' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
            ],
            'frequency' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'Number of times this symptom has been used',
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
        $this->forge->addKey('frequency');
        $this->forge->createTable('symptom_history');

        // Insert common symptoms for ASIC miners
        $symptoms = [
            ['symptom' => 'Hashboard not detected', 'frequency' => 10],
            ['symptom' => 'Fan not spinning', 'frequency' => 8],
            ['symptom' => 'No power / Won\'t turn on', 'frequency' => 7],
            ['symptom' => 'Low hash rate', 'frequency' => 6],
            ['symptom' => 'Overheating', 'frequency' => 5],
            ['symptom' => 'ASIC chip failure', 'frequency' => 5],
            ['symptom' => 'Network connection issue', 'frequency' => 4],
            ['symptom' => 'PSU failure', 'frequency' => 4],
            ['symptom' => 'Control board error', 'frequency' => 3],
            ['symptom' => 'Temperature sensor error', 'frequency' => 3],
            ['symptom' => 'Voltage regulator issue', 'frequency' => 2],
            ['symptom' => 'Firmware update required', 'frequency' => 2],
        ];

        foreach ($symptoms as $symptom) {
            $symptom['created_at'] = date('Y-m-d H:i:s');
            $symptom['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('symptom_history')->insert($symptom);
        }
    }

    public function down()
    {
        $this->forge->dropTable('symptom_history');
    }
}


<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRootUser extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $table = $this->table('users');
        $table->insert([
            "name" => "root",
            "email" => "foo@bar.com",
            "password" => "$2y$10\$VAi8SFjdFoYZmqA5aoiCyuj/5aL6jplt0bQI/GOoQCOIy2UM3c6lK",
            "role" => 1
        ]);
        $table->saveData();
    }
}

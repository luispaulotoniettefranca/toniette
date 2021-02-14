<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateApiTokensTable extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('api_tokens');
        $table->addColumn('user', 'integer')
            ->addForeignKey('user', 'users')
            ->addColumn('token', 'string')
            ->addColumn('maturity', 'datetime')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['token'],['unique' => true])
            ->create();
    }
}

<?php
use Phinx\Migration\AbstractMigration;

class CreateProductsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('products');
        $table->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('description', 'text')
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('category_id', 'integer', ['signed' => false])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->create();
    }
}





<?php
namespace App;

use App\Contracts\ModelBehavior;
use App\Helpers\QueryBuilder;
use PDO;
use Exception;

class Model extends Database implements ModelBehavior
{
    // Intentionally default for testing purpose.
    protected $table = 'users';
    protected $properties = [];
    private $database;

    public function __construct()
    {
        $this->database = parent::__construct();
        $this->queryBuilder = new QueryBuilder();

        $this->setAttributes();
        $this->getAttributes();
    }

    private function getAttributes()
    {
        $this->properties = $this->columnsIn($this->table);
    }

    private function setAttributes(array $items = [])
    {
        foreach ($items as $properKey => $propertyValue) {
            $this->$properKey = $propertyValue;
        }
    }

    public function get(array $columns = ['*'])
    {
        $columns = $this->queryBuilder->prepareForSelect($columns);

        $query = $this->database->query("
            SELECT {$columns} FROM {$this->table}
        ");

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOne(array $columns = ['*'])
    {
        $records = $this->get();
        $recordKey = array_rand($records);
        return $records[$recordKey];
    }


    public function create(array $items) :int
    {
       $query = $this->queryBuilder->prepareForInsert($items);

       $this->database->query("
                INSERT INTO {$this->table} ( {$query['columns']} )
                VALUES ( {$query['values']} )
        ");

       return (int) $this->database->lastInsertId();
    }

    public function find(int $id, array $columns = ['*'])
    {

        $columns = $this->queryBuilder->prepareForSelect($columns);

        $statement = $this->database->query("
            SELECT {$columns}
            FROM {$this->table}
            WHERE id = '{$id}'
        ");

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ( !$result ) {
            throw new Exception('Can\'t find the record with id ' . $id);
        }

        $this->setAttributes($result);

        return $this;
    }

    public function update()
    {
        $statement = null;

        // Collect all properties of the model sub class.
        foreach ($this->properties as $iteration => $property) {

            /* Do not add some whitespace in , (comma) character below of conditional statement if you did, change the format in the rtrim method at the end of this loop. */
            if ( $property !== 'id' )
                $statement .= "`${property}` = '{$this->$property}',";
        }

        $statement = rtrim($statement, ',');

        $statement .= " WHERE id = {$this->id} ";

        $executed = $this->database->query("UPDATE {$this->table} SET {$statement} ");

        return (bool) $executed->rowCount() > 0;
    }

    public function delete()
    {
        $query = $this->database->query("
            DELETE FROM {$this->table} WHERE id = {$this->id}
        ");
        return (bool) $query->rowCount() > 0;
    }

}
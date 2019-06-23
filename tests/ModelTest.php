<?php
namespace Tests;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use App\Model;


class ModelTest extends TestCase
{
    public function setUp()
    {
        $this->model = new Model();
    }

    public function tearDown()
    {
        unset($this->model);
    }

    /**
     * @test
     */
    public function parentMustBeDatabase()
    {
        $this->assertSame('App\Database', get_parent_class($this->model));
    }

    /**
     * @test
     */
    public function itHasTableProperty()
    {
        $this->assertClassHasAttribute('table',Model::class);
    }

    /**
     * @test
     */
    public function TablePropertyMustBeProtected()
    {
        $table = new ReflectionProperty($this->model, 'table');
        $this->assertSame(true, $table->isProtected());
    }

    /**
     * @test
     */
    public function itHasPropertiesProperty()
    {
        $this->assertClassHasAttribute('properties',Model::class);
    }

    /**
     * @test
     */
    public function propertiesPropertyMustBeProtected()
    {
        $properties = new ReflectionProperty($this->model, 'properties');
        $this->assertSame(true, $properties->isProtected());
    }

    /**
     * @test
     */
    public function isHasDatabaseProperty()
    {
        $this->assertClassHasAttribute('database',Model::class);
    }

    /**
     * @test
     */
    public function itCanGetProperties()
    {
        $table = 'users';

        $expected = ['id', 'username', 'password', 'fullname'];

       $model = $this->getMockBuilder('App\Model')
            ->setMethods(['columnsIn'])
            ->getMock();

       $model->expects($this->once())
             ->method('columnsIn')
             ->with($table)
             ->will($this->returnValue($expected));

        $model->columnsIn($table);

        $method = new ReflectionMethod($this->model, 'getAttributes');
        $method->setAccessible(true);
        $method->invoke($this->model);

        $properties = new ReflectionProperty($this->model, 'properties');
        $properties->setAccessible(true);

        $this->assertSame(
            $expected,
            $properties->getValue($this->model)
        );

    }

    /**
     * @test
     */
    public function itCanSetProperties()
    {

        $input = [
            'username' => 'tooshort06',
            'password' => 1234,
            'fullname' => 'Christopher'
        ];

        $method = new ReflectionMethod($this->model, 'setAttributes');
        $method->setAccessible(true);
        $method->invokeArgs($this->model, [$input]);
        $method->invoke($this->model);

        $this->assertSame($this->model->username, $input['username']);
        $this->assertSame($this->model->password, $input['password']);
        $this->assertSame($this->model->fullname, $input['fullname']);

    }


    /**
     * @test
     */
    public function itCanCreateARecord()
    {
       $input = [
            'username' => 'admin2',
            'password' => 1234,
            'fullname' => 'Christopher Vistal2'
        ];

       $queryBuilder = $this->getMockBuilder('App\Helpers\QueryBuilder')
            ->setMethods(['prepareForInsert'])
            ->getMock();

       $queryBuilder->expects($this->once())
             ->method('prepareForInsert');

        $queryBuilder->prepareForInsert($input);

        $this->assertInternalType('int', $this->model->create($input));

    }

    /**
     * @test
     */
    public function itCanGetRecords()
    {
        $records = $this->model->get();
        $this->assertNotEmpty($records);
    }

    /**
     * @test
     */
    public function itCanGetOnlyOneRecord()
    {
        $record = $this->model->getOne();
        $this->assertNotEmpty($record);
    }

    /**
     * @test
     */
    public function itCanUpdateARecord()
    {
        $user = $this->model->getOne(['id']);

        $this->model->id = $user->id;
        $this->model->username = 'admin1';
        $this->model->password = '1234';
        $this->model->fullname = 'Christopher Vistal1';

        $this->assertSame(
            true,
            $this->model->update(),
            "Can't find the record that you want to update."
        );
    }




    /**
     * @test
     */
    public function itCanFindARecord()
    {
        $user = $this->model->getOne(['id']);

        $this->model->find($user->id);

        $this->assertNotNull($this->model->username);
        $this->assertNotNull($this->model->password);
        $this->assertNotNull($this->model->fullname);

    }


    /**
     * @test
     */
    public function itCanDeleteRecord()
    {
        $user = $this->model->getOne(['id']);
        $record = $this->model->find($user->id);
        $this->assertSame(
            true,
            $record->delete(),
            "Can't find the record that you want to delete."
        );
    }

}
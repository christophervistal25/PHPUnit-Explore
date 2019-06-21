<?php
namespace Tests;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use PDO;
use App\Database;
use ReflectionMethod;


class DatabaseTest extends TestCase
{
    public function setUp()
    {
        $this->database = new Database();
    }

    public function tearDown()
    {
        unset($this->database);
    }

    /**
     * @test
     */
    public function databaseConnection()
    {
        $method = new ReflectionMethod($this->database, 'connect');
        $method->setAccessible(true);
        $this->assertInstanceOf('PDO' , $method->invoke($this->database));
    }

}
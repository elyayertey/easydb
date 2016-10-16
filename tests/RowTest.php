<?php
namespace ParagonIE\EasyDB\Tests;

use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;
use PDO;
use PDOException;

class RowTest
    extends
        SafeQueryTest
{


    protected function getResultForMethod(EasyDB $db, $statement, $offset, $params)
    {
        $args = $params;
        array_unshift($args, $statement);

        return call_user_func_array([$db, 'row'], $args);
    }

    /**
    * @dataProvider GoodColArgumentsProvider
    */
    public function testMethod(callable $cb, $statement, $offset, $params, $expectedResult)
    {
        $db = $cb();
        $this->assertInstanceOf(EasyDB::class, $db);

        $result = $this->getResultForMethod($db, $statement, $offset, $params);

        $this->assertEquals(array_diff_assoc($result, $expectedResult[0]), []);
    }
}

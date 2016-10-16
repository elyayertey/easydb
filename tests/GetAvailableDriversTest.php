<?php
namespace ParagonIE\EasyDB\Tests;

use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;
use PDO;
use PDOException;

class GetAvailableDriversTest
    extends
        EasyDBTest
{

    /**
    * @dataProvider GoodFactoryCreateArgumentProvider
    */
    public function testGetAvailableDrivers($dsn, $username=null, $password=null, $options = array(), $expectedDriver)
    {
        if (count(PDO::getAvailableDrivers()) < 1) {
            $this->markTestSkipped('No drivers available!');
        } else {
            $db = Factory::create($dsn, $username, $password, $options);
            $this->assertEquals(
                count(
                    array_diff_assoc(
                        PDO::getAvailableDrivers(),
                        $db->getAvailableDrivers()
                    )
                ),
                0
            );
            $this->assertInstanceOf(PDO::class, $db->getPdo());
            $this->assertEquals(
                count(
                    array_diff_assoc(
                        PDO::getAvailableDrivers(),
                        $db->getPdo()->getAvailableDrivers()
                    )
                ),
                0
            );
            $this->assertEquals(
                count(
                    array_diff_assoc(
                        $db->getAvailableDrivers(),
                        $db->getPdo()->getAvailableDrivers()
                    )
                ),
                0
            );
        }
    }
}

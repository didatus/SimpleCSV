<?php

use Didatus\SimpleCSV\SimpleCSV;

/**
 * @coversDefaultClass \Didatus\SimpleCSV\SimpleCSV
 * @covers \Didatus\SimpleCSV\SimpleCSV
 */
class DuplicateCheckTest extends PHPUnit_Framework_TestCase {
    function testConstruct()
    {
        $duplicateCheck = new SimpleCSV();

        $this->assertTrue(is_object($duplicateCheck));
    }
}

<?php

use Didatus\SimpleCSV\SimpleCSV;

/**
 * @coversDefaultClass \Didatus\SimpleCSV\SimpleCSV
 * @covers \Didatus\SimpleCSV\SimpleCSV
 */
class SimpleCSVTest extends PHPUnit_Framework_TestCase {
    function testCommaDelimiter()
    {
        $csv = new SimpleCSV("tests/csv/default1.csv");
        $data = $csv->getData();

        $this->assertCount(2, $data);
        $this->assertCount(3, $data[0]);
        $this->assertArrayHasKey('country', $data[0]);
        $this->assertArrayHasKey('city', $data[0]);
        $this->assertArrayHasKey('zip', $data[0]);
        $this->assertEquals('Germany', $data[0]['country']);
        $this->assertEquals('Cologne', $data[0]['city']);
        $this->assertEquals('50667', $data[0]['zip']);
        $this->assertCount(3, $data[1]);
        $this->assertArrayHasKey('country', $data[1]);
        $this->assertArrayHasKey('city', $data[1]);
        $this->assertArrayHasKey('zip', $data[1]);
        $this->assertEquals('Netherland', $data[1]['country']);
        $this->assertEquals('Maastricht', $data[1]['city']);
        $this->assertEquals('6211', $data[1]['zip']);
    }

    function testTabDelimiter()
    {
        $csv = new SimpleCSV("tests/csv/default2.csv");
        $data = $csv->getData();

        $this->assertCount(2, $data);
        $this->assertCount(3, $data[0]);
        $this->assertArrayHasKey('country', $data[0]);
        $this->assertArrayHasKey('city', $data[0]);
        $this->assertArrayHasKey('zip', $data[0]);
        $this->assertEquals('Germany', $data[0]['country']);
        $this->assertEquals('Cologne', $data[0]['city']);
        $this->assertEquals('50667', $data[0]['zip']);
        $this->assertCount(3, $data[1]);
        $this->assertArrayHasKey('country', $data[1]);
        $this->assertArrayHasKey('city', $data[1]);
        $this->assertArrayHasKey('zip', $data[1]);
        $this->assertEquals('Netherland', $data[1]['country']);
        $this->assertEquals('Maastricht', $data[1]['city']);
        $this->assertEquals('6211', $data[1]['zip']);
    }
}

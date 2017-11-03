<?php

use Didatus\SimpleCSV\DelimiterNotFoundException;
use Didatus\SimpleCSV\SimpleCSV;

/**
 * @coversDefaultClass \Didatus\SimpleCSV\SimpleCSV
 * @covers \Didatus\SimpleCSV\SimpleCSV
 */
class SimpleCSVTest extends PHPUnit_Framework_TestCase {
    /**
     *
     */
    function testDefaultWithSemicolon()
    {
        $csv = new SimpleCSV("tests/csv/default_semicolon.csv");
        $data = $csv->getData();

        $dummy = require "csv/default.php";

        $this->assertEquals($dummy, $data);
    }

    /**
     *
     */
    function testDefaultWithTab()
    {
        $csv = new SimpleCSV("tests/csv/default_tab.csv");
        $data = $csv->getData();

        $dummy = require "csv/default.php";

        $this->assertEquals($dummy, $data);
    }

    /**
     *
     */
    function testDefaultWithTabAndDoubleQuotes()
    {
        $csv = new SimpleCSV("tests/csv/default_semicolon_double_quote.csv");
        $data = $csv->getData();

        $dummy = require "csv/default.php";

        $this->assertEquals($dummy, $data);
    }

    /**
     *
     */
    function testWithoutHeader()
    {
        $csv = new SimpleCSV("tests/csv/without_header.csv", false);
        $data = $csv->getData();

        $dummy = require "csv/without_header.php";

        $this->assertEquals($dummy, $data);
    }

    /**
     * @expectedException Didatus\SimpleCSV\DelimiterNotFoundException
     * @expectedExceptionMessage No suitable delimiter found!
     */
    function testMissingDelimiter()
    {
        $csv = new SimpleCSV("tests/csv/missing_delimiter.csv");
    }

    /**
     * @expectedException Didatus\SimpleCSV\DelimiterNotFoundException
     * @expectedExceptionMessage no unique delimiter found
     */
    function testMultipleDelimiter()
    {
        $csv = new SimpleCSV("tests/csv/multiple_delimiter.csv");
    }
}

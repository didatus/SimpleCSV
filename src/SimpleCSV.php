<?php

namespace Didatus\SimpleCSV;

/**
 * Class SimpleCSV
 * @package Didatus\SimpleCSV
 */
class SimpleCSV {
    private $raw_data;

    private $data;

    private $header;

    private $preferredDelimiter = [',', ';', '|', '#', '*', '%', "\t", ' '];

    private $delimiter;

    public function __construct($filename, $header = true)
    {
        $this->raw_data = file($filename);
        $this->defineHeader($header);
        $this->handleData();
    }

    /**
     * @return mixed
     */
    public function getData($offset = false, $row_count = false)
    {
        if ($offset === false && $row_count === false) {
            return $this->data;
        }
    }

    /**
     * @return string
     */
    private function getDelimiter()
    {
        if (is_null($this->delimiter)) {
            $this->delimiter = $this->determineDelimiter();
        }

        return $this->delimiter;
    }

    /**
     *
     */
    private function handleData():void
    {
        foreach ($this->raw_data as $row) {
            $record = $this->handleRow($row);
            $this->data[] = array_combine($this->header, $record);
        }
    }

    /**
     * @param $row
     * @return array
     */
    private function handleRow($row)
    {
        $delimiter = $this->getDelimiter();
        $enclosure = $this->determineEnclosure($row);
        $escape = $this->determineEscape($row, $enclosure);
        return str_getcsv($row, $delimiter, $enclosure, $escape);
    }

    /**
     * @param $named_header
     */
    private function defineHeader($named_header):void
    {
        if ($named_header) {
            $this->header = $this->handleRow($this->raw_data[0]);
            array_shift($this->raw_data);
        } else {
            $this->header = range(1, count($this->raw_data[0]));
        }
    }

    /**
     * @return string
     */
    private function determineDelimiter():string
    {
        $char_count = $this->countCharactersPerRow();

        $possible_delimiter = [];
        foreach ($char_count[0] as $chr => $count)
        {
            if ($this->checkCharacterForConstantOccurence($char_count, $chr, $count)) {
                $possible_delimiter[] = chr($chr);
            }
        }

        return $this->handlePossibleDelimiters($possible_delimiter);
    }

    /**
     * @return array
     */
    private function countCharactersPerRow():array
    {
        $char_count = [];

        foreach ($this->raw_data as $row)
        {
            $char_count[] = count_chars($row, 1);
        }

        return $char_count;
    }

    /**
     * @param array $char_count
     * @param string $chr
     * @param int $count
     * @return string
     */
    private function checkCharacterForConstantOccurence(array $char_count, string $chr, int $count): string
    {
        $check = null;
        foreach ($char_count as $row_count) {
            if (!isset($row_count[$chr]) || $row_count[$chr] != $count) {
                $check = false;
                break;
            }

            $check = true;
        }

        return $check;
    }

    /**
     * @param $possible_delimiter
     * @return string
     * @throws DelimiterNotFoundException
     */
    private function handlePossibleDelimiters($possible_delimiter):string
    {
        if (empty($possible_delimiter)) {
            throw new DelimiterNotFoundException("No suitable delimiter found!");
        }

        if (count($possible_delimiter) == 1) {
            return $possible_delimiter[0];
        }

        $result = array_intersect($this->preferredDelimiter, $possible_delimiter);
        if (count($result) == 1) {
            $key = key($result);
            return $result[$key];
        }

        throw new DelimiterNotFoundException("no unique delimiter found");
    }

    /**
     * @param $row
     * @return string
     */
    private function determineEnclosure($row):string
    {
        $delimiter_surrounding_characters = $this->getDelimiterSurroundingCharacters($row);
        $possible_enclosure = array_unique($delimiter_surrounding_characters);

        if (count($possible_enclosure) == 1) {
            $key = key($possible_enclosure);
            return $possible_enclosure[$key];
        }

        return '';
    }

    /**
     * @param $row
     * @return array
     */
    private function getDelimiterSurroundingCharacters($row)
    {
        $delimiter = $this->getDelimiter();
        $delimiter_cleaned_row = preg_replace("/" . preg_quote($delimiter . "+", '/') . "/", $delimiter, $row);
        preg_match_all("/" . preg_quote("(.)" . $delimiter . "(.)", '/') . "/", $delimiter_cleaned_row, $matches);
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($matches));
        return iterator_to_array($iterator, false);
    }

    /**
     * @param $row
     * @param $enclosure
     * @return string
     */
    private function determineEscape($row, $enclosure)
    {
        $delimiter = $this->getDelimiter();
        $elements = explode($delimiter, $row);

        $elements = array_map(function($element) use ($enclosure) {
            return trim($element, $enclosure);
        }, $elements);

        $possible_escape = [];
        foreach ($elements as $element) {
            $result  = $this->getEnclosureLeadingCharacters($element, $enclosure);
            $possible_escape = array_merge($possible_escape, $result);
        }
        $possible_escape = array_unique($possible_escape);

        if (count($possible_escape) == 1) {
            $key = key($possible_escape);
            return $possible_escape[$key];
        }

        return '';
    }

    /**
     * @param $element
     * @param $enclosure
     * @return array|\RecursiveIteratorIterator
     */
    private function getEnclosureLeadingCharacters($element, $enclosure)
    {
        preg_match_all("/" . preg_quote("(.)" . $enclosure, '/') . "/", $element, $matches);
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($matches));
        return iterator_to_array($iterator, false);
    }
}

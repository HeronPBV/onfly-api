<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{

    public function test_format_date()
    {
        $date = '2023-09-22';
        $formattedDate = formatDate($date);

        $this->assertEquals('22/09/2023', $formattedDate);
    }


    public function test_format_value()
    {
        $value = 1234.56;
        $formattedValue = formatValue($value);

        $this->assertEquals('R$ 1.234,56', $formattedValue);
    }


    public function test_format_value_zero()
    {
        $value = 0;
        $formattedValue = formatValue($value);

        $this->assertEquals('R$ 0,00', $formattedValue);
    }


    public function test_format_value_negative()
    {
        $value = -1234.56;
        $formattedValue = formatValue($value);

        $this->assertEquals('R$ -1.234,56', $formattedValue);
    }

}

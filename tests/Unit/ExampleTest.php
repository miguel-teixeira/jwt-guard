<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vendor\Package\Tests\PackageTestCase;

class ExampleTest extends PackageTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $s = DB::select("select 'test'");

        LOG::info('test');

        $this->assertTrue(true);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: over
 * Date: 22/03/2018
 * Time: 1:40
 */

use Overdesign\CifGenerator\Generator;

class Test extends PHPUnit_Framework_TestCase
{

    public function testCif()
    {
        for ($i = 1; $i <= 1000; $i++) {
            $cif = Generator::Cif();
            $this->assertTrue(IsoCodes\Cif::validate($cif), sprintf('CIF: %s is valid', $cif));
        }

    }

    public function testGenerateCif()
    {
        $cifGenerator = new Generator();

        for ($i = 1; $i <= 1000; $i++) {
            $cif = $cifGenerator->generateCif();
            $this->assertTrue(IsoCodes\Cif::validate($cif), sprintf('CIF: %s is valid', $cif));
        }
    }
}

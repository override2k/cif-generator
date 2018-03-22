<?php
/**
 * Created by Fernando Robledo <overdesign@gmail.com>.
 */

use Overdesign\CifGenerator\Generator;

class GeneratorTest extends PHPUnit_Framework_TestCase
{

    public function testCif()
    {
        for ($i = 1; $i <= 1000; $i++) {
            $cif = Generator::Cif();
            $this->assertTrue(IsoCodes\Cif::validate($cif), sprintf('Generated CIF: %s is not valid', $cif));
        }

    }

    public function testGenerateCif()
    {
        $cifGenerator = new Generator();

        for ($i = 1; $i <= 1000; $i++) {
            $cif = $cifGenerator->generateCif();
            $this->assertTrue(IsoCodes\Cif::validate($cif), sprintf('Generated CIF: %s is not valid', $cif));
        }
    }
}

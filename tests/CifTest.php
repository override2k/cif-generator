<?php
/**
 * Created by Fernando Robledo <overdesign@gmail.com>.
 */

use Overdesign\CifGenerator\Cif;

/**
 * @covers Overdesign\CifGenerator\Cif
 */
class CifTest extends PHPUnit_Framework_TestCase
{
    public function testCif()
    {
        for ($i = 1; $i <= 1000; $i++) {
            $cif = Cif::generate();
            $this->assertTrue(IsoCodes\Cif::validate($cif), sprintf('Generated CIF: %s is not valid', $cif));
        }
    }

    public function testGenerateCif()
    {
        $cifGenerator = new Cif();

        for ($i = 1; $i <= 1000; $i++) {
            $cif = $cifGenerator->get();
            $this->assertTrue(IsoCodes\Cif::validate($cif), sprintf('Generated CIF: %s is not valid', $cif));
        }
    }
}

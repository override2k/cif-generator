<?php
/**
 * Created by Fernando Robledo <overdesign@gmail.com>.
 */

namespace Overdesign\CifGenerator;

/**
 * Class Cif
 *
 * @package Overdesign\CifGenerator
 * @see     https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
 */
class Cif
{
    const CHECKSUM_DIGIT_LETTER = 0;
    const CHECKSUM_DIGIT_NUMBER = 1;

    public const SOCIETY_TYPE = [
        'A', //  Sociedades anónimas.
        'B', //  Sociedades de responsabilidad limitada.
        'C', //  Sociedades colectivas.
        'D', //  Sociedades comanditarias.
        'E', //  Comunidades de bienes.
        'F', //  Sociedades cooperativas.
        'G', //  Asociaciones y fundaciones.
        'H', //  Comunidades de propietarios en régimen de propiedad horizontal.
        'J', //  Sociedades civiles.
        'N', //  Entidades no residentes.
        'P', //  Corporaciones locales.
        'Q', //  Organismos autónomos', 'estatales o no', 'y asimilados', 'y congregaciones e instituciones religiosas.
        'R', //  Congregaciones e instituciones religiosas (desde 2008', 'ORDEN EHA/451/2008)
        'S', //  Órganos de la Administración del Estado y comunidades autónomas
        'U', //  Uniones Temporales de Empresas.
        'V', //  Sociedad Agraria de Transformación.
        'W', //  Establecimientos permanentes de entidades no residentes en España
    ];

    public const PROVINCES = [
        '00', //  No Residente
        '01', // Álava
        '02', // Albacete
        '03', '53', '54', // Alicante
        '04', // Almería
        '05', // Ávila
        '06', // Badajoz
        '07', '57', '16', // Islas Baleares
        '08', '58', '59', '60', '61', '62', '63', '64', '65', '66', '68', // Barcelona
        '09', // Burgos
        '10', // Cáceres
        '11', '72', // Cádiz
        '12', // Castellón
        '13', // Ciudad Real
        '14', '56', // Córdoba
        '15', '70', // La Coruña
        '16', // Cuenca
        '17', '55', '67', // Gerona
        '18', '19', // Granada
        '19', // Guadalajara
        '20', '71', // Guipúzcoa
        '21', // Huelva
        '22', // Huesca
        '23', // Jaén
        '24', // León
        '25', // Lérida
        '26', // La Rioja
        '27', // Lugo
        '28', '78', '79', '80', '81', '82', '83', '84', '85', '86', '87', // Madrid
        '29', '92', '93', // Málaga
        '30', '73', // Murcia
        '31', '71', // Navarra
        '32', // Orense
        '33', '74', // Asturias
        '34', // Palencia
        '35', '76', // Las Palmas
        '36', '94', '27', // Pontevedra
        '37', // Salamanca
        '38', '75', // Santa Cruz de Tenerife
        '39', // Cantabria
        '40', // Segovia
        '41', '90', '91', // Sevilla
        '42', // Soria
        '43', '77', // Tarragona
        '44', // Teruel
        '45', // Toledo
        '46', '96', '97', '98', // Valencia
        '47', // Valladolid
        '48', '95', // Vizcaya
        '49', // Zamora
        '50', '99', // Zaragoza
        '51', // Ceuta
        '52', // Melilla
    ];

    protected const CHECKSUM_DIGITS = 'JABCDEFGHI';

    /**
     * Generates a valid CIF number
     *
     * @return string a random CIF number
     */
    public static function generate(): string
    {
        return (new self)->get();
    }

    /**
     * Generates a valid CIF number
     *
     * @return string a random CIF number
     */
    public function get(): string
    {
        $societyType = $this->getSocietyType();
        $province    = $this->getProvince();
        $inscription = $this->getInscriptionNumber();
        $digitType   = $this->getControlDigitType($societyType, $province);
        $digit       = $this->getControlDigit($province . $inscription, $digitType);

        return $societyType . $province . $inscription . $digit;
    }

    /**
     * @return string random society type digit
     */
    private function getSocietyType(): string
    {
        return self::SOCIETY_TYPE[array_rand(self::SOCIETY_TYPE)];
    }

    /**
     * @return string random province number digit
     */
    private function getProvince(): string
    {
        return self::PROVINCES[array_rand(self::PROVINCES)];
    }

    /**
     * @return string a random five numeric digits string
     */
    private function getInscriptionNumber(): string
    {
        return str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param string $digits a seven digits string (province + inscription number)
     * @param int $digitType self::CHECKSUM_DIGIT_NUMBER or self::CHECKSUM_DIGIT_LETTER indicates the checksum digit type
     *
     * @return string control digit
     */
    private function getControlDigit(string $digits, int $digitType): string
    {
        $evenSum = $this->getEvenChecksum($digits);
        $oddSum  = $this->getOddChecksum($digits);

        $digit = (int)substr($evenSum + $oddSum, -1);
        $digit = $digit > 0 ? 10 - $digit : $digit;

        if ($digitType === self::CHECKSUM_DIGIT_NUMBER) {
            return (string)$digit;
        }

        return self::CHECKSUM_DIGITS[$digit];
    }

    /**
     * @param string $societyType one of self::SOCIETY_TYPE, indicates CIF society type
     * @param string $province two digit province id
     *
     * @return int with self::CHECKSUM_DIGIT_NUMBER or self::CHECKSUM_DIGIT_LETTER, indicates the checksum digit type
     */
    private function getControlDigitType(string $societyType, string $province): int
    {
        if (in_array($societyType, ['A', 'B', 'E', 'H'], true)) {
            return self::CHECKSUM_DIGIT_NUMBER;
        }
        if ($province === '00' || in_array($societyType, ['P', 'Q', 'S', 'W'], true)) {
            return self::CHECKSUM_DIGIT_LETTER;
        }

        return mt_rand(0, 1) ? self::CHECKSUM_DIGIT_LETTER : self::CHECKSUM_DIGIT_NUMBER;
    }

    /**
     * This checksum is calculated with the sum of all the digits in even position (1 based index  (╯°□°）╯)
     *
     * @param string $digits seven digit string  (province + inscription number)
     *
     * @return int even digits checksum
     */
    private function getEvenChecksum(string $digits): int
    {
        $checksum = 0;
        foreach (range(1, strlen($digits) - 1, 2) as $digit) {
            $checksum += (int)$digits[$digit];
        }

        return $checksum;
    }

    /**
     * This checksum is calculated with the sum of all digits in odd position, multiplying them *2 and adding the last
     * digit of the multiplication result
     *
     * @param string $digits seven digit string  (province + inscription number)
     *
     * @return int odd digits checksum
     */
    private function getOddChecksum(string $digits): int
    {
        $checksum = 0;
        foreach (range(0, strlen($digits) - 1, 2) as $digit) {
            $checksum += array_sum(str_split((int)$digits[$digit] * 2));
        }

        return $checksum;
    }
}

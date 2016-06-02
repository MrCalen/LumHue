<?php

namespace App\Helpers;

use App\Models\RGB;

use MischiefCollective\ColorJizz\Formats\Yxy;

class XY {
    public $x;
    public $y;

    /**
     * XY constructor.
     * @param $x
     * @param $y
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function crossProduct($p1, $p2) {
        return ($p1->x * $p2->y - $p1->y * $p2->x);
    }
}

class LumHueColorConverter
{

    public static function RGBstrToRGB(string $str)
    {
        $matches = [];
        if (preg_match('/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/', $str, $matches)) {
            return array_slice($matches, 1);
        }

        return [
          255,
          255,
          255,
        ];
    }

    public static function RGBToChromaticRGB(RGB $rgb)
    {
        return LumHueColorConverter::RGBToChromatic(
            $rgb->getRed(),
            $rgb->getGreen(),
            $rgb->getBlue()
        );
    }

    public static function RGBtoChromatic($r, $g, $b)
    {
        $r = $r / 255;
        $g = $g / 255;
        $b = $b / 255;

        $r = ($r > 0.04055 ? pow(($r + 0.055) / 1.055, 2.4) : ($r / 12.92));
        $g = ($g > 0.04055 ? pow(($g + 0.055) / 1.055, 2.4) : ($g / 12.92));
        $b = ($b > 0.04055 ? pow(($b + 0.055) / 1.055, 2.4) : ($b / 12.92));

        $X = $r * 0.649926 + $g * 0.103455 + $b * 0.197109;
        $Y = $r * 0.234327 + $g * 0.743075 + $b * 0.022598;
        $Z = $r * 0        + $g * 0.053077 + $b * 1.035763;

        if (($X + $Y + $Z) == 0) {
            $x = 0;
            $y = 0;
        } else {
            $x = round($X / ($X + $Y + $Z), 10);
            $y = round($Y / ($X + $Y + $Z), 10);
        }

        $bri = round($Y * 254);
        if ($bri > 254) {
            $bri = 254;
        }

        return [
            'x' => $x,
            'y' => $y,
            'bri' => $Y,
        ];
    }

    public static function RGBToHex($r, $g, $b)
    {
        $hex = "#";
        $hex .= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return $hex;
    }

    public static function chromaticToRGB($x, $y, $bri) {
        return self::getRGBFromXYState($x, $y, $bri);
    }


    private static function getRGBFromXYState($x, $y, $brightness) {
        $Y = $brightness;
        $X = ($Y / $y) * $x;
        $Z = ($Y / $y) * (1 - $x - $y);
        $rgb = [
            $X * 1.612 - $Y * 0.203 - $Z * 0.302,
            -$X * 0.509 + $Y * 1.412 + $Z * 0.066,
            $X * 0.026 - $Y * 0.072 + $Z * 0.962
        ];

        $rgb = array_map(function ($x) {
            return ($x <= 0.0031308) ? (12.92 * $x) : ((1.0 + 0.055) * pow($x, (1.0 / 2.4)) - 0.055);
        }, $rgb);

        $rgb = array_map(function ($x) { return max(0, $x); }, $rgb);
        $max = max($rgb[0], $rgb[1], $rgb[2]);
        if ($max > 1)
            $rgb = array_map(function ($x) use($max) { return $x / $max; }, $rgb);

        $tmp = array_map(function ($x) { return $x * 255;}, $rgb);
        return [
            'r' => $tmp[0],
            'g' => $tmp[1],
            'b' => $tmp[2]
        ];
    }
}

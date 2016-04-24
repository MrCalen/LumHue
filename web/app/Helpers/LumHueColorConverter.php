<?php

namespace App\Helpers;

use App\Models\RGB;

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
            $x = round($X / ($X + $Y + $Z), 4);
            $y = round($Y / ($X + $Y + $Z), 4);
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

    public static function chromaticToRGB($x, $y, $bri)
    {
        $z = 1 - $x - $y;
        $Y = $bri / 254;

        if ($y == 0) {
            $X = 0;
            $Z = 0;
        } else {
            $X = ($Y / $y) * $x;
            $Z = ($Y / $y) * $z;
        }

        $r = $X * 3.2406 - $Y * 1.5372 - $Z * 0.4986;
        $g = - $X * 0.9689 + $Y * 1.8758 + $Z * 0.0415;
        $b = $X * 0.0557 - $Y * 0.204 + $Z * 1.057;

        $r = ($r <= 0.0031308 ? 12.92 * $r : (1.055) * pow($r, (1 / 2.4)) - 0.055);
        $g = ($g <= 0.0031308 ? 12.92 * $g : (1.055) * pow($g, (1 / 2.4)) - 0.055);
        $b = ($b <= 0.0031308 ? 12.92 * $b : (1.055) * pow($b, (1 / 2.4)) - 0.055);

        $r = ($r < 0 ? 0 : round($r * 255));
        $g = ($g < 0 ? 0 : round($g * 255));
        $b = ($b < 0 ? 0 : round($b * 255));
        $r = ($r > 255 ? 255 : $r);
        $g = ($g > 255 ? 255 : $g);
        $b = ($b > 255 ? 255 : $b);

        return [
            'r' => $r,
            'g' => $g,
            'b' => $b,
        ];
    }
}

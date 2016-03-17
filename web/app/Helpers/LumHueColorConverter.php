<?php

namespace App\Helpers;

use App\Models\RGB;

class LumHueColorConverter
{

    public function RGBToChromaticRGB(RGB $rgb)
    {
        return $this->RGBToChromatic(
            $rgb->getRed(),
            $rgb->getGreen(),
            $rgb->getBlue()
        );
    }

    public function RGBtoChromatic($r, $g, $b)
    {
        $X = 0.4124*$r + 0.3576*$g + 0.1805*$b;
        $Y = 0.2126*$r + 0.7152*$g + 0.0722*$b;
        $Z = 0.0193*$r + 0.1192*$g + 0.9505*$b;

        $div = ($X + $Y + $Z) === 0 ? 1 : ($X + $Y + $Z);

        $x = $X / $div;
        $y = $Y / $div;

        return [
            'x' => $x,
            'y' => $y,
        ];
    }

    public function chromaticToRGB($x, $y, $brightness)
    {
        $z = 1 - $x - $y;
        $Y = $brightness;
        $X = ($Y / $y) * $x;
        $Z = ($Y / $y) * $z;

        $r = $X * 1.612 - $Y * 0.203 - $Z * 0.302;
        $g = -$X * 0.509 + $Y * 1.412 + $Z * 0.066;
        $b = $X * 0.026 - $Y * 0.072 + $Z * 0.962;

        $r = $r <= 0.0031308 ? 12.92 * $r : (1 + 0.055) * pow($r, (1 / 2.4)) - 0.055;
        $g = $g <= 0.0031308 ? 12.92 * $g : (1 + 0.055) * pow($g, (1 / 2.4)) - 0.055;
        $b = $b <= 0.0031308 ? 12.92 * $b : (1 + 0.055) * pow($b, (1 / 2.4)) - 0.055;

        return [
            'r' => $r,
            'g' => $g,
            'b' => $b,
        ];
    }
}

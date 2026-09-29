<?php

namespace App\Database\Seeds;

/**
 * Pictures drawn with GD for the example site (VitrineSeeder): scout scenes (camp, forest, lake, evening
 * around the fire, group in scarves) and portraits. No download: the example site works offline.
 * The same seed always gives the same picture.
 */
class VitrineImages
{
    public const SCENES = ['camp', 'forest', 'lake', 'evening', 'group'];

    /**
     * Scene of the given kind, as a GD image.
     *
     * @param string $kind one of SCENES
     * @param string $scarf colour of the scarves (#RRGGBB)
     */
    public static function scene(string $kind, int $seed, int $width = 1600, int $height = 1067, string $scarf = '#16a34a')
    {
        mt_srand($seed);
        $image = imagecreatetruecolor($width, $height);
        $night = $kind === 'evening';
        $sunset = !$night && mt_rand(0, 3) === 0;

        // Sky and horizon
        $horizon = (int) ($height * ($kind === 'group' ? 0.55 : mt_rand(48, 62) / 100));
        if ($night)
            self::gradient($image, 0, 0, $width, $horizon, [16, 24, 58], [52, 44, 92]);
        elseif ($sunset)
            self::gradient($image, 0, 0, $width, $horizon, [255, 176, 110], [255, 226, 170]);
        else
            self::gradient($image, 0, 0, $width, $horizon, [110, 170, 230], [200, 228, 250]);

        if ($night) {
            for ($i = 0; $i < 160; $i++) {
                $size = mt_rand(1, 3);
                imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $horizon), $size, $size, self::color($image, [255, 255, 235]));
            }
            $x = mt_rand((int) ($width * 0.6), (int) ($width * 0.9));
            imagefilledellipse($image, $x, (int) ($height * 0.15), 90, 90, self::color($image, [250, 246, 220]));
            imagefilledellipse($image, $x + 26, (int) ($height * 0.15) - 12, 80, 80, self::color($image, [30, 34, 72]));
        } else {
            $sunX = mt_rand((int) ($width * 0.1), (int) ($width * 0.9));
            $sunY = $sunset ? $horizon - 60 : mt_rand((int) ($height * 0.08), (int) ($height * 0.22));
            imagefilledellipse($image, $sunX, $sunY, 150, 150, self::color($image, $sunset ? [255, 150, 90] : [255, 244, 200], 60));
            imagefilledellipse($image, $sunX, $sunY, 100, 100, self::color($image, $sunset ? [255, 120, 70] : [255, 250, 225]));
            for ($i = mt_rand(2, 5); $i > 0; $i--)
                self::cloud($image, mt_rand(0, $width), mt_rand(30, (int) ($horizon * 0.6)), mt_rand(120, 260));
        }

        // Mountains / hills in the distance, then the ground
        $far = $night ? [40, 46, 80] : ($sunset ? [170, 120, 140] : [140, 170, 200]);
        self::ridge($image, $horizon - mt_rand(120, 220), $horizon, $width, $height, $far, 90);
        $near = $night ? [24, 40, 40] : [72, 130, 80];
        self::ridge($image, $horizon - mt_rand(20, 70), $horizon, $width, $height, $near, 40);
        self::gradient($image, 0, $horizon, $width, $height, $night ? [22, 42, 30] : [110, 170, 80], $night ? [10, 24, 16] : [70, 128, 56]);

        switch ($kind) {
            case 'camp':
                self::trees($image, $width, $horizon, 14, 0.7, $night);
                for ($i = mt_rand(2, 4); $i > 0; $i--)
                    self::tent($image, mt_rand((int) ($width * 0.08), (int) ($width * 0.85)), mt_rand($horizon + 60, $height - 120), mt_rand(150, 240));
                self::fire($image, mt_rand((int) ($width * 0.3), (int) ($width * 0.7)), $height - mt_rand(80, 160), 1.0);
                break;
            case 'forest':
                self::trees($image, $width, $horizon, 26, 1.0, $night);
                $path = self::color($image, [176, 150, 110]);
                imagefilledpolygon($image, [(int) ($width * 0.46), $horizon + 10, (int) ($width * 0.54), $horizon + 10, (int) ($width * 0.75), $height, (int) ($width * 0.25), $height], $path);
                self::trees($image, $width, $horizon + 120, 8, 1.8, $night);
                break;
            case 'lake':
                self::gradient($image, 0, $horizon + 20, $width, (int) ($horizon + ($height - $horizon) * 0.7), [90, 150, 200], [60, 110, 170]);
                for ($i = 0; $i < 40; $i++) {
                    $y = mt_rand($horizon + 30, (int) ($horizon + ($height - $horizon) * 0.68));
                    $x = mt_rand(0, $width);
                    imagefilledrectangle($image, $x, $y, $x + mt_rand(40, 160), $y + 2, self::color($image, [220, 235, 250], 70));
                }
                self::canoe($image, mt_rand((int) ($width * 0.2), (int) ($width * 0.6)), $horizon + mt_rand(90, 180), $scarf);
                self::trees($image, $width, $horizon, 10, 0.6, $night);
                break;
            case 'evening':
                self::trees($image, $width, $horizon, 18, 0.8, true);
                $fireX = (int) ($width / 2);
                $fireY = $height - 170;
                for ($radius = 700; $radius > 0; $radius -= 70)
                    imagefilledellipse($image, $fireX, $fireY, $radius * 2, (int) ($radius * 1.1), self::color($image, [255, 150, 60], 118));
                // Circle around the fire: the ones at the back first (smaller), the fire in front of them
                for ($i = 0; $i < 8; $i++) {
                    $angle = M_PI * (0.08 + $i * 0.84 / 7);
                    self::person($image, (int) ($fireX + cos($angle) * 560), (int) ($fireY - sin($angle) * 120 + 30), 0.75 - sin($angle) * 0.1, [20, 16, 28], $scarf);
                }
                self::fire($image, $fireX, $fireY, 1.6);
                break;
            case 'group':
                self::trees($image, $width, $horizon, 12, 0.7, false);
                $count = mt_rand(6, 10);
                for ($i = 0; $i < $count; $i++) {
                    $x = (int) ($width * (0.1 + 0.8 * $i / max(1, $count - 1)));
                    $skin = [[241, 204, 170], [224, 172, 128], [176, 120, 86], [120, 80, 56]][mt_rand(0, 3)];
                    self::person($image, $x, $height - mt_rand(60, 110), mt_rand(95, 125) / 100, self::shirt(), $scarf, $skin);
                }
                break;
        }

        return $image;
    }

    /**
     * Portrait (head and shoulders) with the scarf of the section.
     */
    public static function portrait(int $seed, string $scarf, int $size = 600)
    {
        mt_srand($seed);
        $image = imagecreatetruecolor($size, $size);
        $backgrounds = [[[220, 232, 245], [180, 205, 230]], [[235, 228, 214], [210, 196, 170]], [[222, 240, 224], [180, 214, 186]], [[240, 226, 232], [214, 190, 200]]];
        [$top, $bottom] = $backgrounds[mt_rand(0, 3)];
        self::gradient($image, 0, 0, $size, $size, $top, $bottom);

        $skin = [[241, 204, 170], [224, 172, 128], [176, 120, 86], [120, 80, 56]][mt_rand(0, 3)];
        $hair = [[60, 40, 30], [140, 90, 50], [220, 180, 110], [30, 30, 34], [170, 70, 40]][mt_rand(0, 4)];
        $cx = (int) ($size / 2);

        // Shoulders (shirt of the uniform), neck, scarf
        $shirt = self::color($image, [[70, 90, 130], [150, 120, 80], [60, 110, 90]][mt_rand(0, 2)]);
        imagefilledellipse($image, $cx, (int) ($size * 1.08), (int) ($size * 0.95), (int) ($size * 0.7), $shirt);
        imagefilledrectangle($image, $cx - (int) ($size * 0.07), (int) ($size * 0.55), $cx + (int) ($size * 0.07), (int) ($size * 0.76), self::color($image, self::darker($skin, 0.9)));
        $scarfColor = self::color($image, self::rgb($scarf));
        imagefilledpolygon($image, [$cx - (int) ($size * 0.2), (int) ($size * 0.74), $cx + (int) ($size * 0.2), (int) ($size * 0.74), $cx, (int) ($size * 0.98)], $scarfColor);
        imagefilledellipse($image, $cx, (int) ($size * 0.8), (int) ($size * 0.09), (int) ($size * 0.07), self::color($image, self::darker(self::rgb($scarf), 0.7)));

        // Head, hair, eyes, smile
        $headW = (int) ($size * 0.36);
        $headH = (int) ($size * 0.44);
        $headY = (int) ($size * 0.42);
        imagefilledellipse($image, $cx, $headY - (int) ($size * 0.04), $headW + (int) ($size * 0.06), $headH + (int) ($size * 0.02), self::color($image, $hair));
        if (mt_rand(0, 1))
            imagefilledellipse($image, $cx, $headY + (int) ($size * 0.08), $headW + (int) ($size * 0.1), $headH, self::color($image, $hair));
        imagefilledellipse($image, $cx, $headY, $headW, $headH, self::color($image, $skin));
        imagefilledarc($image, $cx, $headY - (int) ($size * 0.1), $headW + 4, (int) ($headH * 0.6), 180, 360, self::color($image, $hair), IMG_ARC_PIE);
        $eye = self::color($image, [40, 36, 40]);
        imagefilledellipse($image, $cx - (int) ($size * 0.065), $headY + (int) ($size * 0.01), (int) ($size * 0.028), (int) ($size * 0.034), $eye);
        imagefilledellipse($image, $cx + (int) ($size * 0.065), $headY + (int) ($size * 0.01), (int) ($size * 0.028), (int) ($size * 0.034), $eye);
        imagesetthickness($image, max(2, (int) ($size / 150)));
        imagearc($image, $cx, $headY + (int) ($size * 0.07), (int) ($size * 0.12), (int) ($size * 0.07), 20, 160, self::color($image, self::darker($skin, 0.6)));
        imagesetthickness($image, 1);

        return $image;
    }

    /**
     * Saves a GD image as a JPEG file (temporary file for the helpers of the site).
     */
    public static function toTempJpeg($image): string
    {
        $path = tempnam(sys_get_temp_dir(), 'vitrine') . '.jpg';
        imagejpeg($image, $path, 88);
        imagedestroy($image);
        return $path;
    }

    // ------------------------------------------------------------------ drawing tools

    private static function color($image, array $rgb, int $alpha = 0)
    {
        return imagecolorallocatealpha($image, max(0, min(255, $rgb[0])), max(0, min(255, $rgb[1])), max(0, min(255, $rgb[2])), $alpha);
    }

    private static function rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    private static function darker(array $rgb, float $factor): array
    {
        return array_map(fn($value) => (int) ($value * $factor), $rgb);
    }

    private static function shirt(): array
    {
        return [[70, 90, 130], [150, 120, 80], [60, 110, 90], [120, 70, 60], [90, 90, 100]][mt_rand(0, 4)];
    }

    private static function gradient($image, int $x1, int $y1, int $x2, int $y2, array $from, array $to): void
    {
        $height = max(1, $y2 - $y1);
        for ($y = $y1; $y < $y2; $y++) {
            $ratio = ($y - $y1) / $height;
            $rgb = [];
            for ($i = 0; $i < 3; $i++)
                $rgb[] = (int) ($from[$i] + ($to[$i] - $from[$i]) * $ratio);
            imageline($image, $x1, $y, $x2, $y, self::color($image, $rgb));
        }
    }

    private static function cloud($image, int $x, int $y, int $size): void
    {
        $white = self::color($image, [255, 255, 255], 30);
        for ($i = 0; $i < 5; $i++)
            imagefilledellipse($image, $x + (int) (($i - 2) * $size * 0.3), $y + mt_rand(-15, 15), (int) ($size * 0.6), (int) ($size * 0.4), $white);
    }

    /**
     * Line of hills / mountains from $top to the bottom of the image.
     */
    private static function ridge($image, int $top, int $horizon, int $width, int $height, array $rgb, int $amplitude): void
    {
        $points = [0, $height];
        $y = $top + mt_rand(0, $amplitude);
        for ($x = 0; $x <= $width; $x += (int) ($width / 12)) {
            $y = max($top, min($horizon, $y + mt_rand(-$amplitude, $amplitude)));
            array_push($points, $x, $y);
        }
        array_push($points, $width, $height);
        imagefilledpolygon($image, $points, self::color($image, $rgb));
    }

    private static function trees($image, int $width, int $baseline, int $count, float $scale, bool $night): void
    {
        for ($i = 0; $i < $count; $i++) {
            $x = mt_rand(-40, $width + 40);
            $height = (int) (mt_rand(140, 260) * $scale);
            $y = $baseline + mt_rand(-10, 30);
            $green = $night ? [16, 34, 28] : [[40, 96, 60], [30, 80, 50], [52, 110, 66]][mt_rand(0, 2)];
            imagefilledrectangle($image, $x - (int) (6 * $scale), $y - (int) (20 * $scale), $x + (int) (6 * $scale), $y + (int) (10 * $scale), self::color($image, $night ? [20, 20, 20] : [100, 70, 40]));
            for ($level = 0; $level < 3; $level++) {
                $levelWidth = (int) ($height * (0.5 - $level * 0.1));
                $levelTop = $y - $height + (int) ($level * $height * 0.22);
                $levelBottom = $y - (int) ($height * 0.1) - (int) ($level * $height * 0.2);
                imagefilledpolygon($image, [$x, $levelTop, $x - (int) ($levelWidth / 2), $levelBottom, $x + (int) ($levelWidth / 2), $levelBottom], self::color($image, self::darker($green, 1 + $level * 0.08)));
            }
        }
    }

    private static function tent($image, int $x, int $y, int $size): void
    {
        $colors = [[230, 120, 60], [70, 110, 170], [200, 180, 90], [90, 140, 90]];
        $rgb = $colors[mt_rand(0, 3)];
        imagefilledpolygon($image, [$x, $y - $size, $x - $size, $y, $x + $size, $y], self::color($image, $rgb));
        imagefilledpolygon($image, [$x, $y - $size, $x + $size, $y, $x + (int) ($size * 1.5), $y - (int) ($size * 0.1), $x + (int) ($size * 0.5), $y - (int) ($size * 1.05)], self::color($image, self::darker($rgb, 0.75)));
        imagefilledpolygon($image, [$x, $y - (int) ($size * 0.6), $x - (int) ($size * 0.3), $y, $x + (int) ($size * 0.3), $y], self::color($image, self::darker($rgb, 0.45)));
    }

    private static function fire($image, int $x, int $y, float $scale): void
    {
        $wood = self::color($image, [90, 56, 30]);
        imagesetthickness($image, (int) (14 * $scale));
        imageline($image, $x - (int) (60 * $scale), $y + (int) (10 * $scale), $x + (int) (60 * $scale), $y - (int) (10 * $scale), $wood);
        imageline($image, $x - (int) (60 * $scale), $y - (int) (10 * $scale), $x + (int) (60 * $scale), $y + (int) (10 * $scale), $wood);
        imagesetthickness($image, 1);
        foreach ([[255, 90, 30, 1.0], [255, 160, 40, 0.7], [255, 230, 120, 0.4]] as [$r, $g, $b, $factor]) {
            $w = (int) (50 * $scale * $factor);
            $h = (int) (110 * $scale * $factor);
            imagefilledpolygon($image, [$x - $w, $y, $x - (int) ($w * 0.3), $y - (int) ($h * 0.7), $x, $y - $h, $x + (int) ($w * 0.4), $y - (int) ($h * 0.6), $x + $w, $y], self::color($image, [$r, $g, $b]));
        }
    }

    private static function canoe($image, int $x, int $y, string $scarf): void
    {
        $hull = self::color($image, [180, 70, 50]);
        imagefilledpolygon($image, [$x - 180, $y, $x + 180, $y, $x + 140, $y + 30, $x - 140, $y + 30], $hull);
        self::person($image, $x - 80, $y + 5, 0.5, [60, 110, 90], $scarf);
        self::person($image, $x + 70, $y + 5, 0.5, [70, 90, 130], $scarf);
    }

    /**
     * Child / leader seen from the front, in uniform with the scarf of the section. $y: position of the feet.
     */
    private static function person($image, int $x, int $y, float $scale, array $shirt, string $scarf, ?array $skin = null): void
    {
        $skin = $skin ?? [30, 24, 30];
        $silhouette = $skin[0] < 40;
        $h = (int) (330 * $scale);
        $legs = self::color($image, $silhouette ? $skin : [60, 66, 80]);
        imagefilledrectangle($image, $x - (int) (34 * $scale), $y - (int) ($h * 0.42), $x - (int) (6 * $scale), $y, $legs);
        imagefilledrectangle($image, $x + (int) (6 * $scale), $y - (int) ($h * 0.42), $x + (int) (34 * $scale), $y, $legs);
        $body = self::color($image, $silhouette ? $skin : $shirt);
        imagefilledpolygon($image, [$x - (int) (50 * $scale), $y - (int) ($h * 0.4), $x - (int) (56 * $scale), $y - (int) ($h * 0.78), $x + (int) (56 * $scale), $y - (int) ($h * 0.78), $x + (int) (50 * $scale), $y - (int) ($h * 0.4)], $body);
        imagefilledellipse($image, $x, $y - (int) ($h * 0.78), (int) (112 * $scale), (int) (40 * $scale), $body);
        imagefilledpolygon($image, [$x - (int) (42 * $scale), $y - (int) ($h * 0.8), $x + (int) (42 * $scale), $y - (int) ($h * 0.8), $x, $y - (int) ($h * 0.6)], self::color($image, self::rgb($scarf)));
        imagefilledellipse($image, $x, $y - (int) ($h * 0.9), (int) (70 * $scale), (int) (78 * $scale), self::color($image, $skin));
        if (!$silhouette)
            imagefilledarc($image, $x, $y - (int) ($h * 0.93), (int) (74 * $scale), (int) (60 * $scale), 180, 360, self::color($image, [[60, 40, 30], [140, 90, 50], [220, 180, 110], [30, 30, 34]][mt_rand(0, 3)]), IMG_ARC_PIE);
    }
}

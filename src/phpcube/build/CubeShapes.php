<?php
declare(strict_types=1);

/**
 * Разработано студией - vk.com/phpcube
 * Кто спиздил у того отпадет хуй
 */
namespace phpcube\build;

use pocketmine\world\Position;

final class CubeShapes
{
    /**
     * @param string $shape
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function getBlocksForShape(string $shape, int $radius, Position $position): array {
        return match ($shape) {
            "§c§l>> §fФигура: §eКрест" => self::buildCross($radius, $position),
            "§c§l>> §fФигура: §eКруг" => self::buildCircle($radius, $position),
            "§c§l>> §fФигура: §eКвадрат" => self::buildSquare($radius, $position),
            "§c§l>> §fФигура: §eПрямоугольник" => self::buildRectangle($radius, $position),
            "§c§l>> §fФигура: §eТреугольник" => self::buildTriangle($radius, $position),
            "§c§l>> §fФигура: §eРомб" => self::buildRhombus($radius, $position),
            "§c§l>> §fФигура: §eШестиугольник" => self::buildHexagon($radius, $position),
            "§c§l>> §fФигура: §eЗвезда" => self::buildStar($radius, $position),
            default => [],
        };
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildCross(int $radius, Position $position): array {
        $blocks = [];
        for ($i = -$radius; $i <= $radius; $i++) {
            $blocks[] = [$position->getX() + $i, $position->getY(), $position->getZ()];
            $blocks[] = [$position->getX(), $position->getY() + $i, $position->getZ()];
            $blocks[] = [$position->getX(), $position->getY(), $position->getZ() + $i];
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildCircle(int $radius, Position $position): array {
        $blocks = [];
        for ($x = -$radius; $x <= $radius; $x++) {
            for ($y = -$radius; $y <= $radius; $y++) {
                for ($z = -$radius; $z <= $radius; $z++) {
                    if (sqrt($x * $x + $y * $y + $z * $z) <= $radius) {
                        $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ() + $z];
                    }
                }
            }
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildSquare(int $radius, Position $position): array {
        $blocks = [];
        for ($x = -$radius; $x <= $radius; $x++) {
            for ($y = -$radius; $y <= $radius; $y++) {
                for ($z = -$radius; $z <= $radius; $z++) {
                    $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ() + $z];
                }
            }
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildRectangle(int $radius, Position $position): array {
        $blocks = [];
        $height = $radius * 2;
        $width = $radius;
        $depth = $radius / 2;
        for ($x = -$width; $x <= $width; $x++) {
            for ($y = -$depth; $y <= $depth; $y++) {
                for ($z = -$height; $z <= $height; $z++) {
                    $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ() + $z];
                }
            }
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildTriangle(int $radius, Position $position): array {
        $blocks = [];
        for ($y = 0; $y <= $radius; $y++) {
            for ($x = -$y; $x <= $y; $x++) {
                for ($z = -$y; $z <= $y; $z++) {
                    $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ() + $z];
                }
            }
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildRhombus(int $radius, Position $position): array {
        $blocks = [];
        for ($x = -$radius; $x <= $radius; $x++) {
            for ($y = -$radius; $y <= $radius; $y++) {
                for ($z = -$radius; $z <= $radius; $z++) {
                    if (abs($x) + abs($y) + abs($z) <= $radius) {
                        $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ() + $z];
                    }
                }
            }
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildHexagon(int $radius, Position $position): array {
        $blocks = [];
        for ($y = 0; $y <= $radius; $y++) {
            for ($x = max(-$radius + $y, -$radius); $x <= min($radius - $y, $radius); $x++) {
                for ($z = max(-$radius + $y, -$radius); $z <= min($radius - $y, $radius); $z++) {
                    $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ() + $z];
                    if ($y !== 0) { // Mirror vertically
                        $blocks[] = [$position->getX() + $x, $position->getY() - $y, $position->getZ() + $z];
                    }
                }
            }
        }
        return $blocks;
    }

    /**
     * @param int $radius
     * @param Position $position
     * @return array
     */
    public static function buildStar(int $radius, Position $position): array {
        $blocks = [];
        $innerRadius = $radius / 2;
        for ($y = -$radius; $y <= $radius; $y++) {
            for ($x = -$radius; $x <= $radius; $x++) {
                for ($z = -$radius; $z <= $radius; $z++) {
                    $distSq = $x * $x + $y * $y + $z * $z;
                    if ($distSq <= $innerRadius * $innerRadius || ($distSq > $innerRadius * $innerRadius && $distSq <= $radius * $radius && (($x + $y + $z) % 2 == 0))) {
                        $blocks[] = [$position->getX() + $x, $position->getY() + $y, $position->getZ()+ $z];
                    }
                }
            }
        }
        return $blocks;
    }
}
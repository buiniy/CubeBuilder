<?php

/**
 * Разработано студией - vk.com/phpcube
 * Кто спиздил у того отпадет хуй
 */
namespace phpcube\listener;

use Exception;
use phpcube\build\CubeShapes;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\Listener;
use phpcube\CubeBuilder;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

final class CubeBuildListener implements Listener{
    /**
     * @var CubeBuilder
     */
    public CubeBuilder $loader;

    /**
     * @param CubeBuilder $loader
     */
    public function __construct(CubeBuilder $loader){
        $this->loader = $loader;
    }

    /**
     * @param ProjectileHitBlockEvent $event
     * @return void
     */
    public function handleHit(ProjectileHitBlockEvent $event) : void{
        $entity = $event->getEntity();
        $owner = $entity->getOwningEntity();
        $block = $event->getBlockHit();
        if($entity instanceof Egg && $owner instanceof Player && Server::getInstance()->isOp($owner->getName())) {
            self::apply($owner, $block->getPosition());
        }
    }

    /**
     * @param Player $player
     * @param Position $position
     * @return void
     */
    public static function apply(Player $player, Position $position): void{
        if (!isset(CubeBuilder::$selectedShape[$player->getName()]) || !isset(CubeBuilder::$selectedRadius[$player->getName()])) {
            $player->sendMessage("§8§l(§c§lСтроитель§8)§r§f Вы не выбрали фигуру или радиус.");
            return;
        }
        $shape = CubeBuilder::$selectedShape[$player->getName()];
        $radius = CubeBuilder::$selectedRadius[$player->getName()];
        $invItem = $player->getInventory()->getItem(0);
        try {
            $block = $invItem->getBlock();
        } catch (Exception $e) {
            $player->sendMessage("§8§l(§c§lСтроитель§8)§r§f Ошибка: Невозможно получить блок из предмета.");
            return;
        }
        $blocksToPlace = CubeShapes::getBlocksForShape($shape, $radius, $position);
        foreach ($blocksToPlace as $blockData) {
            $pos = new Position($blockData[0], $blockData[1], $blockData[2], $position->getWorld());
            $position->getWorld()->setBlock($pos, $block, true);
        }
    }
}
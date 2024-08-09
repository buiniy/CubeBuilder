<?php
/**
 * Разработано студией - vk.com/phpcube
 * Кто спиздил у того отпадет хуй
 */

namespace phpcube;

use phpcube\command\BuilderCommand;
use phpcube\form\SimpleForm;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\entity\projectile\Egg;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use pocketmine\player\Player;

use phpcube\listener\CubeBuildListener;

class CubeBuilder extends PluginBase implements Listener{
    /**
     * @var array|string[]
     */
    public static array $shapes = [
        "§c§l>> §fФигура: §eКрест",
        "§c§l>> §fФигура: §eКруг",
        "§c§l>> §fФигура: §eКвадрат",
        "§c§l>> §fФигура: §eПрямоугольник",
        "§c§l>> §fФигура: §eТреугольник",
        "§c§l>> §fФигура: §eРомб",
        "§c§l>> §fФигура: §eШестиугольник",
        "§c§l>> §fФигура: §eЗвезда"
    ];
    /**
     * @var array
     */
    public static array $selectedShape = [];
    /**
     * @var array
     */
    public static array $selectedRadius = [];

    public function onEnable() : void{
        $this->getServer()->getLogger()->info("§c§lСтроитель фигур: §r§fРазработано студией - §cvk.com/phpcube");
        $this->getServer()->getPluginManager()->registerEvents(new CubeBuildListener($this), $this);
        $this->getServer()->getCommandMap()->register("builder", new BuilderCommand());
    }
}

<?php

/**
 * Разработано студией - vk.com/phpcube
 * Кто спиздил у того отпадет хуй
 */
namespace phpcube\command;

use phpcube\build\CubeShapes;
use phpcube\CubeBuilder;
use phpcube\form\SimpleForm;
use pocketmine\command\Command;
use pocketmine\permission\PermissionManager;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BuilderCommand extends Command {

    public function __construct() {
        parent::__construct("builder", "§c§lСтроитель фигур");
        $this->setPermission("cmd.builder");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if ($sender instanceof Player) {
            $this->openShapeSelectionForm($sender);
            return true;
        } else {
            $sender->sendMessage("§8§l(§c§lСтроитель§8)§r§f Команда доступна только игроку");
            return false;
        }
    }

    private function openShapeSelectionForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, mixed $data) {
            if ($data === null) {
                return;
            }
            CubeBuilder::$selectedShape[$player->getName()] = CubeBuilder::$shapes[$data];
            $this->openRadiusSelectionForm($player);
        });

        $form->setTitle("§c§lВыберите фигуру");
        foreach (CubeBuilder::$shapes as $index => $shape) {
            $form->addButton($shape, -1, "", $index);
        }
        $player->sendForm($form);
    }

    private function openRadiusSelectionForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, mixed $data) {
            if ($data === null || $data < 1 || $data > 8) {
                $player->sendMessage("§8§l(§c§lСтроитель§8)§r§f Неверный радиус.");
                unset(CubeBuilder::$selectedShape[$player->getName()]);
                return;
            }
            CubeBuilder::$selectedRadius[$player->getName()] = $data;
            $player->sendMessage("§8§l(§c§lСтроитель§8)§r§f Вы выбрали " . CubeBuilder::$selectedShape[$player->getName()] . " §rс радиусом: §c$data §fблоков.");
        });

        $form->setTitle("Выберите радиус");
        for ($i = 1; $i <= 8; $i++) {
            $form->addButton((string)$i, -1, "", $i);
        }
        $player->sendForm($form);
    }
}
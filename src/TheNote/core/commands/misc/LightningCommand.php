<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\misc;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\events\EventsListener;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class LightningCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("lightning", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("LightningDescription"), "/lightning");
        $this->setPermission(Permissions::$lightning);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $strike = new EventsListener();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "LighningUsage"));
            return false;
        }
        if (isset($args[0])) {
            $target = $api->findPlayer($sender, $args[0]);
            if ($target == null) {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "PlayernotOnline"));
                return false;
            } else {
                $strike->addStrike($target);
                $sender->sendMessage($api->getCommandPrefix("Prefix") . str_replace("{target}", $target->getName(), $api->getLang($sender->getName(), "LightningSucces")));
                return false;
            }
        }
        return true;
    }
}
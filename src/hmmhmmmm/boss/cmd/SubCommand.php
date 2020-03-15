<?php

namespace hmmhmmmm\boss\cmd;

use hmmhmmmm\boss\Boss;
use CortexPE\Commando\BaseSubCommand;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

abstract class SubCommand extends BaseSubCommand implements PluginIdentifiableCommand{
   protected $plugin;

   public function __construct(Boss $plugin, string $name, string $description = "", array $aliases = []){
      $this->plugin = $plugin;
      parent::__construct($name, $description, $aliases);
   }
   
   public function getPlugin(): Plugin{
      return $this->plugin;
   }
   
   protected function sendConsoleError(CommandSender $sender): void{
      $plugin = $this->getPlugin();
      $lang = $plugin->getLanguage();
      $sender->sendMessage($lang->getTranslate(
         "command.consoleError"
      ));
   }

   protected function sendPermissionError(CommandSender $sender): void{
      $plugin = $this->getPlugin();
      $lang = $plugin->getLanguage();
      $sender->sendMessage($lang->getTranslate(
         "command.permissionError"
      ));
   }
}
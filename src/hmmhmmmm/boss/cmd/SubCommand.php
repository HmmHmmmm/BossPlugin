<?php

namespace hmmhmmmm\boss\cmd;

use hmmhmmmm\boss\Boss;
use CortexPE\Commando\BaseSubCommand;

use pocketmine\command\CommandSender;

abstract class SubCommand extends BaseSubCommand{

   public function __construct(string $name, string $description = "", array $aliases = []){
      parent::__construct($name, $description, $aliases);
   }
   
   public function sendConsoleError(CommandSender $sender): void{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      $sender->sendMessage($lang->getTranslate(
         "command.consoleError"
      ));
   }

   public function sendPermissionError(CommandSender $sender): void{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      $sender->sendMessage($lang->getTranslate(
         "command.permissionError"
      ));
   }
}
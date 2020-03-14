<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\cmd\SubCommand;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class Info extends SubCommand{
   
   public function getPrefix(): string{
      return Boss::getInstance()->getPrefix();
   }
   
   protected function prepare(): void{
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = Boss::getInstance();
      $sender->sendMessage($plugin->getPluginInfo());
   }
   
}
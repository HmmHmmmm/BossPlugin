<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\cmd\SubCommand;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class Info extends SubCommand{

   public function __construct(Boss $plugin){
      parent::__construct($plugin, "info");
   }
   
   public function getPrefix(): string{
      return $this->getPlugin()->getPrefix();
   }
   
   public function prepare(): void{
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = $this->getPlugin();
      $sender->sendMessage($plugin->getPluginInfo());
   }
   
}
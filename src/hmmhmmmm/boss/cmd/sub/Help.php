<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\cmd\SubCommand;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class Help extends SubCommand{

   public function __construct(Boss $plugin){
      parent::__construct($plugin, "help");
   }
   
   public function getPrefix(): string{
      return $this->getPlugin()->getPrefix();
   }
   
   public function prepare(): void{
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $this->sendHelp($sender);
   }
   
   public function sendHelp(CommandSender $sender): void{
      $plugin = $this->getPlugin();
      $lang = $plugin->getLanguage();
      $sender->sendMessage($this->getPrefix()." : §fCommand");
      $sender->sendMessage("§a".$lang->getTranslate(
         "command.info.usage"
      )." : ".$lang->getTranslate(
         "command.info.description"
      ));
      $sender->sendMessage("§a".$lang->getTranslate(
         "command.create.usage"
      )." : ".$lang->getTranslate(
         "command.create.description"
      ));
      $sender->sendMessage("§a".$lang->getTranslate(
         "command.remove.usage"
      )." : ".$lang->getTranslate(
         "command.remove.description"
      ));
      $sender->sendMessage("§a".$lang->getTranslate(
         "command.respawn.usage"
      )." : ".$lang->getTranslate(
         "command.respawn.description"
      ));
      $sender->sendMessage("§a".$lang->getTranslate(
         "command.setrespawn.usage"
      )." : ".$lang->getTranslate(
         "command.setrespawn.description"
      ));
      $sender->sendMessage("§a".$lang->getTranslate(
         "command.slapper_respawn.usage"
      )." : ".$lang->getTranslate(
         "command.slapper_respawn.description"
      ));
   }
   
}
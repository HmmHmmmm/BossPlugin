<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\cmd\SubCommand;
use hmmhmmmm\boss\cmd\args\StringArgs;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class Remove extends SubCommand{

   public function __construct(Boss $plugin){
      parent::__construct($plugin, "remove");
   }
   
   public function getPrefix(): string{
      return $this->getPlugin()->getPrefix();
   }
   
   public function prepare(): void{
      $this->registerArgument(0, new StringArgs("name", true));
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = $this->getPlugin();
      $lang = $plugin->getLanguage();
      if(count($args) < 1){
         $sender->sendMessage($lang->getTranslate(
            "command.remove.error1",
            [
               $lang->getTranslate(
                  "command.remove.usage"
               )
            ]
         ));
         return;
      }
      $name = $args["name"];
      if(!BossData::isBoss($name)){
         $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
            "command.remove.error2",
            [$name]
         ));
         return;
      }
      BossData::removeBoss($name);
      $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
         "command.remove.complete",
         [$name]
      ));
   }
   
}
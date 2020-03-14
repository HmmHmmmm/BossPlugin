<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\utils\BossUtils;
use hmmhmmmm\boss\cmd\SubCommand;
use hmmhmmmm\boss\cmd\args\StringArgs;
use hmmhmmmm\boss\cmd\args\IntegerArgs;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class SetRespawn extends SubCommand{
   
   public function getPrefix(): string{
      return Boss::getInstance()->getPrefix();
   }
   
   protected function prepare(): void{
      $this->registerArgument(0, new StringArgs("name", true));
      $this->registerArgument(1, new IntegerArgs("time", true));
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      if(count($args) < 2){
         $sender->sendMessage($lang->getTranslate(
            "command.setrespawn.error1",
            [
               $lang->getTranslate(
                  "command.setrespawn.usage"
               )
            ]
         ));
         return;
      }
      $name = $args["name"];
      if(!BossData::isBoss($name)){
         $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
            "command.setrespawn.error2",
            [$name]
         ));
         return;
      }
      $time = $args["time"];
      $time = (int) $time;
      BossData::setIsRespawnTime($name, $time);
      BossData::setRespawnTime($name, $time);
      $bossUtils = new BossUtils();
      $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
         "command.setrespawn.complete",
         [$bossUtils->sendTime($time), $name]
      ));
   }
   
}
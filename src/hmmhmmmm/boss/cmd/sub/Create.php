<?php

namespace hmmhmmmm\boss\cmd\sub;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\BossManager;
use hmmhmmmm\boss\cmd\SubCommand;
use hmmhmmmm\boss\cmd\args\StringArgs;
use hmmhmmmm\boss\cmd\args\EnumArgs;
use hmmhmmmm\boss\cmd\args\IntegerArgs;
use hmmhmmmm\boss\object\CreateBossObject;

use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\level\Location;

class Create extends SubCommand{
   
   public function getPrefix(): string{
      return Boss::getInstance()->getPrefix();
   }
   
   protected function prepare(): void{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      $this->registerArgument(0, new StringArgs("name", true));
      $entityType = [];
      foreach($plugin->entityList as $list){
         $entityType[$list] = $list;
      }
      $this->registerArgument(1, new EnumArgs("monster", $entityType, true));
      $this->registerArgument(2, new IntegerArgs("health", true));
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      if(!$sender instanceof Player){
         $this->sendConsoleError($sender);
         return;
      }
      if(count($args) < 3){
         $sender->sendMessage($lang->getTranslate(
            "command.create.error1",
            [
               $lang->getTranslate(
                  "command.create.usage"
               )
            ]
         ));
         return;
      }
      $name = $args["name"];
      if(BossData::isBoss($name)){
         $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
            "command.create.error2",
            [$name]
         ));
         return;
      }
      $entityType = $args["monster"];
      if(!in_array($entityType, $plugin->entityList)){
         $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
            "command.create.error3",
            [$entityType]
         ));
         return;
      }
      $health = $args["health"];
      $db = Boss::getInstance()->getDatabase();
      $pos = $sender;
      $object = [
         "entityType" => $entityType,
         "x" => $pos->x,
         "y" => $pos->y,
         "z" => $pos->z,
         "level" => $pos->level->getFolderName(),
         "isrespawntime" => 600,
         "respawntime" => 600,
         "deathRespawntime" => 5,
         "health" => (float) $health,
         "speed" => (float) 1.0,
         "scale" => (float) 1.0,
         "minDamage" => (float) 5.0,
         "maxDamage" => (float) 5.0,
         "infoDrop" => "???",
         "commandDrop" => "??"
      ];
      $db->create($name, $object);
      BossManager::spawn($name);
      $sender->sendMessage($this->getPrefix()." ".$lang->getTranslate(
         "command.create.complete",
         [$name, $entityType]
      ));
   }
   
}
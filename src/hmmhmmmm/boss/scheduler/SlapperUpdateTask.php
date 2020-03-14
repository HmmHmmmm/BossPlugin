<?php

namespace hmmhmmmm\boss\scheduler;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\BossManager;
use hmmhmmmm\boss\utils\BossUtils;
use slapper\entities\SlapperEntity;
use slapper\entities\SlapperHuman;

use pocketmine\scheduler\Task;
use pocketmine\nbt\tag\StringTag;

class SlapperUpdateTask extends Task{
   private $plugin;
   
   public function __construct(Boss $plugin){
      $this->plugin = $plugin;
   }
   
   public function getPlugin(): Boss{
      return $this->plugin;
   }
   
   public function onRun(int $currentTick): void{
      $this->onSlapperUpdate();
   }

   public function onSlapperUpdate(): void{
      foreach($this->plugin->getServer()->getLevels() as $level){
         foreach($level->getEntities() as $entity){
            if($entity instanceof SlapperEntity || $entity instanceof SlapperHuman){
               if($entity->namedtag->hasTag("slapper_Boss", StringTag::class)){
                  $bossName = $entity->namedtag->getString("slapper_Boss");
                  if(BossData::isBoss($bossName)){
                     $entity->setNameTag($this->getPlugin()->getPrefix()." ".BossManager::respawnInfo($bossName));
                  }else{
                     $entity->close();
                  }
               }
            }
         }
      }
   }
   
}
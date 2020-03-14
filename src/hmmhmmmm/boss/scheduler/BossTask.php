<?php

namespace hmmhmmmm\boss\scheduler;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\BossManager;

use pocketmine\scheduler\Task;

class BossTask extends Task{
   private $plugin;
   
   public function __construct(Boss $plugin){
      $this->plugin = $plugin;
   }
 
   public function getPlugin(): Boss{
      return $this->plugin;
   }

   public function onRun(int $currentTick): void{
      $this->respawn();
   }
   
   public function respawn(): void{
      if(BossData::getCountBoss() == 0){
         return;
      }
      foreach(BossData::getBoss() as $name){
         $respawn = BossData::getRespawnTime($name);
         $respawn--;
         BossData::setRespawnTime($name, $respawn);
         if(BossData::getRespawnTime($name) <= 0){
            $pos = BossData::getSpawn($name);
            $level = $pos->getLevel();
            if($this->plugin->getServer()->isLevelLoaded($level->getFolderName())){
               if(count($level->getPlayers()) !== 0){
                  BossManager::respawn($name);
                  BossData::setRespawnTime($name, BossData::getIsRespawnTime($name));
               }
            }
         }
      }
   }

}
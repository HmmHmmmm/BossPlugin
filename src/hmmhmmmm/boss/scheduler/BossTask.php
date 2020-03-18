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
            $levelFolderName = BossData::getLevelFolderName($name);
            if(!file_exists($this->plugin->getServer()->getDataPath()."worlds/".$levelFolderName)){
               $plugin->getLogger()->error("§cBoss ".$name ." will not respawn because not found  world ".$levelFolderName);
               BossData::setRespawnTime($name, BossData::getIsRespawnTime($name));
            }
            $pos = BossData::getSpawn($name);
            $level = $pos->getLevel();
            if(count($level->getPlayers()) == 0){
               BossData::setRespawnTime($name, 30);
            }else{
               BossManager::respawn($name);
               BossData::setRespawnTime($name, BossData::getIsRespawnTime($name));
            }
         }
      }
   }

}
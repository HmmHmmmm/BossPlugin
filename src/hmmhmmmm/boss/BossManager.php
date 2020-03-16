<?php

namespace hmmhmmmm\boss;

use revivalpmmp\pureentities\entity\BaseEntity;
use hmmhmmmm\boss\utils\BossUtils;

use pocketmine\entity\Entity;
use pocketmine\level\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

class BossManager{

   public static function respawnInfo(string $name): string{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      $bossUtils = new BossUtils();
      return $lang->getTranslate(
         "boss.respawninfo",
         [$name, $bossUtils->sendTime(BossData::getRespawnTime($name))]
      );
   }
   
   public static function respawnAll(): void{
      if(BossData::getCountBoss() !== 0){
         foreach(BossData::getBoss() as $name){
            BossData::setRespawnTime($name, 0);
         }
      }
   }
   
   public static function remove(string $name): void{
      $plugin = Boss::getInstance();
      $levelFolderName = BossData::getLevelFolderName($name);
      if(!file_exists($plugin->getServer()->getDataPath()."worlds/".$levelFolderName)){
         $plugin->getLogger()->error("§cBoss ".$name ." will not remove because not found  world ".$levelFolderName);
         return;
      }
      $level = BossData::getSpawn($name)->getLevel();
      foreach($level->getEntities() as $entity){
         if($entity->namedtag instanceof CompoundTag){
            if($entity->namedtag->hasTag("Boss".BossData::getEntityType($name), StringTag::class)){
               $entity->close();
            }
         }
      }
   }
   
   public static function spawn(string $name): void{
      $plugin = Boss::getInstance();
      $levelFolderName = BossData::getLevelFolderName($name);
      if(!file_exists($plugin->getServer()->getDataPath()."worlds/".$levelFolderName)){
         $plugin->getLogger()->error("§cBoss ".$name ." will not spawn because not found  world ".$levelFolderName);
         return;
      }
      $pos = BossData::getSpawn($name);
      $level = $pos->getLevel();
      if(!$plugin->getServer()->isLevelLoaded($level->getFolderName())){
         return;
      }
      $entityCount = 0;
      foreach($level->getEntities() as $entity){
         if($entity->namedtag instanceof CompoundTag){
            if($entity->namedtag->hasTag("Boss".BossData::getEntityType($name), StringTag::class)){
               $entityCount++;
            }
         }
      }
      if($entityCount == 0){
         $nbt = Entity::createBaseNBT($pos->asVector3(), null, $pos instanceof Location ? $pos->yaw : 0, $pos instanceof Location ? $pos->pitch : 0);
         $entity = Entity::createEntity("Boss".BossData::getEntityType($name), $pos->getLevel(), $nbt);
         $minDamage = BossData::getMinDamage($name);
         $maxDamage = BossData::getMaxDamage($name);
         $entity->namedtag->setString("Boss".$entity->getName(), $name);
         $entity->setHealth(BossData::getHealth($name));
         $entity->health = BossData::getHealth($name);
         $entity->speed = BossData::getSpeed($name);
         $entity->setMinDamage($minDamage);
         $entity->setMaxDamage($maxDamage);
         $entity->setScale(BossData::getScale($name));
         $entity->setNameTag($name);
         $entity->setNameTagAlwaysVisible(true);
         $entity->setNameTagVisible(true);
         $entity->spawnToAll();
         $plugin->getServer()->broadcastMessage($plugin->getPrefix()." ".$plugin->getLanguage()->getTranslate(
            "boss.spawn",
            [$entity->getName(), $name]
         ));
      }
   }
   
   public static function respawn(string $name): void{
      $plugin = Boss::getInstance();
      $levelFolderName = BossData::getLevelFolderName($name);
      if(!file_exists($plugin->getServer()->getDataPath()."worlds/".$levelFolderName)){
         $plugin->getLogger()->error("§cBoss ".$name ." will not respawn because not found  world ".$levelFolderName);
         return;
      }
      $pos = BossData::getSpawn($name);
      $level = $pos->getLevel();
      if(!BossData::isBoss($name)){
         return;
      }
      if(!$plugin->getServer()->isLevelLoaded($level->getFolderName())){
         return;
      }
      if(count($level->getPlayers()) !== 0){
         foreach($level->getEntities() as $entity){
            if($entity->namedtag instanceof CompoundTag){
               if($entity->namedtag->hasTag("Boss".BossData::getEntityType($name), StringTag::class)){
                  $entity->close();
               }
            }
         }
         BossManager::spawn($name);
      }
   }

}
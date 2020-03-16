<?php

namespace hmmhmmmm\boss;

use revivalpmmp\pureentities\entity\BaseEntity;

use pocketmine\level\Position;
use pocketmine\level\Location;
use pocketmine\nbt\tag\StringTag;

class BossData{

   public static function getCountBoss(): int{
      $db = Boss::getInstance()->getDatabase();
      return $db->getCount();
   }
   
   public static function getBoss(): array{
      $db = Boss::getInstance()->getDatabase();
      return $db->getAll();
   }
   
   public static function isBoss(string $name): bool{
      $db = Boss::getInstance()->getDatabase();
      return $db->exists($name);
   }
   
   public static function createBoss(string $name, string $entityType, Location $pos, int $respawntime, int $deathRespawntime, int $health, float $speed, float $scale, int $minDamage, int $maxDamage, string $infoDrop, string $commandDrop): void{
      $db = Boss::getInstance()->getDatabase();
      $object = [
         "entityType" => $entityType,
         "x" => $pos->x,
         "y" => $pos->y,
         "z" => $pos->z,
         "level" => $pos->level->getFolderName(),
         "isrespawntime" => $respawntime,
         "respawntime" => $respawntime,
         "deathRespawntime" => $deathRespawntime,
         "health" => (float) $health,
         "speed" => $speed,
         "scale" => $scale,
         "minDamage" => (float) $minDamage,
         "maxDamage" => (float) $maxDamage,
         "infoDrop" => $infoDrop,
         "commandDrop" => $commandDrop
      ];
      $db->create($name, $object);
      BossManager::spawn($name);
   }
   
   public static function editBoss(string $name, int $deathRespawntime, int $health, float $speed, float $scale, int $minDamage, int $maxDamage, string $infoDrop, string $commandDrop): void{
      $db = Boss::getInstance()->getDatabase();
      $pos = BossData::getSpawn($name);
      $object = [
         "entityType" => BossData::getEntityType($name),
         "x" => $pos->x,
         "y" => $pos->y,
         "z" => $pos->z,
         "level" => $pos->level->getFolderName(),
         "isrespawntime" => BossData::getIsRespawnTime($name),
         "respawntime" => BossData::getRespawnTime($name),
         "deathRespawntime" => $deathRespawntime,
         "health" => (float) $health,
         "speed" => $speed,
         "scale" => $scale,
         "minDamage" => (float) $minDamage,
         "maxDamage" => (float) $maxDamage,
         "infoDrop" => $infoDrop,
         "commandDrop" => $commandDrop
      ];
      $db->edit($name, $object);
      BossManager::spawn($name);
   }
  
   public static function getEntityType(string $name): string{
      $db = Boss::getInstance()->getDatabase();
      return $db->getEntityType($name);
   }
   
   public static function getLevelFolderName(string $name): string{
      $db = Boss::getInstance()->getDatabase();
      return $db->getLevelName($name);
   }
   
   public static function getSpawn(string $name): Position{
      $db = Boss::getInstance()->getDatabase();
      return $db->getPosition($name);
   }
   
   public static function removeBoss(string $name): void{
      BossManager::remove($name);
      $db = Boss::getInstance()->getDatabase();
      $db->remove($name);
   }
   
   public static function getRespawnTime(string $name): int{
      $db = Boss::getInstance()->getDatabase();
      return $db->getRespawnTime($name);
   }
   
   public static function getIsRespawnTime(string $name): int{
      $db = Boss::getInstance()->getDatabase();
      return $db->getIsRespawnTime($name);
   }
   
   public static function setRespawnTime(string $name, int $time): void{
      $db = Boss::getInstance()->getDatabase();
      $db->setRespawnTime($name, $time);
   }
   
   public static function setIsRespawnTime(string $name, int $time): void{
      $db = Boss::getInstance()->getDatabase();
      $db->setIsRespawnTime($name, $time);
   }
   
   public static function getDeathRespawnTime(string $name): int{
      $db = Boss::getInstance()->getDatabase();
      return $db->getDeathRespawnTime($name);
   }
   
   public static function getHealth(string $name): float{
      $db = Boss::getInstance()->getDatabase();
      return $db->getHealth($name);
   }
   
   public static function getSpeed(string $name): float{
      $db = Boss::getInstance()->getDatabase();
      return $db->getSpeed($name);
   }
   
   public static function getScale(string $name): float{
      $db = Boss::getInstance()->getDatabase();
      return $db->getScale($name);
   }
  
   public static function getMinDamage(string $name): float{
      $db = Boss::getInstance()->getDatabase();
      return $db->getMinDamage($name);
   }
   
   public static function getMaxDamage(string $name): float{
      $db = Boss::getInstance()->getDatabase();
      return $db->getMaxDamage($name);
   }
   
   public static function getInfoDrop(string $name): string{
      $db = Boss::getInstance()->getDatabase();
      return $db->getInfoDrop($name);
   }
   
   public static function getCommandDrop(string $name): string{
      $db = Boss::getInstance()->getDatabase();
      return $db->getCommandDrop($name);
   }
   
}
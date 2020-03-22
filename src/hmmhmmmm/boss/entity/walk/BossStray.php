<?php

namespace hmmhmmmm\boss\entity\walk;

use revivalpmmp\pureentities\entity\monster\walking\Stray;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\Projectile;
use pocketmine\entity\projectile\ProjectileSource;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\level\sound\LaunchSound;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class BossStray extends Stray{
   public $health = 20;
   public $boss_data = "0xAAA001";
  
   public function getName(): string{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return "BossStray";
      }else{
         return parent::getName();
      }
   }
   
   public function attackEntity(Entity $player){
      if($this->attackDelay > 30 && mt_rand(1, 32) < 4 && $this->distanceSquared($player) <= 55){
         $this->attackDelay = 0;
         if($player instanceof Player){
            $f = 1.2;
            $yaw = $this->yaw + mt_rand(-220, 220) / 10;
            $pitch = $this->pitch + mt_rand(-120, 120) / 10;
            $nbt = new CompoundTag("", [
               "Pos" => new ListTag("Pos", [
                  new DoubleTag("", $this->x + (-sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * 0.5)),
                  new DoubleTag("", $this->y + 1.62),
                  new DoubleTag("", $this->z + (cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * 0.5))
               ]),
               "Motion" => new ListTag("Motion", [
                  new DoubleTag("", -sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * $f),
                  new DoubleTag("", -sin($pitch / 180 * M_PI) * $f),
                  new DoubleTag("", cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * $f)
               ]),
               "Rotation" => new ListTag("Rotation", [
                  new FloatTag("", $yaw),
                  new FloatTag("", $pitch)
               ]),
            ]);
            $arrow = Entity::createEntity("Arrow", $this->getLevel(), $nbt, $this);
            $name = $this->boss_data;
            if(BossData::isBoss($name)){
               $arrow->setBaseDamage((float) $this->getDamage());
            }else{
               $arrow->setBaseDamage((float) mt_rand(3, 5));
            }
            $bow = $this->mobEquipment->getMainHand();
            $ev = new EntityShootBowEvent($this, $bow, $arrow, $f);
            $ev->call();
            $projectile = $ev->getProjectile();
            if($ev->isCancelled()){
               $projectile->kill();
            }elseif($projectile instanceof Projectile){
               $launch = new ProjectileLaunchEvent($projectile);
               $launch->call();
               if($launch->isCancelled()){
                  $projectile->kill();
               }else{
                  $projectile->spawnToAll();
                  $this->level->addSound(new LaunchSound($this), $this->getViewers());
               }
            }
            $this->checkTamedMobsAttack($player);
         }
      }
   }

   public function getMaxHealth(): int{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return BossData::getHealth($name);
      }else{
         return $this->health;
      }
   }
   
   public function entityBaseTick(int $tickDiff = 1): bool{
      $hasUpdate = parent::entityBaseTick($tickDiff);
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         $this->setNameTag($name." §c(".$this->getHealth()."/".$this->getMaxHealth().")");
         $this->setNameTagAlwaysVisible(true);
         $this->setNameTagVisible(true);
         if($this->isOnFire()){
            $this->extinguish();
         }
         return $hasUpdate;
      }else{
         return parent::entityBaseTick($tickDiff);
      }
   }
   
   public function targetOption(Creature $creature, float $distance) : bool{
      if(!($creature instanceof SlapperHuman)){
         if($creature instanceof Player){
            return parent::targetOption($creature, $distance);
         }
      }
      return false;
   }
   
}
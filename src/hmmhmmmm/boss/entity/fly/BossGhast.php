<?php

namespace hmmhmmmm\boss\entity\fly;

use revivalpmmp\pureentities\entity\monster\flying\Ghast;
use hmmhmmmm\boss\entity\projectile\LargeFireball;
use revivalpmmp\pureentities\PureEntities;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\entity\projectile\ProjectileSource;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class BossGhast extends Ghast{
   public $health = 10;   
   public $boss_data = "0xAAA001";
  
   public function getName(): string{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return "BossGhast";
      }else{
         return parent::getName();
      }
   }
   
   public function attackEntity(Entity $player){
      if($this->attackDelay > 30 && mt_rand(1, 32) < 4 && $this->distance($player) <= 100){
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
                  new DoubleTag("", -sin(rad2deg($yaw)) * cos(rad2deg($pitch)) * $f * $f),
                  new DoubleTag("", -sin(rad2deg($pitch)) * $f * $f),
                  new DoubleTag("", cos(rad2deg($yaw)) * cos(rad2deg($pitch)) * $f * $f)
               ]),
               "Rotation" => new ListTag("Rotation", [
                  new FloatTag("", $yaw),
                  new FloatTag("", $pitch)
               ]),
            ]);
            $fireball = new LargeFireball($this->level, $nbt, $this);

            $fireball->setExplode(true);
            $this->server->getPluginManager()->callEvent($launch = new ProjectileLaunchEvent($fireball));
            if($launch->isCancelled()){
               $fireball->kill();
            }else{
               $fireball->spawnToAll();
               $this->level->addSound(new LaunchSound($this), $this->getViewers());
            }
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
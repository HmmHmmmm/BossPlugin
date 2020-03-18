<?php

namespace hmmhmmmm\boss\entity\walk;

use revivalpmmp\pureentities\entity\monster\walking\Witch;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

class BossWitch extends Witch{
   public $health = 26;
   
   public function attackEntity(Entity $player){
      if($this->attackDelay > 10 && $this->distanceSquared($player) < 2){
         $this->attackDelay = 0;
         $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
         $player->attack($ev);
         $this->checkTamedMobsAttack($player);
      }
   }
   
   public function initEntity() : void{
      parent::initEntity();
      if($this->namedtag instanceof CompoundTag){
         if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
            $name = $this->namedtag->getString("Boss".$this->getName());
            $this->setHealth(BossData::getHealth($name));
            $this->health = BossData::getHealth($name);
            $this->speed = BossData::getSpeed($name);
            $this->setMinDamage(BossData::getMinDamage($name));
            $this->setMaxDamage(BossData::getMaxDamage($name));
            $this->setScale(BossData::getScale($name));
         }
      }
   }
  
   public function getMaxHealth(): int{
      if($this->namedtag instanceof CompoundTag){
         if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
            if(BossData::isBoss($this->namedtag->getString("Boss".$this->getName()))){
               return BossData::getHealth($this->namedtag->getString("Boss".$this->getName()));
            }else{
               return $this->health;
            }
         }else{
            return $this->health;
         }
      }else{
         return $this->health;
      }
   }
   
   public function entityBaseTick(int $tickDiff = 1): bool{
      if($this->namedtag instanceof CompoundTag){
         $hasUpdate = parent::entityBaseTick($tickDiff);
         if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
            $name = $this->namedtag->getString("Boss".$this->getName());
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
         }else{
            return parent::entityBaseTick($tickDiff);
         }
      }else{
         return parent::entityBaseTick($tickDiff);
      }
   }
   
   public function targetOption(Creature $creature, float $distance) : bool{
      if(!($creature instanceof SlapperHuman)){
         return parent::targetOption($creature, $distance);
      }
      return false;
   }
   
}
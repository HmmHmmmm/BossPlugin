<?php

namespace hmmhmmmm\boss\entity\fix;

use revivalpmmp\pureentities\entity\monster\Monster;
use revivalpmmp\pureentities\data\Data;
use revivalpmmp\pureentities\entity\monster\WalkingMonster;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\item\Item;

class IronGolem extends WalkingMonster implements Monster{
   const NETWORK_ID = Data::NETWORK_IDS["iron_golem"];
   public $health = 100;
   
   public function getName() : string{
      return "IronGolem";
   }
   
   public function attackEntity(Entity $player): void{
      if($this->attackDelay > 10 && $this->distanceSquared($player) < 4){
         $this->attackDelay = 0;
         $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
         $player->attack($ev);
         $player->setMotion(new Vector3(0, 0.7, 0));
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
      }else{
         $this->speed = 0.8;
         $this->setHealth($this->health);
         $this->setFriendly(true);
         $this->setDamage([0, 21, 21, 21]);
         $this->setMinDamage([0, 7, 7, 7]);
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
         if(!($this->namedtag instanceof CompoundTag)){
            if(!($creature instanceof Player)){
               return $creature->isAlive() && $distance <= 60;
            }
         }else{
            if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
               return $this instanceof Monster
                  && (!($creature instanceof Player)
                  || ($creature->isSurvival()
                  && $creature->spawned))
                  && $creature->isAlive()
                  && !$creature->isClosed()
                  && $distance <= 81;
            }
         }
      }
      return false;
   }
   
   public function getDrops() : array{
      $drops = [];
      if($this->isLootDropAllowed()){
         array_push($drops, Item::get(Item::IRON_INGOT, 0, mt_rand(3, 5)));
         array_push($drops, Item::get(Item::POPPY, 0, mt_rand(0, 2)));
      }
      return $drops;
   }
}
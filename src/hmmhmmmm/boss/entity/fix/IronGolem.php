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
   public $boss_data = "0xAAA001";
  
   public function getName(): string{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return "BossIronGolem";
      }else{
         return "IronGolem";
      }
   }

   public function attackEntity(Entity $player){
      if($this->attackDelay > 10 && $this->distanceSquared($player) < 2){
         $this->attackDelay = 0;
         if($player instanceof Player){
            $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
            $player->attack($ev);
            $player->setMotion(new Vector3(0, 0.7, 0));
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
   
   public function getDrops() : array{
      $drops = [];
      if($this->isLootDropAllowed()){
         array_push($drops, Item::get(Item::IRON_INGOT, 0, mt_rand(3, 5)));
         array_push($drops, Item::get(Item::POPPY, 0, mt_rand(0, 2)));
      }
      return $drops;
   }
}
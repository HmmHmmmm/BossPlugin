<?php

namespace hmmhmmmm\boss\entity\projectile;

use revivalpmmp\pureentities\entity\projectile\SmallFireball as Small;

use pocketmine\level\particle\EntityFlameParticle;

class SmallFireball extends Small{
   
   public function onUpdate(int $currentTick): bool{
      $hasUpdate = parent::onUpdate($currentTick);
      $this->level->addParticle(new EntityFlameParticle($this));
      return $hasUpdate;
   }
   
}
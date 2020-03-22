<?php

namespace hmmhmmmm\boss\entity\projectile;

use revivalpmmp\pureentities\entity\projectile\LargeFireball as Large;

use pocketmine\level\particle\FlameParticle;

class LargeFireball extends Large{

   public function onUpdate(int $currentTick): bool{
      $hasUpdate = parent::onUpdate($currentTick);
      $this->level->addParticle(new FlameParticle($this));
      return $hasUpdate;
   }
   
}
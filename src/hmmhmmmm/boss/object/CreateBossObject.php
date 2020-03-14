<?php

namespace hmmhmmmm\boss\object;

use hmmhmmmm\boss\Boss;

class CreateBossObject{
   private $object;
   
   public function __construct(array $object = []){
      $this->object = $object;
   }
   
   public function getPage(): string{
      return $this->object["page"];
   }
   
   public function setPage(string $page): void{
      $this->object["page"] = $page;
   }
   
   public function getName(): string{
      return $this->object["name"];
   }
   
   public function getEntityType(): string{
      return $this->object["entityType"];
   }
   
   public function getRespawn(): int{
      return $this->object["respawn"];
   }
      
   public function getDeathRespawn(): int{
      return $this->object["death_respawn"];
   }
   
   public function getSpeed(): float{
      return $this->object["speed"];
   }
   
   public function setSpeed(float $speed): void{
      $this->object["speed"] = $speed;
   }
   
   public function getScale(): float{
      return $this->object["scale"];
   }
   
   public function setScale(float $scale): void{
      $this->object["scale"] = $scale;
   }
   
   public function getMinDamage(): float{
      return $this->object["min_damage"];
   }
   
   public function setMinDamage(float $min_damage): void{
      $this->object["min_damage"] = $min_damage;
   }
   
   public function getMaxDamage(): float{
      return $this->object["max_damage"];
   }
   
   public function setMaxDamage(float $max_damage): void{
      $this->object["max_damage"] = $max_damage;
   }
   
   public function getInfoDrop(): string{
      return $this->object["info_drop"];
   }
   
   public function setInfoDrop(string $info_drop): void{
      $this->object["info_drop"] = $info_drop;
   }
   
   public function getCommandDrop(): string{
      return $this->object["command_drop"];
   }
   
   public function setCommandDrop(string $command_drop): void{
      $this->object["command_drop"] = $command_drop;
   }
   
}
<?php

namespace hmmhmmmm\boss\utils;

use hmmhmmmm\boss\Boss;

class SQL_BossDataUtils{
   private $object;
   
   public function __construct(array $object = []){
      $this->object = $object;
   }
   
   public function getAll(): array{
      return $this->object;
   }
   
   public function setAll(array $data): void{
      $this->object = $data;
   }
   
   public function save(): void{
      Boss::getInstance()->getDatabase()->saveAll();
   }
   
}
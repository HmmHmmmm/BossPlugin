<?php

namespace hmmhmmmm\boss\database\yaml;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\database\Database;

use pocketmine\level\Position;
use pocketmine\utils\Config;

class BossData_YML implements Database{
   private $plugin;
   public $db_name;
   
   public $data;
   
   public function __construct(string $db_name){
      $this->plugin = Boss::getInstance();
      $this->db_name = $db_name;
      $this->data = new Config($this->plugin->getDataFolder()."boss.yml", Config::YAML, array());
   }
   
   public function getDatabaseName(): string{
      return $this->db_name;
   }
   
   public function getData(): Config{
      return $this->data;
   }
   
   public function close(): void{
   
   }
   
   public function reset(): void{
      $data = $this->getData()->getAll();
      $data = [];
      $this->getData()->setAll($data);
      $this->getData()->save();
   }
   
   public function saveAll(): void{
   
   }
   
   public function getCount(): int{
      $data = $this->getData()->getAll();
      return count($data);
   }
   
   public function getAll(): array{
      $data = $this->getData()->getAll();
      return array_keys($data);
   }
   
   public function exists(string $name): bool{
      $data = $this->getData()->getAll();
      return isset($data[$name]);
   }
   
   public function create(string $name, array $object): void{
      $data = $this->getData()->getAll();
      $data[$name] = $object;
      $this->getData()->setAll($data);
      $this->getData()->save();
   }
   
   public function edit(string $name, array $object): void{
      $this->create($name, $object);
   }
   
   public function getEntityType(string $name): string{
      $data = $this->getData()->getAll();
      return $data[$name]["entityType"];
   }
   
   public function getPosition(string $name): Position{
      $data = $this->getData()->getAll();
      return new Position($data[$name]["x"], $data[$name]["y"], $data[$name]["z"], $this->plugin->getServer()->getLevelByName($data[$name]["level"]));
   }
   
   public function remove(string $name): void{
      $data = $this->getData()->getAll();
      unset($data[$name]);
      $this->getData()->setAll($data);
      $this->getData()->save();
   }
   
   public function getRespawnTime(string $name): int{
      $data = $this->getData()->getAll();
      return $data[$name]["respawntime"];
   }
   
   public function getIsRespawnTime(string $name): int{
      $data = $this->getData()->getAll();
      return $data[$name]["isrespawntime"];
   }
   
   public function setRespawnTime(string $name, int $time): void{
      $data = $this->getData()->getAll();
      $data[$name]["respawntime"] = $time;
      $this->getData()->setAll($data);
      $this->getData()->save();
   }
   
   public function setIsRespawnTime(string $name, int $time): void{
      $data = $this->getData()->getAll();
      $data[$name]["isrespawntime"] = $time;
      $this->getData()->setAll($data);
      $this->getData()->save();
   }
   
   public function getDeathRespawnTime(string $name): int{
      $data = $this->getData()->getAll();
      return $data[$name]["deathRespawntime"];
   }
   
   public function getHealth(string $name): float{
      $data = $this->getData()->getAll();
      return $data[$name]["health"];
   }
   
   public function getSpeed(string $name): float{
      $data = $this->getData()->getAll();
      return $data[$name]["speed"];
   }
   
   public function getScale(string $name): float{
      $data = $this->getData()->getAll();
      return $data[$name]["scale"];
   }
  
   public function getMinDamage(string $name): float{
      $data = $this->getData()->getAll();
      return $data[$name]["minDamage"];
   }
   
   public function getMaxDamage(string $name): float{
      $data = $this->getData()->getAll();
      return $data[$name]["maxDamage"];
   }
   
   public function getInfoDrop(string $name): string{
      $data = $this->getData()->getAll();
      return $data[$name]["infoDrop"];
   }
   
   public function getCommandDrop(string $name): string{
      $data = $this->getData()->getAll();
      return $data[$name]["commandDrop"];
   }
   
}
<?php

namespace hmmhmmmm\boss\database\sqlite;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\database\Database;
use hmmhmmmm\boss\utils\SQL_BossDataUtils;
use poggit\libasynql\libasynql;

use pocketmine\level\Position;

class BossData_SQLite implements Database{
   private $plugin;
   public $db_name;
   
   private $db;
   public $data;
   
   public function __construct(string $db_name){
      $this->plugin = Boss::getInstance();
      $this->db_name = $db_name;
      $mc = $this->plugin->getConfig()->getNested("MySQL-Info");
      $libasynql_friendly_config = [
         "type" => $this->plugin->getConfig()->getNested("bossdata-database"),
         "sqlite" => [
            "file" => $this->plugin->getDataFolder()."boss.sqlite3"
         ],
         "mysql" => array_combine(
            ["host", "username", "password", "schema", "port"],
            [$mc["Host"], $mc["User"], $mc["Password"], $mc["Database"], $mc["Port"]]
         )
      ];
      $this->db = libasynql::create($this->plugin, $libasynql_friendly_config, [
         "sqlite" => "sqlite.sql",
         "mysql" => "sqlite.sql"
      ]);
      $this->data = new SQL_BossDataUtils();
      $this->db->executeGeneric("bossplugin.boss.init");
      $this->db->executeSelect("bossplugin.boss.load", [],
         function(array $bossData): void{
            foreach($bossData as $bd){
               $name = $bd["Name"];
               $object[$name] = unserialize($bd["Object"]);
               $this->data->setAll($object);
            }
         }
      );
   }
   
   public function getDatabaseName(): string{
      return $this->db_name;
   }
   
   public function getData(): SQL_BossDataUtils{
      return $this->data;
   }
   
   public function close(): void{
      $this->db->close();
   }
  
   public function reset(): void{
      $this->db->executeChange("bossplugin.boss.reset");
   }
   
   public function saveAll(): void{
      $data = $this->getData()->getAll();
      foreach($this->getAll() as $name){
         $pos = $this->getPosition($name);
         $this->db->executeChange("bossplugin.boss.save", [
            "name" => $name,
            "object" => serialize([
               "entityType" => $this->getEntityType($name),
               "x" => $pos->x,
               "y" => $pos->y,
               "z" => $pos->z,
               "level" => $pos->level->getFolderName(),
               "isrespawntime" => $this->getIsRespawnTime($name),
               "respawntime" => $this->getRespawnTime($name),
               "deathRespawntime" => $this->getDeathRespawnTime($name),
               "health" => $this->getHealth($name),
               "speed" => $this->getSpeed($name),
               "scale" => $this->getScale($name),
               "minDamage" => $this->getMinDamage($name),
               "maxDamage" => $this->getMaxDamage($name),
               "infoDrop" => $this->getInfoDrop($name),
               "commandDrop" => $this->getCommandDrop($name)
            ])
         ]);
      }
   }
   
   public function register(string $name, array $object): void{
      $this->db->executeChange("bossplugin.boss.register", [
         "name" => $name,
         "object" => serialize($object)
      ]);
   }
   
   public function unregister(string $name): void{
      $this->db->executeChange("bossplugin.boss.unregister", [
         "name" => $name
      ]);
   }
   
   public function load(): void{
      
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
      $this->register($name, $object);
      $data = $this->getData()->getAll();
      $data[$name] = $object;
      $this->getData()->setAll($data);
      $this->getData()->save();
   }
   
   public function edit(string $name, array $object): void{
      $data = $this->getData()->getAll();
      $data[$name] = $object;
      $this->getData()->setAll($data);
      $this->getData()->save();
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
      $this->unregister($name);
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
<?php

namespace hmmhmmmm\boss\utils;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

class BossUtils{
   private $plugin;

   public function __construct(){
      $this->plugin = Boss::getInstance();
   }
   
   public function getPlugin(): Boss{
      return $this->plugin;
   }
   
   public function sendTime(int $second): string{
      $lang = $this->plugin->getLanguage();
      $time = $second;
      $days = floor($time / (60 * 60 * 24));
      $time -= $days * (60 * 60 * 24);
      $hours = floor($time / (60 * 60));
      $time -= $hours * (60 * 60);
      $minutes = floor($time / 60);
      $time -= $minutes * 60;
      $seconds = floor($time);
      $time -= $seconds;    
      $ret_val = "";
      $msgDay = $lang->getTranslate("utils.sendtime.msgday");
      $msgHours = $lang->getTranslate("utils.sendtime.msghours");
      $msgMinutes = $lang->getTranslate("utils.sendtime.msgminutes");
      $msgSeconds = $lang->getTranslate("utils.sendtime.msgseconds");
      if($days > 0){
         if($ret_val == ""){
            $ret_val = $days." ".$msgDay;
         }else{
            $ret_val = $ret_val." ".$days." ".$msgDay;
         }
      }
      if($hours > 0 || $days > 0){
         if($ret_val == ""){
            $ret_val = $hours." ".$msgHours;
         }else{
            $ret_val = $ret_val." ".$hours." ".$msgHours;
         }
      }
      if($minutes > 0 || $hours > 0 || $days > 0){
         if($ret_val == ""){
            $ret_val = $minutes." ".$msgMinutes;
         }else{
            $ret_val = $ret_val." ".$minutes." ".$msgMinutes;
         }
      }
      if($seconds > 0 || $minutes > 0 || $hours > 0 || $days > 0){
         if($ret_val == ""){
            $ret_val = $seconds." ".$msgSeconds;
         }else{
            $ret_val = $ret_val." ".$seconds." ".$msgSeconds;
         }
      }
      return $ret_val;
   }
   
   private function makeSlapperNBT(string $type, Player $player, string $name, string $cmd): CompoundTag{
      $nbt = Entity::createBaseNBT($player, null, $player->getYaw(), $player->getPitch());
      $nbt->setShort("Health", 1);
      $cmds = [new StringTag($cmd, $cmd)];
      $nbt->setTag(new CompoundTag("Commands", $cmds));
      $nbt->setString("MenuName", "");
      $nbt->setString("CustomName", $name);
      $nbt->setString("SlapperVersion", "1.5");
      if($type === "Human") {
         $player->saveNBT();
         $inventoryTag = $player->namedtag->getListTag("Inventory");
         assert($inventoryTag !== null);
         $nbt->setTag(clone $inventoryTag);
         $skinTag = $player->namedtag->getCompoundTag("Skin");
         assert($skinTag !== null);
         $nbt->setTag(clone $skinTag);
      }
      return $nbt;
   }
   
   public function makeSlapper(Player $player): void{
      $lang = $this->plugin->getLanguage();
      if(isset($this->plugin->array["slapper"][$player->getName()]["respawn"])){
         $nbt = $this->makeSlapperNBT($this->plugin->getConfig()->getNested("slapper-type"), $player, "{boss_respawn}", "rca {player} boss respawn ".$this->plugin->array["slapper"][$player->getName()]["respawn"]);
         $entity = Entity::createEntity("Slapper".$this->plugin->getConfig()->getNested("slapper-type"), $player->getLevel(), $nbt);
         $entity->setNameTag("{boss_respawn}");
         $entity->setNameTagVisible(true);
         $entity->setNameTagAlwaysVisible(true);
         $entity->namedtag->setString("slapper_Boss", $this->plugin->array["slapper"][$player->getName()]["respawn"]);
         $entity->spawnToAll();
         $player->sendMessage($this->plugin->getPrefix()." ".$lang->getTranslate(
            "utils.makeslapper.complete"
         ));
         unset($this->plugin->array["slapper"][$player->getName()]);
      }
   }
   
}
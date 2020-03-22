<?php

namespace hmmhmmmm\boss\listener;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\utils\BossUtils;
use revivalpmmp\pureentities\entity\monster\Monster;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

class EventListener implements Listener{
   private $plugin;
   private $prefix;
   private $lang;
   
   public function __construct(Boss $plugin){
      $this->plugin = $plugin;
      $this->prefix = $this->plugin->getPrefix();
      $this->lang = $this->plugin->getLanguage();
   }
   
   public function getPlugin(): Boss{
      return $this->plugin;
   }
   
   public function getPrefix(): string{
      return $this->prefix;
   }
   
   public function onEntityDeath(EntityDeathEvent $event){
      $entity = $event->getEntity();
      $cause = $entity->getLastDamageCause();
      $c = $cause === null ? EntityDamageEvent::CAUSE_CUSTOM : $cause->getCause();
      if($c == EntityDamageEvent::CAUSE_ENTITY_ATTACK
         || $c == EntityDamageEvent::CAUSE_PROJECTILE
      ){
         if($cause instanceof EntityDamageByEntityEvent){
            $damager = $cause->getDamager();
            if($damager instanceof Player){
               if(!empty($entity->boss_data)){
                  $bossName = $entity->boss_data;
                  if(BossData::isBoss($bossName)){
                     $bossUtils = new BossUtils();
                     $command = str_replace("{player}", $damager->getName(), BossData::getCommandDrop($bossName));
                     $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                     BossData::setRespawnTime($bossName, BossData::getDeathRespawnTime($bossName));
                     $this->plugin->getServer()->broadcastMessage($this->getPrefix()." ".$this->lang->getTranslate(
                        "boss.death.message1",
                        [$damager->getName(), $bossName, BossData::getInfoDrop($bossName)]
                     ));
                     $this->plugin->getServer()->broadcastMessage($this->getPrefix()." ".$this->lang->getTranslate(
                        "boss.death.message2",
                        [
                           $bossUtils->sendTime(BossData::getDeathRespawnTime($bossName)),
                           $bossName
                        ]
                     ));
                  }
               }
            }
         }
      }
   }
   
   public function onEntityDamage(EntityDamageEvent $event){
      $entity = $event->getEntity();
      if($entity instanceof Player){
         $cause = $entity->getLastDamageCause();
         $c = $cause === null ? EntityDamageEvent::CAUSE_CUSTOM : $cause->getCause();
         if($c == EntityDamageEvent::CAUSE_ENTITY_ATTACK){
            if($cause instanceof EntityDamageByEntityEvent){
               $damager = $cause->getDamager();
               if($damager instanceof Monster){
                  $bossName = $damager->boss_data;
                  if(BossData::isBoss($bossName)){
                     $damager->setMinDamage(BossData::getMinDamage($bossName)); 
                     $damager->setMaxDamage(BossData::getMaxDamage($bossName));
                  }else{
                     $damager->setMinDamage((float) $damager->getMinDamage()); 
                     $damager->setMaxDamage((float) $damager->getMaxDamage());
                  }
               }
            }
         }
      }
   }
   
}
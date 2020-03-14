<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\BaseArgument;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class TargetArgs extends BaseArgument{
   
   public function __construct(string $name, bool $optional = false){
      parent::__construct($name, $optional);
   }
  
   protected function getPlugin(): Quest{
      return $this->plugin;
   }
   
   public function getNetworkType(): int{
      return AvailableCommandsPacket::ARG_TYPE_TARGET;
   }
   
   public function canParse(string $testString, CommandSender $sender): bool{
      return true;
   }
   
   public function parse(string $argument, CommandSender $sender){
      return (string) $argument;
   }
   
   public function getTypeName(): string{
      return "target";
   }
   
}
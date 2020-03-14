<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\network\mcpe\protocol\types\CommandEnum;

use pocketmine\command\CommandSender;

class EnumArgs extends StringEnumArgument{
   public $value = [];
   
   public function __construct(string $name, array $value, bool $optional = false){
      parent::__construct($name, $optional);
      $this->value = $value;
      $this->parameterData->enum = new CommandEnum();
      $this->parameterData->enum->enumName = $this->getEnumName();
      $this->parameterData->enum->enumValues = $this->getEnumValues();
   }
   
   public function parse(string $argument, CommandSender $sender){
      return (string)$this->getValue($argument);
   }
   
   public function getTypeName(): string{
      return "enum";
   }
   
   public function canParse(string $testString, CommandSender $sender): bool{
      return (bool)preg_match(
         "/^(" . implode("|", array_map("\\strtolower", $this->getEnumValues())) . ")$/iu",
         $testString
      );
   }

   public function getValue(string $string){
      if(isset($this->value[$string])){
         return $this->value[$string];
      }else{
         return $string;
      }
   }

   public function getEnumName(): string {
      return "enum";
   }

   public function getEnumValues(): array {
      return array_keys($this->value);
   }
   
}
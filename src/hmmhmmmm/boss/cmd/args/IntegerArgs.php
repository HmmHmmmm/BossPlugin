<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\IntegerArgument;

class IntegerArgs extends IntegerArgument{

   public function getTypeName(): string{
      return "int";
   }
   
}
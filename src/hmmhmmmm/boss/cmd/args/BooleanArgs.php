<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\BooleanArgument;

class BooleanArgs extends BooleanArgument{

   public function getTypeName(): string{
      return "bool";
   }
   
}
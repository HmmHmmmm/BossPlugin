<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\FloatArgument;

class FloatArgs extends FloatArgument{

   public function getTypeName(): string{
      return "float";
   }
   
}
<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\RawStringArgument;

class StringArgs extends RawStringArgument{

   public function getTypeName(): string{
      return "string";
   }
   
}
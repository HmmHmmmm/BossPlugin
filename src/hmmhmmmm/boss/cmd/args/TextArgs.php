<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\TextArgument;

class TextArgs extends TextArgument{

   public function getTypeName(): string{
      return "text";
   }
   
}
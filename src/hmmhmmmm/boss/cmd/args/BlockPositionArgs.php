<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\BlockPositionArgument;

class BlockPositionArgs extends BlockPositionArgument{

   public function getTypeName(): string{
      return "block-position";
   }
   
}
<?php

namespace hmmhmmmm\boss\cmd\args;

use CortexPE\Commando\args\Vector3Argument;

class Vector3Args extends Vector3Argument{

   public function getTypeName(): string{
      return "vector3";
   }
   
}
<?php

namespace hmmhmmmm\boss\database;

use hmmhmmmm\boss\Quest;

interface Database{

   public function getDatabaseName(): string;
   
   public function close(): void;
   
   public function reset(): void;
   
}
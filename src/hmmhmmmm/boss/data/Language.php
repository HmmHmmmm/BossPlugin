<?php

namespace hmmhmmmm\boss\data;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\data\lang\English;
use hmmhmmmm\boss\data\lang\Thai;

use pocketmine\utils\Config;

class Language{
   private $plugin = null;
   private $data = null;
   private $lang = "?";
   private $version = null;
   
   public function __construct(Boss $plugin, string $lang){
      $this->plugin = $plugin;
      $this->lang = $lang;
      $this->data = new Config($this->plugin->getDataFolder()."language/$this->lang.yml", Config::YAML, array());
      $d = $this->data->getAll();
      if(!isset($d["reset"])){
         $this->reset();
      }else{
         if($d["reset"]){
            $this->reset();
            $this->plugin->getLogger()->info("You have reset language ".$this->getLang());
         }
         if(isset($d["version"])){
            $this->version = $d["version"];
            if($this->getVersion() !== 1){
               $this->plugin->getLogger()->info("Language ".$this->getLang()." has been update as version ".$this->getVersion()." please reset language");
            }
         }else{
            $this->reset();
            $this->plugin->getLogger()->info("Language ".$this->getLang()." has been update as version ".$this->getVersion()." therefore language has been reset");
         }
      }
   }
   
   public function getPlugin(): Boss{
      return $this->plugin;
   }
   
   public function getData(): Config{
      return $this->data;
   }
   
   public function getLang(): string{
      return $this->lang;
   }
   
   public function getVersion(): int{
      return $this->version;
   }
   
   public function reset(): void{
      $data = $this->getData();
      if($this->getLang() == "thai"){
         foreach(Thai::init() as $key => $value){
            $data->setNested($key, $value);
         }
      }
      if($this->getLang() == "english"){
         foreach(English::init() as $key => $value){
            $data->setNested($key, $value);
         }
      }
      $data->save();
   }
   
   public function getTranslate(string $key, array $params = []): string{
      $data = $this->getData();
      if(!$data->exists($key)){
         $message = $data->getNested($key);
         for($i = 0; $i < count($params); $i++){
            $message = str_replace("%".($i + 1), $params[$i], $message);
         }
         return $message;
      }else{
         return "§cNot found message ".$key;
      }
   }
   
}
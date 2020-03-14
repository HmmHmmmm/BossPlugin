<?php

namespace hmmhmmmm\boss\ui;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\BossManager;
use hmmhmmmm\boss\utils\BossUtils;
use xenialdan\customui\elements\Button;
use xenialdan\customui\elements\Dropdown;
use xenialdan\customui\elements\Input;
use xenialdan\customui\elements\Label;
use xenialdan\customui\elements\Slider;
use xenialdan\customui\elements\StepSlider;
use xenialdan\customui\elements\Toggle;
use xenialdan\customui\windows\CustomForm;
use xenialdan\customui\windows\ModalForm;
use xenialdan\customui\windows\SimpleForm;

use pocketmine\Player;
use pocketmine\item\Item;

class BossForm{
   private $plugin;
   private $prefix;
   private $lang;
   
   public function __construct(Boss $plugin){
      $this->plugin = $plugin;
      $this->prefix = $this->plugin->getPrefix();
      $this->lang = $this->plugin->getLanguage();
   }
   
   public function getPlugin(): Boss{
      return $this->plugin;
   }
   
   public function getPrefix(): string{
      return $this->prefix;
   }
   
   public function Menu(Player $player, string $content = ""): void{
      $form = new SimpleForm(
         $this->getPrefix()." Data Menu",
         $content
      );
      $button = [];
      $button[] = $this->lang->getTranslate(
         "form.menu.button1"
      );
      $button[] = $this->lang->getTranslate(
         "form.menu.button2"
      );
      if(BossData::getCountBoss() !== 0){
         foreach(BossData::getBoss() as $bossName){
            $button[] = $bossName;
         }
      }
      foreach($button as $buttons){
         $form->addButton(new Button($buttons));
      }
      $form->setCallable(function ($player, $data) use ($button){
         if(!($data === null)){
            switch($data){
               case $button[0]:
                  $this->Create($player);
                  break;
               case $button[1]:
                  $this->CreateObject($player);
                  break;
               default:
                  $this->Edit($player, $data);
                  break;
            }
            
         }
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
   public function Create(Player $player, string $content = ""): void{
      $form = new CustomForm(
         $this->getPrefix()." Create"
      );
      $form->addElement(new Label($content));
      $form->addElement(new Dropdown("§eEntity Type", $this->getPlugin()->entityList)); 
      $input = [
         $this->lang->getTranslate(
            "form.create.input1"
         ) => "test", //ชื่อ
         $this->lang->getTranslate(
            "form.create.input2"
         ) => "600", //รีเกิดเวลา
         $this->lang->getTranslate(
            "form.create.input3"
         ) => "10", //ตายรีเกิดเวลา
         $this->lang->getTranslate(
            "form.create.input4"
         ) => "100", //เลือด
         $this->lang->getTranslate(
            "form.create.input5"
         ) => "1.0", //ความเร็ว
         $this->lang->getTranslate(
            "form.create.input6"
         ) => "1.0", //ขนาด
         $this->lang->getTranslate(
            "form.create.input7"
         ) => "3", //ดาเมจเริ่มต้น
         $this->lang->getTranslate(
            "form.create.input8"
         ) => "5", //ดาเมจสูงสุด
         $this->lang->getTranslate(
            "form.create.input9"
         ) => "Diamond 64", //ข้อความดรอป
         $this->lang->getTranslate(
            "form.create.input10"
         ) => "give {player} 264 64" //คำสั่งดรอป
      ];
      foreach($input as $inputs => $value){
         $form->addElement(new Input($inputs, $value));
      }
      $form->setCallable(function ($player, $data){
         if($data == null){
            return;
         }
         $entityType = $this->getPlugin()->entityList[array_search($data[1], $this->getPlugin()->entityList)];
         $name = explode(" ", $data[2]); 
         if($name[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error1",
               [$this->lang->getTranslate("form.create.input1")]
            );
            $this->Create($player, $text);
            return;
         }
         $name = $name[0];
         $respawntime = explode(" ", (int) $data[3]); 
         if($respawntime[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input2")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($respawntime[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input2")]
            );
            $this->Create($player, $text);
            return;
         }
         $respawntime = $respawntime[0];
         $deathRespawntime = explode(" ", (int) $data[4]); 
         if($deathRespawntime[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input3")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($deathRespawntime[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input3")]
            );
            $this->Create($player, $text);
            return;
         }
         $deathRespawntime = $deathRespawntime[0];
         $health = explode(" ", (int) $data[5]); 
         if($health[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input4")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($health[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input4")]
            );
            $this->Create($player, $text);
            return;
         }
         $health = $health[0];
         $speed = explode(" ", (float) $data[6]); 
         if($speed[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input5")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($speed[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input5")]
            );
            $this->Create($player, $text);
            return;
         }
         $speed = $speed[0];
         $scale = explode(" ", (float) $data[7]); 
         if($scale[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input6")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($scale[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input6")]
            );
            $this->Create($player, $text);
            return;
         }
         $scale = $scale[0];
         $minDamage = explode(" ", (int) $data[8]); 
         if($minDamage[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input7")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($minDamage[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input7")]
            );
            $this->Create($player, $text);
            return;
         }
         $minDamage = $minDamage[0];
         $maxDamage = explode(" ", (int) $data[9]); 
         if($maxDamage[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input8")]
            );
            $this->Create($player, $text);
            return;
         }
         if(!is_numeric($maxDamage[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input8")]
            );
            $this->Create($player, $text);
            return;
         }
         $maxDamage = $maxDamage[0];
         $infoDrop = explode(" ", $data[10]); 
         if($infoDrop[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error1",
               [$this->lang->getTranslate("form.create.input9")]
            );
            $this->Create($player, $text);
            return;
         }
         $infoDrop = $data[10];
         $commandDrop = explode(" ", $data[11]); 
         if($commandDrop[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error1",
               [$this->lang->getTranslate("form.create.input10")]
            );
            $this->Create($player, $text);
            return;
         }
         $commandDrop = $data[11];
         BossData::createBoss($name, $entityType, $player, $respawntime, $deathRespawntime, $health, $speed, $scale, $minDamage, $maxDamage, $infoDrop, $commandDrop);
         $player->sendMessage($this->getPrefix()." ".$this->lang->getTranslate(
            "command.create.complete",
            [$name, $entityType]
         ));
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
   public function CreateObject(Player $player, string $content = ""): void{
      $form = new CustomForm(
         $this->getPrefix()." CreateObject"
      );
      $form->addElement(new Label($content));
      $form->addElement(new Dropdown("§eEntity Type", $this->getPlugin()->entityList)); 
      $input = [
         $this->lang->getTranslate(
            "form.create.input1"
         ) => "test",
         $this->lang->getTranslate(
            "form.create.input4"
         ) => "100"
      ];
      foreach($input as $inputs => $value){
         $form->addElement(new Input($inputs, $value));
      }
      $form->setCallable(function ($player, $data){
         if($data == null){
            return;
         }
         $entityType = $this->getPlugin()->entityList[array_search($data[1], $this->getPlugin()->entityList)];
         $name = explode(" ", $data[2]); 
         if($name[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error1",
               [$this->lang->getTranslate("form.create.input1")]
            );
            $this->CreateObject($player, $text);
            return;
         }
         $name = $name[0];
         $health = explode(" ", (int) $data[3]); 
         if($health[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input4")]
            );
            $this->CreateObject($player, $text);
            return;
         }
         if(!is_numeric($health[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input4")]
            );
            $this->CreateObject($player, $text);
            return;
         }
         $health = $health[0];
         
         $db = Boss::getInstance()->getDatabase();
         $pos = $player;
         $object = [
            "entityType" => $entityType,
            "x" => $pos->x,
            "y" => $pos->y,
            "z" => $pos->z,
            "level" => $pos->level->getFolderName(),
            "isrespawntime" => 600,
            "respawntime" => 600,
            "deathRespawntime" => 5,
            "health" => (float) $health,
            "speed" => (float) 1.0,
            "scale" => (float) 1.0,
            "minDamage" => (float) 5.0,
            "maxDamage" => (float) 5.0,
            "infoDrop" => "???",
            "commandDrop" => "??"
         ];
         $db->create($name, $object);
         BossManager::spawn($name);
         $player->sendMessage($this->getPrefix()." ".$this->lang->getTranslate(
            "command.create.complete",
            [$name, $entityType]
         ));
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
   public function Edit(Player $player, string $bossName, string $content = ""): void{
      $form = new SimpleForm(
         $this->getPrefix()." Edit ".$bossName,
         $content
      );
      $button = [
         $this->lang->getTranslate(
            "form.edit.button1"
         ) => 0,
         $this->lang->getTranslate(
            "form.edit.button2"
         ) => 1,
         $this->lang->getTranslate(
            "form.edit.button3"
         ) => 2,
         $this->lang->getTranslate(
            "form.edit.button4"
         ) => 3,
         $this->lang->getTranslate(
            "form.edit.button5"
         ) => 4
      ];
      foreach($button as $buttons => $value){
         $form->addButton(new Button($buttons));
      }
      $form->setCallable(function ($player, $data) use ($button, $bossName){
         if(!($data === null)){
            switch($button[$data]){
               case 0:
                  $this->Edit2($player, $bossName);
                  break;
               case 1:
                  $this->SetRespawnTime($player, $bossName);
                  break;
               case 2:
                  $bossUtils = new BossUtils();
                  BossData::setRespawnTime($bossName, 3);
                  $player->sendMessage($this->getPrefix()." ".$this->lang->getTranslate(
                     "command.respawn.complete",
                     [$bossUtils->sendTime(3), $bossName]
                  ));
                  break;
               case 3:
                  $bossUtils = new BossUtils();
                  $this->getPlugin()->array["slapper"][$player->getName()]["respawn"] = $bossName;
                  $bossUtils->makeSlapper($player);
                  break;
               case 4:
                  $this->Remove($player, $bossName);
                  break;
            }
            
         }
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
   public function Edit2(Player $player, string $bossName, string $content = ""): void{
      $form = new CustomForm(
         $this->getPrefix()." Edit2 ".$bossName
      );
      $form->addElement(new Label($content));
      $input = [
         $this->lang->getTranslate(
            "form.create.input3"
         ) => "10", //ตายรีเกิดเวลา
         $this->lang->getTranslate(
            "form.create.input4"
         ) => "100", //เลือด
         $this->lang->getTranslate(
            "form.create.input5"
         ) => "1.0", //ความเร็ว
         $this->lang->getTranslate(
            "form.create.input6"
         ) => "1.0", //ขนาด
         $this->lang->getTranslate(
            "form.create.input7"
         ) => "3", //ดาเมจเริ่มต้น
         $this->lang->getTranslate(
            "form.create.input8"
         ) => "5", //ดาเมจสูงสุด
         $this->lang->getTranslate(
            "form.create.input9"
         ) => "Diamond 64", //ข้อความดรอป
         $this->lang->getTranslate(
            "form.create.input10"
         ) => "give {player} 264 64" //คำสั่งดรอป
      ];
      foreach($input as $inputs => $value){
         $form->addElement(new Input($inputs, $value));
      }
      $form->setCallable(function ($player, $data) use ($bossName){
         if($data == null){
            return;
         }
         $deathRespawntime = explode(" ", (int) $data[1]); 
         if($deathRespawntime[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input3")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         if(!is_numeric($deathRespawntime[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input3")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $deathRespawntime = $deathRespawntime[0];
         $health = explode(" ", (int) $data[2]); 
         if($health[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input4")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         if(!is_numeric($health[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input4")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $health = $health[0];
         $speed = explode(" ", (float) $data[3]); 
         if($speed[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input5")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         if(!is_numeric($speed[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input5")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $speed = $speed[0];
         $scale = explode(" ", (float) $data[4]); 
         if($scale[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input6")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         if(!is_numeric($scale[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input6")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $scale = $scale[0];
         $minDamage = explode(" ", (int) $data[5]); 
         if($minDamage[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input7")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         if(!is_numeric($minDamage[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input7")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $minDamage = $minDamage[0];
         $maxDamage = explode(" ", (int) $data[6]); 
         if($maxDamage[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input8")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         if(!is_numeric($maxDamage[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input8")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $maxDamage = $maxDamage[0];
         $infoDrop = explode(" ", $data[7]); 
         if($infoDrop[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error1",
               [$this->lang->getTranslate("form.create.input9")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $infoDrop = $data[7];
         $commandDrop = explode(" ", $data[8]); 
         if($commandDrop[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error1",
               [$this->lang->getTranslate("form.create.input10")]
            );
            $this->Edit2($player, $bossName, $text);
            return;
         }
         $commandDrop = $data[8];
         BossData::editBoss($bossName, $deathRespawntime, $health, $speed, $scale, $minDamage, $maxDamage, $infoDrop, $commandDrop);
         $player->sendMessage($this->getPrefix()." ".$this->lang->getTranslate(
            "command.edit2.complete",
            [$bossName]
         ));
         BossData::setRespawnTime($bossName, 1);
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
   public function SetRespawnTime(Player $player, string $bossName, string $content = ""): void{
      $form = new CustomForm(
         $this->getPrefix()." SetRespawn ".$bossName
      );
      $form->addElement(new Label($content));
      $input = [
         $this->lang->getTranslate(
            "form.create.input2"
         ) => "600" //รีเกิดเวลา
      ];
      foreach($input as $inputs => $value){
         $form->addElement(new Input($inputs, $value));
      }
      $form->setCallable(function ($player, $data) use ($bossName){
         if($data == null){
            return;
         }
         $respawntime = explode(" ", (int) $data[1]); 
         if($respawntime[0] == null){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input2")]
            );
            $this->SetRespawnTime($player, $bossName, $text);
            return;
         }
         if(!is_numeric($respawntime[0])){
            $text = $this->lang->getTranslate(
               "form.create.error2",
               [$this->lang->getTranslate("form.create.input2")]
            );
            $this->SetRespawnTime($player, $bossName, $text);
            return;
         }
         $respawntime = $respawntime[0];
         BossData::setIsRespawnTime($bossName, $respawntime);
         BossData::setRespawnTime($bossName, $respawntime);
         $bossUtils = new BossUtils();
         $player->sendMessage($this->getPrefix()." ".$this->lang->getTranslate(
            "command.setrespawn.complete",
            [$bossUtils->sendTime($respawntime), $bossName]
         ));
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
   public function Remove(Player $player, string $bossName): void{
      $form = new ModalForm(
         $this->getPrefix()." Remove ".$bossName,
         $this->getPrefix()." ".$this->lang->getTranslate(
            "form.remove.content",
            [$bossName]
         ),
         $this->lang->getTranslate(
            "form.remove.button1"
         ),
         $this->lang->getTranslate(
            "form.remove.button2"
         )
      );
      $form->setCallable(function ($player, $data) use ($bossName){
         if(!($data === null)){
            if($data){
               BossData::removeBoss($bossName);
               $player->sendMessage($this->getPrefix()." ".$this->lang->getTranslate(
                  "command.remove.complete",
                  [$bossName]
               ));
            }
         }
      });
      $form->setCallableClose(function (Player $player){
         //??
      });
      $player->sendForm($form);
   }
   
}
<?php

namespace hmmhmmmm\boss\cmd;

use hmmhmmmm\boss\Boss;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\utils\BossUtils;
use hmmhmmmm\boss\cmd\sub\Help;
use hmmhmmmm\boss\cmd\sub\Info;
use hmmhmmmm\boss\cmd\sub\Create;
use hmmhmmmm\boss\cmd\sub\Remove;
use hmmhmmmm\boss\cmd\sub\Respawn;
use hmmhmmmm\boss\cmd\sub\SetRespawn;
use hmmhmmmm\boss\cmd\sub\Slapper_Respawn;
use CortexPE\Commando\BaseCommand;

use pocketmine\Player;
use pocketmine\command\CommandSender;

class BossCommand extends BaseCommand{
   
   public function __construct(){
      parent::__construct("boss", "open ui");
      $this->setPermission("boss.command");
   }
   
   protected function prepare(): void{
      $plugin = Boss::getInstance();
      $subClass = [
         new Help("help",
            $plugin->getLanguage()->getTranslate(
              "command.help.description"
            )
         ),
         new Info("info",
            $plugin->getLanguage()->getTranslate(
              "command.info.description"
            )
         ),
         new Create("create",
            $plugin->getLanguage()->getTranslate(
              "command.create.description"
            )
         ),
         new Remove("remove", 
            $plugin->getLanguage()->getTranslate(
              "command.remove.description"
            )
         ),
         new Respawn("respawn",
            $plugin->getLanguage()->getTranslate(
              "command.respawn.description"
            )
         ),
         new SetRespawn("setrespawn",
            $plugin->getLanguage()->getTranslate(
              "command.setrespawn.description"
            )
         ),
         new Slapper_Respawn("slapper_respawn",
            $plugin->getLanguage()->getTranslate(
              "command.slapper_respawn.description"
            ),
            ["npc_respawn"]
         ),
      ];
      foreach($subClass as $sub){
         $this->registerSubCommand($sub);
      }
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = Boss::getInstance();
      $lang = $plugin->getLanguage();
      if($sender instanceof Player){
         $plugin->getBossForm()->Menu($sender);
         $sender->sendMessage($lang->getTranslate(
            "command.sendHelp.empty"
         ));
      }else{
         $class = new HelpSub("help");
         $class->sendHelp($sender);  
      }
   }
   
}
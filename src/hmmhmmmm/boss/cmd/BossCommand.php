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
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class BossCommand extends BaseCommand implements PluginIdentifiableCommand{
   private $plugin;
   
   public function __construct(Boss $plugin){
      $this->plugin = $plugin;
      parent::__construct("boss", "open ui");
   }
   
   public function getPlugin(): Plugin{
      return $this->plugin;
   }
  
   public function prepare(): void{
      $this->setPermission("bossplugin.command.boss");
      $subClass = [
         new Help($this->getPlugin()),
         new Info($this->getPlugin()),
         new Create($this->getPlugin()),
         new Remove($this->getPlugin()),
         new Respawn($this->getPlugin()),
         new SetRespawn($this->getPlugin()),
         new Slapper_Respawn($this->getPlugin()),
      ];
      foreach($subClass as $sub){
         $this->registerSubCommand($sub);
      }
   }
   
   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
      $plugin = $this->getPlugin();
      $lang = $plugin->getLanguage();
      if($sender instanceof Player){
         $plugin->getBossForm()->Menu($sender);
         $sender->sendMessage($lang->getTranslate(
            "command.sendHelp.empty"
         ));
      }else{
         $class = new Help($plugin);
         $class->sendHelp($sender);  
      }
   }
  
}
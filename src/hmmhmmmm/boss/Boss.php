<?php

namespace hmmhmmmm\boss;


use hmmhmmmm\boss\cmd\BossCommand;
use hmmhmmmm\boss\data\Language;
use hmmhmmmm\boss\database\Database;
use hmmhmmmm\boss\database\mysql\BossData_MySQL;
use hmmhmmmm\boss\database\sqlite\BossData_SQLite;
use hmmhmmmm\boss\database\yaml\BossData_YML;
use hmmhmmmm\boss\listener\EventListener;
use hmmhmmmm\boss\scheduler\BossTask;
use hmmhmmmm\boss\scheduler\SlapperUpdateTask;
use hmmhmmmm\boss\ui\BossForm;
use hmmhmmmm\boss\entity\BossBlaze;
use hmmhmmmm\boss\entity\BossGhast;
use hmmhmmmm\boss\entity\BossVex;
use hmmhmmmm\boss\entity\BossMagmaCube;
use hmmhmmmm\boss\entity\BossSlime;
use hmmhmmmm\boss\entity\BossElderGuardian;
use hmmhmmmm\boss\entity\BossGuardian;
use hmmhmmmm\boss\entity\BossCaveSpider;
use hmmhmmmm\boss\entity\BossCreeper;
use hmmhmmmm\boss\entity\BossEnderman;
use hmmhmmmm\boss\entity\BossEndermite;
use hmmhmmmm\boss\entity\BossEvoker;
use hmmhmmmm\boss\entity\BossHusk;
use hmmhmmmm\boss\entity\BossPolarBear;
use hmmhmmmm\boss\entity\BossIronGolem;
use hmmhmmmm\boss\entity\BossPigZombie;
use hmmhmmmm\boss\entity\BossShulker;
use hmmhmmmm\boss\entity\BossSilverfish;
use hmmhmmmm\boss\entity\BossSkeleton;
use hmmhmmmm\boss\entity\BossSpider;
use hmmhmmmm\boss\entity\BossStray;
use hmmhmmmm\boss\entity\BossWitch;
use hmmhmmmm\boss\entity\BossWitherSkeleton;
use hmmhmmmm\boss\entity\BossWolf;
use hmmhmmmm\boss\entity\BossZombie;
use hmmhmmmm\boss\entity\BossZombiePigman;
use hmmhmmmm\boss\entity\BossZombieVillager;
use xenialdan\customui\API as XenialdanCustomUI;
use CortexPE\Commando\PacketHooker;
use poggit\libasynql\libasynql;

use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;

class Boss extends PluginBase{
   private static $instance = null;
   
   private $prefix = "?";
   private $facebook = "§cwithout";
   private $youtube = "§cwithout";
   private $discord = "§cwithout";
   private $language = null;
   private $slapper = null;
   private $mobai = null;
   private $bossform = null;
   
   public $array = [];
   
   public $database;
    
   private $langClass = [
      "thai",
      "english"
   ];
   
   public $entityList = [
      "Blaze",
      "Ghast",
      "Vex", 
      //"MagmaCube",
      //"Slime",
      "ElderGuardian",
      //"Guardian",
      "CaveSpider",
      "Creeper",
      "Enderman",
      "Endermite",
      "Evoker",
      "Husk",
      "PolarBear",
      //"IronGolem",
      "PigZombie",
      //"Shulker",
      "Silverfish",
      //"Skeleton",
      "Spider",
      //"Stray",
      "Witch",
      "WitherSkeleton",
      "Wolf",
      "Zombie",
      "ZombiePigman",
      "ZombieVillager"
   ];
   
   public $entityClass = [
      BossBlaze::class,
      BossGhast::class,
      BossVex::class,
      //BossMagmaCube::class,
      //BossSlime::class,
      BossElderGuardian::class,
      BossGuardian::class,
      BossCaveSpider::class,
      BossCreeper::class,
      BossEnderman::class,
      BossEndermite::class,
      BossEvoker::class,
      BossHusk::class,
      BossPolarBear::class,
      BossIronGolem::class,
      BossPigZombie::class,
      BossShulker::class,
      BossSilverfish::class,
      BossSkeleton::class,
      BossSpider::class,
      BossStray::class,
      BossWitch::class,
      BossWitherSkeleton::class,
      BossWolf::class,
      BossZombie::class,
      BossZombiePigman::class,
      BossZombieVillager::class
   ];

   public static function getInstance(): Boss{
      return self::$instance;
   }
   
   public function onLoad(): void{
      self::$instance = $this;
   }
   
   public function onEnable(): void{
      @mkdir($this->getDataFolder());
      @mkdir($this->getDataFolder()."language/");
      $this->prefix = "Boss";
      $this->youtube = "https://bit.ly/2HL1j28";
      $langConfig = $this->getConfig()->getNested("language");
      if(!in_array($langConfig, $this->langClass)){
         $this->getLogger()->error("§cNot found language ".$langConfig.", Please try ".implode(", ", $this->langClass));
         $this->getServer()->getPluginManager()->disablePlugin($this);
         return;
      }else{
         $this->language = new Language($this, $langConfig);
         
         $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
         $this->getScheduler()->scheduleRepeatingTask(new SlapperUpdateTask($this), (20 * $this->getConfig()->getNested("slapper-update")));
         $this->getScheduler()->scheduleRepeatingTask(new BossTask($this), 20);
      }
      if($this->getServer()->getPluginManager()->getPlugin("PureEntitiesX") === null){
         $this->getLogger()->error($this->language->getTranslate("notfound.plugin", ["PureEntitiesX"]));
         $this->getServer()->getPluginManager()->disablePlugin($this);
         return;
      }else{
         $this->mobai = $this->getServer()->getPluginManager()->getPlugin("PureEntitiesX");
         foreach($this->entityClass as $bossClass){
            Entity::registerEntity($bossClass);
         }
      }
      if($this->getServer()->getPluginManager()->getPlugin("Slapper") === null){
         $this->getLogger()->error($this->language->getTranslate("notfound.plugin", ["Slapper"]));
         $this->getServer()->getPluginManager()->disablePlugin($this);
         return;
      }else{
         $this->slapper = $this->getServer()->getPluginManager()->getPlugin("Slapper");
      }
      if(!class_exists(XenialdanCustomUI::class)){
         $this->getLogger()->error($this->language->getTranslate("notfound.libraries", ["CustomUI"]));
         $this->getServer()->getPluginManager()->disablePlugin($this);
         return;
      }else{
         $this->bossform = new BossForm($this);
      }
      if(!class_exists(PacketHooker::class)){
         $this->getLogger()->error($this->language->getTranslate("notfound.libraries", ["Commando"]));
         $this->getServer()->getPluginManager()->disablePlugin($this);
         return;
      }else{
         if(!PacketHooker::isRegistered()){
            PacketHooker::register($this);
            $this->getServer()->getCommandMap()->register("BossPlugin", new BossCommand($this));
         }
      }
      if(!class_exists(libasynql::class)){
         $this->getLogger()->error($this->language->getTranslate("notfound.libraries", ["Libasynql"]));
         $this->getServer()->getPluginManager()->disablePlugin($this);
         return;
      }else{
         switch($this->getConfig()->getNested("bossdata-database")){
            case "sqlite":
               $this->database = new BossData_SQLite("SQLite");
               break;
            case "mysql":
               $this->database = new BossData_MySQL("MySQL");
               break;
            case "yml":
               $this->database = new BossData_YML("Yaml");
               break;
            default:
               $this->database = new BossData_YML("Yaml");
               break;
         }
      }
   }
   
   public function getPrefix(): string{
      return "§7[§4".$this->prefix."§7]§f";
   }
   
   public function getFacebook(): string{
      return $this->facebook;
   }
   
   public function getYoutube(): string{
      return $this->youtube;
   }
   
   public function getDiscord(): string{
      return $this->discord;
   }
   
   public function getLanguage(): Language{
      return $this->language;
   }
   
   public function getBossForm(): BossForm{
      return $this->bossform;
   }
   
   public function getDatabase(): Database{
      return $this->database;
   }
   
   public function onDisable(): void{
      if($this->database instanceof Database){
         $this->getDatabase()->close();
      }
   }
   
   public function getPluginInfo(): string{
      $author = implode(", ", $this->getDescription()->getAuthors());
      $arrayText = [
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.name", [$this->getDescription()->getName()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.version", [$this->getDescription()->getVersion()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.author", [$author]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.description"),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.facebook", [$this->getFacebook()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.youtube", [$this->getYoutube()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.website", [$this->getDescription()->getWebsite()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.discord", [$this->getDiscord()]),
      ];
      return implode("\n", $arrayText);
   }
   
}
## QuestPlugin


[Language English](#english)


Create boss has unlimited And can create many forms


download BossPlugin.phar dev https://poggit.pmmp.io/ci/HmmHmmmm/BossPlugin/BossPlugin

BossGhast
![1](https://github.com/HmmHmmmm/BossPlugin/blob/master/images/3.1/1.jpg)

BossSpiderBig
![2](https://github.com/HmmHmmmm/BossPlugin/blob/master/images/3.1/2.jpg)

•••••Bug•••••••••
If you install the plugin TeaSpoon please `false` in
`plugin_data\TeaSpoon\config.yml`
```
entities:
 # Weather to register Vanilla Entities (Set to false if you're going to use a MobAI Plugin such as, PureEntitiesX etc...) or not
 register: true
```
••••••••••••••••••

# English

```diff
You must install the plugin
- Slapper
- PureEntitiesX
this plugin will work
```

**Features of plugin**<br>
- Is a plugin to create boss using [EntityType](#entitytype)
- support database Yaml and SQLite
- Have language thai and english (You can edit the language you don't like at, `plugin_data/QuestPlugin/language`)


**How to use**<br>
- ?


**Command**<br>
- `/boss` : open ui

# EntityType
- Blaze 
- Ghast
- Vex
- MagmaCube `TODO`
- Slime `TODO`
- ElderGuardian
- Guardian `TODO`
- CaveSpider 
- Creeper
- Enderman
- Endermite
- Evoker
- Husk
- PolarBear
- IronGolem `TODO`
- PigZombie
- Shulker `TODO`
- Silverfish
- Skeleton `TODO`
- Spider
- Stray `TODO`
- Witch
- WitherSkeleton
- Wolf
- Zombie
- ZombiePigman
- ZombieVillager

# Features of Boss
- respawn
- death_respawn
- health
- speed
- scale
- min_damage
- max_damage
- message_drop
- command_drop

# Config
```
#thai=ภาษาไทยนะจ้ะ
#english=English language
#You can edit in plugin_data/BossPlugin/language
language: english

#yml=Yaml, Information will be in plugin_data/BossPlugin/boss.yml
#sqlite=SQLite3, Information will be in plugin_data/BossPlugin/boss.sqlite3
bossdata-database: sqlite

slapper-update: 5

slapper-type: Human

#It is now not unusable.
#MySQL-Info:
  #Host: 127.0.0.1
  #User: Admin
  #Password: Admin
  #Database: BossPlugin
  #Port: 3306
```
  

# Permissions
```
boss.command:
  default: op
```



## BossPlugin
[![](https://poggit.pmmp.io/shield.dl.total/BossPlugin)](https://poggit.pmmp.io/p/BossPlugin)

download BossPlugin.phar version dev https://poggit.pmmp.io/ci/HmmHmmmm/BossPlugin/BossPlugin

[Language English](#english)


Create boss has unlimited And can create many forms


![1](https://github.com/HmmHmmmm/BossPlugin/blob/master/images/3.20/1.jpg)


server of you will not lag if you `false` in `plugin_data\PureEntitiesX\config.yml`
```
tasks:
  spawn: false
  async: false
  looking: false
```

•••••Bug•••••••••
If you install the plugin TeaSpoon please `false` in
`plugin_data\TeaSpoon\config.yml`
```
entities:
 register: false
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
- And this plugin will fix some bugs of PureEntitiesX [List](#list)
- Support database Yaml and SQLite
- Have language thai and english (You can edit the language you don't like at, `plugin_data/BossPlugin/language`)


**How to use**<br>
- https://youtu.be/wr5IPWTfFdQ


**Command**<br>
- `/boss` : open ui

# EntityType
- Blaze `TODO`
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
- IronGolem
- SnowGolem `TODO`
- PigZombie
- Shulker
- Silverfish
- Skeleton
- Spider
- Stray
- Vindicator
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

# List fix bug of PureEntitiesX
- PureEntitiesX 0.6.3
- pocketmine\entity\Living::getArmorInventory() null
- getContents() null
- When Ghast shot Fireball will not see.
- Skeleton and Stray don't shot players.


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
bossplugin.command.boss:
  default: op
```



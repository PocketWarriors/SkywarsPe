<?php

/*
__PocketMine Plugin__
class=skywars
-name=skywars
author=Wantedkillers
version=0.5
apiversion=14
*/
// To do 1: Make a sign that shows how many people are in that match and tapping it will let u join unless it's full
// To do 2: Add multiworld support!!!!!!! 3:

namespace Wantedkillers\skywars;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\player;
use pocketmine\item\Item;
use pocketmine\event\Event;
use pocketmine\event\playerInteractEvent
use pocketmine\event\PlayerDeathEvent;
use pocketmine\event\
use pocketmine\event\EntityLevelChangeEvent;
use pocketmine\event\EventExecutor;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;

private $item = $id 347;
private $p = 1;
private $skpod1 = $c1;
private $skpod2 = $c2;
private $skpod3 = $c3;
private $skpod4 = $c4;
private $skpod5 = $c5;
private $skpod6 = $c6;
private $skpod7 = $c7;
private $skpod8 = $c8;
private $skpod9 = $c9;
private $skpod10 = $c10;
private $aworld  = $world;
private $bworld = $world2;
private $cworld = $world3;

class skywars implements Plugin{

    public function __construct(ServerAPI $api, $server = false){
        $this->api = $api;
    public function onLoad(){
    }

     public function onEnable(){
     $this->getLogger()->info(TextFormat::DARK_RED . "SKY" . TextFormat::DARK_BLUE . "WARS" . TextFormat::AQUA . "plugin by Wantedkillers is Loading...");

    public function init(){
        $this->api->addHandler("player.block.touch", array($this, "eventHandler"), 10);
        $this->api->addHandler("player.block.touch", array($this, "eeventHandler"), 10);
        $this->api->addHandler("player.block.touch", array($this, "deadclockevent"), 10);
        $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\BlockBreakEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "eventHandler")), $this, false);
        $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\BlockPlaceEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "eventHandler")), $this, false);
        $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\BlockBreakEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "eeventHandler")), $this, false);
        $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\BlockPlaceEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "eeventHandler")), $this, false);
   $this->api->addhandler("entity.level.change" , array($this, "playerjoin"), 10););
// $this->api->addhandler("tile.level.change" , array($this, "worldSign"), 10););
   $this->api->addhandler("player.death" , array($this, "deadevent"), 10;);
   $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\EntityLevelChangeEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "playerjoin")), $this, false);
   $this->api->addhandler("player.chat" , array($this, "onPlayerChat"), 10;);
   $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\onPlayerChatEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "onPlayerChat")), $this, false);
   $this->getServer()->getPluginManager()->registerEvent("pocketmine\\event\\PlayerDeathEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "deadevent")), $this, false);  
        $this->api->console->register("skywarshowto", "displays a help screen on how to play", array($this, "commandHandler"));
        $command = new PluginCommand("skywarhowto", $this);
        $command->setDescription(" displays a help screen on how to play sky wars" );
        $this->api->console->alias("skhowto", "skywarshowto");
        $this->api->console->alias("skht", "skywarshowto");
        $this->api->console->alias("skhelp", "skywarshelp");
        $this->api->console->alias("skh", "skywarshelp");
        $command->setAliases(array("skhelp", "skh"));
        $this->api->schedule(12000, array($this, "eeventHandler"), array("item 0", "item 1"), false);
        $this->api->schedule(29000, array($this, "gameend"), array("item 2"), false);
        $this->getServer()->getScheduler()->scheduleDelayedTask(
                new MyCallbackPluginTask(array($this, "eeventHandler"), array("item 0", "item 1"), $this), 12000);
        $this->getServer()->getScheduler()->scheduleDelayedTask(
                new MyCallbackPluginTask(array($this, "gameend"), array("item 2"), $this), 29000);
    {
    public function __destruct(){
    public function onDisable(){
   $this->getLogger()->info(TextFormat::GOLD . "Skywars plugin by Wantedkillers is disabling...");
    }
    public function commandHandler($cmd, $params, $issuer, $alias){
        switch($cmd){
    public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
        switch($cmd->getName()){
            case "skywarshowto":
              if($event->getPlayer()->hasPermission("skywarshowto") | |
                $event->getPlayer()->hasPermission("skywars.howto.play") }  {
                $sender->sendMessage("----How To Play skywars----");
        }
    }
    public function eventHandler(PlayerInteractevent $event){
        switch($event){
            case "player.block.touch":
        if($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed skywars ")
                    $event->setCancelled(true);
                     $sender->sendmessage("please wait until the game begins")
                      break;

    public function eeventHandler(PlayerInteractEvent $event){
        switch(get_class($event)){
            case "BlockBreakEvent":
            case "BlockPlaceEvent":
        if($event->getTarget() === $this->aworld) {
  or if($event->getTarget() === $this->bworld) {
  or if($event->getTarget() === $this->cworld) {
             break;
             }
            $player = $event->getEntity();
            if($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the auto Skywars spawn");
        $this->api->schedule(12000, array($this, "eeventHandler"), array("item 0", "item 1"), false);
                  if($task->isfinished);
                    $event->setCancelled(false);
                    $this->server->broadcastMessage("The games have begun go go go!!!!");
                      break;
    public function gamestart(Event $event){
        switch(get_class($event)){
            case "GameStartEvent":
        if ($event->getTarget() === $this->aworld) {
        if ($event->getTarget() === $this->bworld) {
        if ($event->getTarget() === $this->cworld) {
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the auto Skywars spawn");
                break;
                }
                $this->api->get->schedule(29000, array($this, "gameend"), array("item 2"), false);
                  if($task->isfinished);
                 $this->server->broadcastMessage("The game is over thank you for playing Wantedkillers' skywars plugin");
                   $this->api->get->schedule(12000, array($this "eeventhandler"), array("item 0" , "item 1"), false);
                   $this->api->start($task); 
    public function gamestart(Event $event){
        switch(get_class($event)){
            case "GameStartBEvent":
                $this->api->get->schedule(12000, array($this "eeventhandler"), array("item 0" , "item 1"), false);
                  if($task->isfinished);
                $this->api->get->schedule(29000, array($this, "gameend"), array("item 2"), false);
                   $this->api->start($task); 
   }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        if (strtolower($sender->getName()) !== "console") return $this->onCommandByUser($sender, $command, $label, $args);
        switch ($cmd->getName()) {
            case "sk":
                $subCommand = strtolower(array_shift($args));
                switch ($subCommand) {
                    case "":
                    case "skywarshelp":
                        $sender->sendMessage("/skywarshelp");
                     $sender->sendMessage("/skywarshowto");
                        $sender->sendMessage("/skworld (The world that teleports the players to the positions)");
                     //   $sender->sendMessage("/skworld2 (The second world that teleports the players to the positions)");
                      //  $sender->sendMessage("/skworld3 (The third world that teleports the players to the positions)");
                        $sender->sendMessage("/skpod1");
                        $sender->sendMessage("/skpod2");
                        $sender->sendMessage("/skpod 3");
                        $sender->sendMessage("/skpod4");
                        $sender->sendMessage("/skpod5");
                        $sender->sendMessage("/skpod6");
                        $sender->sendMessage("/skpod7");
                        $sender->sendMessage("/skpod8");
                        $sender->sendMessage("/skpod9");
                        $sender->sendMessage("/skpod10");
                        break;

                case "skworld":
                if($event->getPlayer()->hasPermission("skworld") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.world") }  {
                $sender->getLevel() = $this->aworld;
                $sender->sendMessage("[SkywarsPos] World 1 set.");
              // case "skworld2":
               // $sender->getLevel() = $this->bworld;
                //$sender->sendMessage("[SkywarsPos] World 2 set.");
              // case "skworld3":
              //  $sender->getLevel() = $this->cworld;
            //    $sender->sendMessage("[SkywarsPos] World 3 set.");
                case "skpod1":
                if($event->getPlayer()->hasPermission("skypod1") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod1") }  {
                $c1 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 1 at ". $c1 ."created");
                case "skpod2":
               if($event->getPlayer()->hasPermission("skpod2") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod2") }  {
                $c2 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 2 at ". $c2 ."created");
                case "skpod3":
               if($event->getPlayer()->hasPermission("skpod3") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod3") }  {
                $c3 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 3 at ". $c3 ."created");
                case "skpod4":
              if($event->getPlayer()->hasPermission("skpod4") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod4") }  {
                $c4 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 4 at ". $c4 ."created");
                case "skpod5":
              if($event->getPlayer()->hasPermission("skpod5") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod5") }  {
                $c5 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 5 at ". $c5 ."created");
                case "skpod6":
              if($event->getPlayer()->hasPermission("skpod6") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod6") }  {
                $c6 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 6 at ". $c6 ."created");
                case "skpod7":
              if($event->getPlayer()->hasPermission("skpod7") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod7") }  {
                $c7 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 7 at ". $c7 ."created");
                case "skpod8":
              if($event->getPlayer()->hasPermission("skpod8") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod8") }  {
                $c8 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 8 at ". $c8 ."created");
                case "skpod9":
              if($event->getPlayer()->hasPermission("skpod9") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod9") }  {
                $c9 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 9 at ". $c9 ."created");
                case "skpod10":
              if($event->getPlayer()->hasPermission("skpod10") | |
                $event->getPlayer()->hasPermission("skywars.setspawn.pod10") }  {
                $c10 = $sender->getX(), $sender->getY(), $sender->getZ();
                $sender->sendMessage("[SkywarsPos] Position 10 at ". $c10 ."created");
                case "message":
                $this->valve = array_shift($args);
                $this->messege = array_shift($args);
                    if (is_null($this->valve) or is_null($this->messege)) {
                        $sender->sendMessage("Usage: /sk on/off <message>");
                            break;
                        }
                    if ($this->valve === "on") {
                    $sender->sendMessage("[SkywarsMessage] Messages enabled");
                    }
                    else {
                        $sender->sendMessage("[SkywarsMessage] Messages disabled!");
                    }
                case "bypass":
              if($event->getPlayer()->hasPermission("skbypass") | |
                $event->getPlayer()->hasPermission("skywars.event.skbypass") }  {
                $rawnames = array_shift($args);
                $this->bypass = array($rawnames)
                        if (is_null($this->bypass)) {
                            $sender->sendMessage("Usage: /skbypass <player>");
                            break;
                            }
                }
                }
                return true;

            default:
                return false;
        }
    }

    public function onSwitchLevel(EntityLevelChangeEvent $event){
        if ($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the auto Skywars spawn");
                break;
            }
            if ($this->valve === "on") {
            $sender->sendMessage($this->message);
            }
            if($player instanceof Player){
            if ($this->p === 1) {
            $player->teleport(Vector3($this->skpod1));
            $this->p += 1;
            }
            if ($this->p === 2) {
            $player->teleport(Vector3($this->skpod2));
            $this->p += 1;
            }
            if ($this->p === 3) {
            $player->teleport(Vector3($this->skpod3));
            $this->p += 1;
            }
            if ($this->p === 4) {
            $player->teleport(Vector3($this->skpod4));
            $this->p += 1;
            }
            if ($this->p === 5) {
            $player->teleport(Vector3($this->skpod5));
            $this->p += 1;
            }
            if ($this->p === 6) {
            $player->teleport(Vector3($this->skpod6));
            $this->p += 1;
            }
            if ($this->p === 7) {
            $player->teleport(Vector3($this->skpod7));
            $this->p += 1;
            }
            if ($this->p === 8) {
            $player->teleport(Vector3($this->skpod8));
            $this->p += 1;
          }
            if ($this->p === 9) {
            $player->teleport(Vector3($this->skpod9));
            $this->p += 1;
          }
            if ($this->p === 10) {
            $player->teleport(Vector3($this->skpod10));
            $this->p -1= 9;
}
  public function gameend(Event $event){
            case "GameEndEvent":
        if ($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the auto Skywars spawn");
                  break;
                  }
                 $this->api->get->schedule(29000, array($this, "gameend"), array("item 2"), false);
                  if($task->isfinished);
                  $this->api->console->run("kick ", "players");
                 }
 public function deathevent(PlayerDeathEvent $event){
            case "player.death":
        if ($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed skywars");   
                 break;
              }
				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
					$this->dead = true;
	    		$target->setGamemode(2);
			$target->getInventory()->SetItemInHand($item);
          $target->sendMessage("[SkyWars] since you have died you have been put in spectator mode");
          $target->sendMessage("[Skywars] Tap anywhere using the clock to go back to main world");
    }
 public function clockdeathevent(EntityLevelChangeEvent $event){
            case "player.block.touch":
        if ($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the skywars death event");   
                  break;
                  }
				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
					$this->dead = true;
		$defaultLevel = $this->server->getDefaultLevel();
		foreach($this->getPlayers() as $target){
			if($this === $defaultLevel or $defaultLevel === null){
				$player->close(TextFormat::RED . $Target->getName() . " has left the game", "because the default world is removed or not loaded ");
			}elseif($defaultLevel instanceof Level){
				$target->teleport($this->server->getDefaultLevel()->getSafeSpawn());
            }

 public function onPlayerDeath(PlayerDeathEvent $event){
            case "player.death":
        if ($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the skywars death event");   
                  break;
                  }
				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
					$this->dead = true;
            $player = $event->GetEntity();
            $target = $event->GetTarget();
            if($player instanceof Player){
            foreach($player->get->targets() as $t){
            $t->hideTarget($target);
           }
       }
 public function onPlayerChat(PlayerChatEvent $event){
            case "player.chat":
        if ($event->getTarget() === $this->aworld) {
  or if ($event->getTarget() === $this->bworld) {
  or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the skywars death event");   
                  break;
                  }
				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
					$this->dead = true;
            $target = $event->GetTarget();
             $event->setCancelled(true);
              }
       }
 public function renderNameTag($player);
            $username = $target->getname

				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
					$this->dead = true;
            $target = $event->GetTarget();
                 $player->setNameTag(""[Spectator]"$player->getname().");
                   }
                    }else{
               }
                $player->setNameTag(""[PLAYER]"$player->getname().");
                  }
            }

<?php

// To do 1: Make a sign that shows how many people are in that match and tapping it will let u join unless it's full
// To do 2: Add multiworld support!!!!!!! 3:
// To do 3: add /sktime so players can see how much time is left until the game begins
// To do 4: add if an 11th player joins it sends him in spectator mode
// To do 5: add if 5 players wanna start they use /sktimeskip and the game will begin 10 seconds later

// To do 6: add that instead of the players having to break the block itl'l auto break whats under them when the time begins :)
// To do 7: add a config seting so everything can be properly saved and edited☆ done


/*Commands: /skhowto */

namespace SkyWars;

use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\player;
use pocketmine\item\Item;
use pocketmine\event\Event;
use pocketmine\event\playerInteractEvent
use pocketmine\event\PlayerDeathEvent;
use pocketmine\event\EntityLevelChangeEvent;
use pocketmine\event\EventExecutor;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;

public $skywarsstarted = false;
public $config;

class SkyWars extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager->registerEvents($this, $this);
        	$this->getLogger()->info(TextFormat::DARK_RED . "SKY" . TextFormat::DARK_BLUE . "WARS" . TextFormat::AQUA . "plugin by SkyWarsPETeam is Loading...");
        	$this->getServer()->getSchedule()->scheduleRepeatingTask(new Timer($this), 1200); //this runs every second, but maybe will change in every minute
        	//TODO: create a class for the timer
        	$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
                "lobby" => 'world',
                "aworld" => 'swaworld',
                "neededplayers" => '6' //this is just for test
                "spawns" => array(
                    	array(
                        	272,
                        	0,
                        	1
                    	),
                	array(
                		2
                		60,
                        	0,
                        	5
                    	),
			array(
                        	260,
                        	0,
                        	5
                    )
                )
            	));
            	$this->config->save();
            
	}

	public function onDisable(){
        	$this->getLogger()->info(TextFormat::GOLD . "Skywars plugin by SkyWarsPETeam is disabling...");
        	$this->config->save();
        }
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "skywarshowto":
        			if($sender->hasPermission("skywars.command.howto") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")) {
					$sender->sendMessage("----How To Play skywars----");
					$sender->sendMessage("/sk play = start a game");
					$sender->sendMessage("/sk exit = exit from a game");
					$sender->sendMessage("/sk stat [player] = get a player stats");
					return true;
        			}else{
        				$sender->sendMessage("You don't have permissino to run this command");
        			}
			case "skywars" //will set aliases later in plugin.yml
				$params = array_shift($args[0]);
				switch($params){
					case "play":
						//TODO
					case "exit":
						//TODO
					case "stat":
						//TODO
					case "spawnpos":
						if($sender->hasPermission("skywars.command.pos") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							$x = $sender->getX();
							$y = $sender->getY();
							$z = $sender->getZ();
							$this->config->set('spawns', array($x, $y, $z));
							$sender->sendMessage("Spawn position set to: ".$x.", ".$y.", ".$z.", level: ".$sender->getLevel());
						}
				}
			//TODO setpos and setworld and setlobby
		}
	}
	
	public function startGame($level){
		$this->skywarsstarted == true;
		foreach($this->getServer()->getLevel($level)->getPlayers() as $p){
			$x = $p->getGroundX;
			$y = $p->getGroundY;
			$z = $p->getGroundZ;
			//TODO set an air block at $x, $y, $z, to automatically break the block under the player when the game start
		}
	}
	
	public function onLevelChange(EntityLevelChangeEvent $event){
		$p = $event->getEntity();
		if($event->getTarget() == $this->aworld){ // a world
			if(count($this->getServer()->getLevel($this->aworld)->getPlayers()) => $this->neededplayers){
				$event->setCancelled(true);
				$p->sendMessage("The game is full");
			}elseif(count($this->getServer()->getLevel($this->config->get('aworld'))->getPlayers()) == $this->neededplayers){
				$n = count($this->getServer()->getLevel($thisconfig->get('aworld'))->getPlayers());
				$spawn = $this->->config->get('spawns'[$n]); //no need to do + 1 on this, because arrays start counting form 0
				$p->teleport(new Position($spawn[0], $spawn[1], $spawn[2], $this->config->get('aworld'));
                                $this->startGame($this->config->get('aworld'));
                        }elseif(count($this->getServer()->getLevel($this->config->get('aworld'))->getPlayers()) < $this->neededplayers){
                                $n = count($this->getServer()->getLevel($this->config->get('aworld'))->getPlayers());
				$spawn = $this->->config->get('spawns'[$n]); //no need to do + 1 on this, because arrays start counting form 0
				$p->teleport(new Position($spawn[0], $spawn[1], $spawn[2], $this->config->get('aworld'));
      
			}elseif($this->gamestarted == true){
                                $p->sendMessage("The game is already started");
                        }
			//TODO: count if there are enough player to start a game
		}else{
			return;
		}
	}
	
	public function onBlockBreak(BlockBreakEvent $event){
		if($event->getPlayer->getLevel() == $this->config->get('lobby') and !$event->getPlayer->hasPermission("skywars.editlobby") || !$event->getPlayer()->hasPermission("skywars")){
			$event->setCancelled(true);
			$event->getPlayer()->sendMessage("You don't have permission to edit the lobby.");
		}
	}
	
	public function onBlockPlace(BlockPlaceEvent $event){
		if($event->getPlayer->getLevel() == $this->config->get('lobby') and !$event->getPlayer->hasPermission("skywars.editlobby") || !$event->getPlayer()->hasPermission("skywars")){
			$event->setCancelled(true);
			$event->getPlayer()->sendMessage("You don't have permission to edit the lobby.");
		}
	}
	
	public function onPlayerInteract(PlayerInteractEvent $event){
		$ID = $event->getBlock()->getID();
		//TODO sign system
	}
        	
        public function onHurt(EntityDamageByEntityEvent $event){
        	if($event->getEntity()->getLevel() == $this->config->get('lobby')){
        		$event->setCancelled(true);
        		$event->getEntity()->sendMessage("You cannot hurt players in the lobby.");
        	}
        }
        
        public  function onDeath(EntityDeathEvent $event){
        	if($event->getEntity()->getLevel() == $this->config->get('aworld')){
        		if(count($this->getServer->getLevel($this->config->get('aworld'))->getPlayers()) <= 1){
        			foreach($this->getServer->getLevel($this->aworld)->getPlayers() as $p){
        				$p->sendMessage("You won the match!");
        				$p->sendMessage("The game has finished, you will be teleported to the lobby.");
        				$p->teleport($this->getServer()->getLevel($this->config->get('lobby'))->getSafeSpawn());
        				//TODO add points system
        			}
        		}
        	}
        }
}


/*    public function eventHandler(PlayerInteractevent $event){
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

    public function eventHandler(PlayerInteractEvent $event){
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
        $this->api->schedule(12000, array($this, "eventHandler"), array("item 0", "item 1"), false);
                  if($task->isfinished);
                    $event->setCancelled(false);
                    $this->server->broadcastMessage("The games have begun go go go!!!!");
                      break;
    public function gamestart(Event $event){
        switch(get_class($event)){
            case "GameStartEvent":
        if ($event->getTarget() === $this->aworld) {
      //  if ($event->getTarget() === $this->bworld) {
       // if ($event->getTarget() === $this->cworld) {
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed the auto Skywars spawn");
                break;
                }
                $this->api->get->schedule(29000, array($this, "gameend"), array("item 2"), false);
                  if($task->isfinished);
                 $this->server->broadcastMessage("The game is over thank you for playing Wantedkillers' skywars plugin");
                   $this->api->get->schedule(12000, array($this "eventhandler"), array("item 0" , "item 1"), false);
                   $this->api->start($task); 
    public function gamestart(Event $event){
        switch(get_class($event)){
            case "GameStartBEvent":
                $this->api->get->schedule(12000, array($this "eventhandler"), array("item 0" , "item 1"), false);
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
                        $sender->sendMessage("/skpod3");
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
 //or if ($event->getTarget() === $this->bworld) {
 // or if ($event->getTarget() === $this->cworld) {
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
  //or if ($event->getTarget() === $this->bworld) {
  //or if ($event->getTarget() === $this->cworld) {
            $player = $event->getEntity();
            if ($player = $this->bypass) {
                $sender->sendMessage("[SkywarsBypass] You have bypassed skywars");   
                 break;
              }
				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
				$event->setcancelled(true);
	    		$target->setGamemode(2);
			$target->getInventory()->SetItemInHand($itemid "356");
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
            $target = $event->GetTarget();
             $event->setCancelled(true);
              }
       }
 public function renderNameTag($player);
            $username = $target->getname();

				$pk->health = $this->getHealth();
				$this->dataPacket($pk);
				if($this->getHealth() <= 0){
            $target = $event->GetTarget();
                 $player->setNameTag("[Spectator]"$player->getname().);
                   }
                    }else{
               }
                $player->setNameTag("[PLAYER]"$player->getname().);
                  }
            }
*/

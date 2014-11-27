<?php

// To do 1: Add multiworld support!!!!!!! 3:

namespace SkyWarsPETeam;

use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\EventExecutor;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;



class SkyWars extends PluginBase implements Listener{
	

/*Thes are dynamics array, I used them to store some info like: configs, if the game is started and so on.
They can be called using $this->name*/
public $skywarsstarted = false;
public $config;
public $aplayers;
public $inchestedit;
//public $bplayers;
//public $cplayers;

	public function onEnable(){
		$this->getServer()->getPluginManager->registerEvents($this, $this); 
        	$this->getLogger()->info(TextFormat::DARK_RED . "SKY" . TextFormat::DARK_BLUE . "WARS" . TextFormat::AQUA . "plugin by SkyWarsPETeam is Loading...");
        	$this->getServer()->getSchedule()->scheduleRepeatingTask(new Timer($this), 1200); //this runs every second, but maybe will change in every minute
        	//TODO: create a class for the timer
        	$this->saveDefaultConfig();
            	$this->points = new Config($this->getDataFolder()."points.yml", Config::YAML);
            	$this->chestitems = new config($this->getDataFolder()."chestitems.yml", Config::YAML);
            	$this->getConfig()->save();
            	$this->points->save();
            	$this->chestitems->save();
	}

	public function onDisable(){
        	$this->getLogger()->info(TextFormat::GOLD . "Skywars plugin by SkyWarsPETeam is disabling...");
        	$this->getConfig()->save();
        }
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "skywarshowto":
        			if($sender->hasPermission("skywars.command.howto") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")) { 
					$sender->sendMessage("----How To Play skywars----");
                                        $sender->sendMessage("/sk checktime = check the time left");
					$sender->sendMessage("/sk play = start a game");
					$sender->sendMessage("/sk exit = exit from a game");
					$sender->sendMessage("/sk stat [player] = get a player stats");
					return true;
        			}else{
        				$sender->sendMessage("You haven't the permission to run this command");
        			}
			case "skywars" //will set aliases later in plugin.yml
				switch($args[0]){
					case "play":
						if($sender->hasPermission("skywars.command.play") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							if($this->aplayers => $this->getConfig()->get('neededplayers') and $this->skywarsstarted == false){ //if players in the world are more or equal as the max players
								$sender->sendMessage("The game is full"); // game full
								return true;
							}elseif($this->aplayers < $this->getConfig()->get('neededplayers') and $this->skywarsstarted == false){ //if player number is less than the max.
								$n = $this->aplayers; //count the players and store in a variable
								$spawn = $this->getConfig()->get('spawns'[$n]); //no need to do + 1 on this, because arrays start counting form 0 // get the correct spawn place
								$sender->teleport(new Position($spawn[0], $spawn[1], $spawn[2], $this->getConfig()->get('aworld')); //teleport to it
								$this->aplayers = $this->aplayers + 1; //then add a player to the array
								$sender->sendMessage("You have been teleported to the game world.")
      								if($this->aplayers == $this->getConfig()->get('neededplayers')){ //if the players became the same as neededplayers
      									$this->startGame($this->getConfig()>get('aworld')); //start the game
      								} 
      								return true;
							}elseif($this->skywarsstarted == true){ //if the game is already started
                        					$sender->sendMessage("The game is already started");
                        					return true;
                        				}
						}else{
							$sender->sendMessage("You haven't the permission to run this command.");
							return true;
						}
					break;
					case "exit":
						if($sender->hasPermission("skywars.command.exit") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							if($sender->getLevel() == $this->getConfig()->get('aworld')){ //if the level of the sender is a skywars one
								$this->aplayers = $this->aplayers - 1; //remove 1 to the array
								$sender->teleport($this->getServer()->getLevel($this->getConfig()>get('lobby'))->getSafeSpawn); //teleport to the lobby
								$sender->sendMessage("You left the game.");
								if($this->aplayers <= 1){ //if only 1 player is left
        								foreach($this->getServer()->getLevel($this->getConfig()->get('aworld'))->getPlayers() as $p){ //detects the winner
        									if($p->getGameMode() == 0){
        										$p->sendMessage("You won the match!");
        										$p->sendMessage("The game has finished, you will be teleported to the lobby.");
        										$p->teleport($this->getServer()->getLevel($this->config->get('lobby'))->getSafeSpawn()); //teleport to the lobby
        										$points = $this->points->get($p[2]) + $this->getConfig()->get('points-per-match'); //get points and add
        										$deaths = $this->points->get($player[0]); //get the victim's deaths, add one and store in a variable
       											$kills = $this->points->get($player[1]); //get the players kills and store in a var
        										$this->getConfig()->set($p, array($deaths, $kills, $points));
        									}else{
        										$p->sendMessage("The match hs finished, thanks for watching.");
        										$p->teleport($this->getServer()->getLevel($this->getConfig()->get('lobby'))->getSafeSpawn());
        										$p->setGameMode(0);
        									}
        										$this->stopGame($this->getConfig()->get('aworld')); //stop the game
        								}
        							}
								return true;
							}else{
								$sender->sendMessage("You are not in the SkyWars world.");
								return true;
							}
						}else{
							$sender->sendMessage("You haven't the permission to run this command.");
							return true;
						}
					break;
					case "stat":
                                        	if($sender->hasPermission("skywars.command.stat") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
                                        		if(!(isset($args[1]))){
                                        			$player = $sender->getName();
								$deaths = $this->points->get($player[0]);
								$kills = $this->points->get($player[1]);
								$points = $this->points->get($player[2]);
								$sender->sendMessage("You have ".$deaths." deaths, ".$kills." kills and ".$points." points.");
								return true;
                                        		}else{
                                        			$player = $args[1];
								$deaths = $this->points->get($player[0]);
								$kills = $this->points->get($player[1]);
								$points = $this->points->get($player[2]);
								$sender->sendMessage($player." has ".$deaths." deaths, ".$kills." kills and ".$points." points.");
								return true;
                                        		}
                                		}else{
                               	        		$sender->sendMessage("You haven't the permission to run this command.");
							return true;
                               	        	}  
					break;
					case "spawnpos":
						if($sender->hasPermission("skywars.command.pos") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							$x = $sender->getX();
							$y = $sender->getY(); //get coordinates and store in variables
							$z = $sender->getZ();
							$this->getConfig()->add('spawns', array($x, $y, $z)); //add the variables to the config
							$sender->sendMessage("Spawn position set to: ".$x.", ".$y.", ".$z.", level: ".$sender->getLevel());
							return true;
						}else{
							$sender->sendMessage("You haven't the permission to run this command.");
							return true;
						}
					break;
					case "skiptime":
						if($sender->hasPermission("skywars.command.skiptime") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							if($this->aplayers > 3){ //if the players in the array are more than 3
								$this->startGame($sender->getLevel()); //start game on the sender level
								$sender->sendMessage("You started the game skipping the waiting time!");
								return true;
							}else{
								$sender->sendMessage("There are less than 3 players, you can't start the game yet.");
								return true;
							}
						}else{
							$sender->sendMessage("You haven't the permission to run this command.");
							return true;
						}
					break;
					case "left":
						if($sender->hasPermission("skywars.command.left") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							if($sender->getLevel() == $this->getConfig()->get('aworld')){
								$playersleft = $this->getConfig()->get('neededplayers') - $this->aplayers;
								$sender->sendMessage("Players left untill the game begin: ".$playersleft);
								return true;
							}else{
								$sender->sendMessage("You are not in a SkyWars world.");
								return true;
							}
						}else{
							$sender->sendMessage("You haven't the permission to run this command.");
							return true;
						}
					break;
					case "see":
						if($sender->hasPermission("skywars.command.see") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							$sender->sendMessage("You will join a match as a spectator");
							$sender->setGamemode(3);
							$spawn = $this->getConfig()->get('spectatorspawn'); //no need to do + 1 on this, because arrays start counting form 0 // get the correct spawn place
							$sender->teleport(new Position($spawn[0], $spawn[1], $spawn[2], $this->getConfig()->get('aworld'));
						}else{
							$sender->sendMessage("You haven't the permission to run this command.");
							return true;
						}
					break;
					case "capturechest"
					        if($sender->hasPermission("skywars.command.capturechest") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
					                if($sender instanceof Player){
					                $this->inchestedit[$sender->getName()] = true;
					                            $sender->sendMessage("Touch a chest to capture contents.");
					                }
	                                                else{
	                                                	    $sender->sendMessage("Please run command in game.");
	                                                }
	                                           return true;     
					           }
					        }
				}
		}
	}
	

	
	public function onBlockBreak(BlockBreakEvent $event){
		if($event->getPlayer()->getLevel()->getName() == $this->getConfig()->get('lobby') and !$event->getPlayer()->hasPermission("skywars.editlobby") || !$event->getPlayer()->hasPermission("skywars")){ //if level is lobby and player hasn't the permission to modify it
			$event->setCancelled(); // cancel the event
			$event->getPlayer()->sendMessage("You don't have permission to edit the lobby.");
		}
	}
	
	public function onBlockPlace(BlockPlaceEvent $event){
		if($event->getPlayer()->getLevel()->getName() == $this->getConfig()->get('lobby') and !$event->getPlayer->hasPermission("skywars.editlobby") || !$event->getPlayer()->hasPermission("skywars")){
			$event->setCancelled();
			$event->getPlayer()->sendMessage("You don't have permission to edit the lobby.");
		}
		if($event->getPlayer()->getLevel()->getName() == $this->getConfig()->get('aworld') and $event->getPlayer->getGameMode() == 3){
			$event->setCancelled();
		}
	}
	
	public function onLevelChange(EntityLevelChangeEvent $event){
		if($event->getTarget() == $this->getConfig()->get('aworld')){
			foreach($this->getServer()->getLevel($this->getConfig()->get('aworld'))->getPlayers() as $p){
				$p->sendMessage("A player joined the game!");
				$playersleft = $this->getConfig()->get('neededplayers') - $this->aplayers;
				$p->sendMessage("Players left untill the game begin: ".$playersleft)
  				}
		}
	}

	public function onPlayerInteract(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$ID = $event->getBlock()->getID();
                if($ID == 323 or $ID == 63 or $ID == 68){
        		$tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ(),$event->getPlayer()->getLevel()));
        		if($tile instanceof Sign){
        			if($tile->gettext(0)=="[MiniGame]" and $tile->getText(1)=="Skywars" and $tile->gettext(3) == $this->getConfig()->get('aworld')){
        				if($this->aplayers => $this->getConfig()->get('neededplayers') and $this->skywarsstarted == false){ //if players in the world are more or equal as the max players
						$player->sendMessage("The game is full"); // game full
					}elseif($this->aplayers < $this->config->get('neededplayers') and $this->skywarsstarted == false){ //if player number is less than the max.
						$n = $this->aplayers; //count the players and store in a variable
						$spawn = $this->getConfig()->get('spawns'[$n]); //no need to do + 1 on this, because arrays start counting form 0 // get the correct spawn place
						$player->teleport(new Position($spawn[0], $spawn[1], $spawn[2], $this->config->get('aworld')); //teleport to it
						$this->aplayers = $this->aplayers + 1; //then add a player to the array
						$player->sendMessage("You have been teleported to the game world.")
      						if($this->aplayers == $this->getConfig()->get('neededplayers')){ //if the players became the same as neededplayers
      							$this->startGame($this->getConfig()->get('aworld')); //start the game
      						}
					}elseif($this->skywarsstarted == true){ //if the game is already started
                        			$player->sendMessage("The game is already started");
        					
        				}
        			}	
        		}
        	}
	}
        	
        public function onHurt(EntityDamageByEntityEvent $event){
        	if($event->getEntity()->getLevel() == $this->getConfig()->get('lobby')){
        		$event->setCancelled(true); //disable pvp in the lobby
        		$event->getEntity()->sendMessage("You cannot hurt players in the lobby.");
        	}
        }
        
        public  function onDeath(PlayerDeathEvent $event){
        	if($event->getEntity()->getPlayer()->getLevel()->getName() == $this->getConfig()->get('aworld')){ //if in skywars aworld
        		$this->aplayers = $this->aplayers -1; //remove a player
        		$victim = $event->getEntity()->getName();
        		$this->addDeath($victim);
        		$cause = $event->getEntity()->getLastDamageCause();
        		if($cause instanceof EntityDamageByEntityEvent){
				$killer = $cause->getDamager();
				if($killer instanceof Player){
					$this->addKill($killer->getName());
					$event->setDeathMessage($victim."[".$this->getConfig()->get($victim[2])."] was killed by ".$killer->getName()."[".$this->getConfig()->get($killer->getName()[2])."]. ".$this->getConfig()->get('aworld'['neededplayers']) - $this->aplayers." players remaining");
				}
			}else{
					$event->setDeathMessage($victim."[".$this->getConfig()->get($victim[2])."] died.");
			}
        		if($this->aplayers <= 1){ //if only 1 player is left
        			foreach($this->getServer()->getLevel($this->getConfig()->get('aworld'))->getPlayers() as $p){ //detects the winner
        				if($p->getGameMode() == 0){
        					$p->sendMessage("You won the match!");
        					$p->sendMessage("The game has finished, you will be teleported to the lobby.");
        					$p->teleport($this->getServer()->getLevel($this->getConfig()->get('lobby'))->getSafeSpawn()); //teleport to the lobby
        					$points = $this->points->get($p[2]) + $this->config->get('points-per-match'); //get points and add
        					$deaths = $this->points->get($player[0]); //get the victim's deaths, add one and store in a variable
       						$kills = $this->points->get($player[1]); //get the players kills and store in a var
        					$this->config->set($p, array($deaths, $kills, $points));
        				}else{
        					$p->sendMessage("The match hs finished, thanks for watching.");
        					$p->teleport($this->getServer()->getLevel($this->config->get('lobby'))->getSafeSpawn());
        					$p->setGameMode(0);
        				}
        				$this->stopGame($this->config->get('aworld')); //stop the game
        			}
        		}
        	}
        }
        
        public function onChat(PlayerChatEvent $event){
        	$user = event->getPlayer->getName();
        	if($this->config->get('chat-format') == true){
        		$event->setformat("[".$this->points->get($user[2])."]<".$user.">: ".$event->getMessage());
        	}
        }
        
        /*Defining my function to start the game*/
	public function startGame($level){
		$this->skywarsstarted == true; //put the array to true
		foreach($this->getServer()->getLevel($level)->getPlayers() as $p){ //get every single player in the level
			if($p->getGameMode() == 0){
				$x = $p->getGroundX; 
				$y = $p->getGroundY; //get the ground coordinates
				$z = $p->getGroundZ; //these are needed to break the glass under the player
				Server::getInstance()->getLevelByName($level)->setBlock(new Vector3($x,$y,$z), Block::get(0, 0));
				$p->sendMessage("The game starts NOW!! Good luck!");
				$p->sendMessage("You can exit using: /sk exit");
			}
		}
		return true;
	}
	
	public function stopGame($level){
		$this->skywarsstarted == false; //put the array to false
		$this->aplayers == 0; //restore players
		$x ='';
		$y='';
		$z='';
		Server::getInstance()->getLevelByName($level)->setBlock(new Vector3($x,$y,$z), Block::get(20, 0), true); //add block glass 
		//TODO: restore the original map
		return true;
	}
	
	public function addDeath($player){
		if(!$this->points->exist($player)){ //if the name of the victim is not in the config
        			$this->points->set($player, array(1), array(0), array(0)); //set the first death
       		}else{
       			$deaths = $this->points->get($player[0]) + 1; //get the victim's deaths, add one and store in a variable
       			$kills = $this->points->get($player[1]); //get the players kills and store in a var
       			$points = $this->points->get($player[2]) - $this->config->get('points-per-death'); //get the player points
        		$this->points->set($player, array($deaths, $kills, $points)); //set the victim's actual deaths & kills
        	}
        	return true;
	}
	
	public function addKill($player){
		if(!$this->points->exist($player)){ //if the name of the killer is not in the config
        			$this->points->set($player, array(0), array(1), array($this->config->get('points-per-kill'))); //set the first kill
       		}else{
       			$deaths = $this->points->get($player[0]); //get the killer's deaths
       			$kills = $this->points->get($player[1]) + 1; //get the players kills and store in a var
       			$points = $this->points->get($player[2]) + $this->config->get('points-per-kill');
        		$this->points->set($player, array($deaths, $kills, $points)); //set the killer's actual deaths & kills
        	}
        	return true;
	}
	public function onPlayerInteract(PlayerInteractEvent $event){
        if(isset($this->inchestedit[$event->getPlayer()->getName()]); 	
        $ID = $event->getBlock->getID();
        if($ID = 54){
        if($tile instanceof chest){
        $chestpositon = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ(),$event->getPlayer()->getLevel()));
        $chestname = this->getChestname();
        $player->getInventory();
        $chestitems = $inventory->getHotbarSlotItemIDs($ItemIDs);
        if(!$this->chest->exist($chests)){
        $this->chestitems->set($chest, array($chestname, $chestposition, $chestitems));
        if($this->skywarsstarted = true;
        $this->chestitems->get($chests, array($chestname, $chestposition, $chestitems));
        $this->chests->set($chestitems);
        }else{
         $sender->sendMessage("[SkywarsPe] This chest is already in the skywars config file please choose another chest");
        }
	}
}
    
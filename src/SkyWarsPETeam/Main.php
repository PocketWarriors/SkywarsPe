<?php

// To do 1: Make a sign that shows how many people are in that match and tapping it will let u join unless it's full * done
// To do 2: Add multiworld support!!!!!!! 3:
// To do 3: add /sktime so players can see how much time is left until the game begins
// To do 4: add if an 11th player joins it sends him in spectator mode
// To do 5: add if 5 players wanna start they use /sktimeskip and the game will begin 10 seconds later ☆ done

// To do 6: add that instead of the players having to break the block itl'l auto break whats under them when the time begins :)
// To do 7: add a config seting so everything can be properly saved and edited☆ done


/*Commands: /skhowto */

namespace SkyWars;

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

/*Thes are dynamics array, I used them to store some info like: configs, if the game is started and so on.
They can be called using $this->name*/
public $skywarsstarted = false;
public $config;
public $aplayers;
//public $bplayers;
//public $cplayers;


class SkyWars extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager->registerEvents($this, $this); 
        	$this->getLogger()->info(TextFormat::DARK_RED . "SKY" . TextFormat::DARK_BLUE . "WARS" . TextFormat::AQUA . "plugin by SkyWarsPETeam is Loading...");
        	$this->getServer()->getSchedule()->scheduleRepeatingTask(new Timer($this), 1200); //this runs every second, but maybe will change in every minute
        	//TODO: create a class for the timer
        	$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
        	"chat-format" => true,
                "lobby" => 'world',
                "sksign" => 'sign',
                "aworld" => 'swaworld',
                "neededplayers" => '6' //this is just for test
                "spawns" => array(
                    	array(
                        	272,
                        	0,
                        	1
                    	),
                	array(
                		260,
                        	0,
                        	5
                    	),
			array(
                        	260,
                        	0,
                        	5
                       
                       ),
                       array(
                       	        280,
                       	        0,
                       	        5
                       	        
                      ),
                      array(
                      	        292,
                      	        0,
                      	        5
                      	        
                    )
                   
                )
            	));
            	$this->points = new Config($this->getDataFolder()."config.yml", Config::YAML);
            	$this->config->save();
            	$this->points->save();
            
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
                                        $sender->sendMessage("/sk checktime = check the time left");
					$sender->sendMessage("/sk play = start a game");
					$sender->sendMessage("/sk exit = exit from a game");
					$sender->sendMessage("/sk stat [player] = get a player stats");
					return true;
        			}else{
        				$sender->sendMessage("You haven't the permission to run this command");
        			}
			case "skywars" //will set aliases later in plugin.yml
				$params = array_shift($args[0]);
				switch($params){
					case "play":
						if($sender->hasPermission("skywars.command.start") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							if($this->aplayers => $this->config->get('neededplayers') and $this->skywarsstarted == false){ //if players in the world are more or equal as the max players
								$sender->sendMessage("The game is full"); // game full
								return true;
							}elseif($this->aplayers < $this->config->get('neededplayers') and $this->skywarsstarted == false){ //if player number is less than the max.
								$n = $this->aplayers; //count the players and store in a variable
								$spawn = $this->->config->get('spawns'[$n]); //no need to do + 1 on this, because arrays start counting form 0 // get the correct spawn place
								$sender->teleport(new Position($spawn[0], $spawn[1], $spawn[2], $this->config->get('aworld')); //teleport to it
								$this->aplayers = $this->aworld + 1; //then add a player to the array
								$sender->sendMessage("You have been teleported to the game world.")
      								if($this->aplayers == $this->config->get('neededplayers')){ //if the players became the same as neededplayers
      									$this->startGame($this->config->get('aworld')); //start the game
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
							if($sender->getLevel() == $this->config->get('aworld')){ //if the level of the sender is a skywars one
								$this->aplayers = $this->aplayers - 1; //remove 1 to the array
								$sender->teleport($this->getServer()->getLevel($this->config->get('lobby'))->getSafeSpawn); //teleport to the lobby
								$sender->sendMessage("You left the game.");
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
					$this->points->get($player, array($deaths, $kills, $points));
					$sender->sendMessage(.$player."has".$deaths."deaths,".$kills."kills and".$points."points");
					break;
					case "spawnpos":
						if($sender->hasPermission("skywars.command.pos") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							$x = $sender->getX();
							$y = $sender->getY(); //get coordinates and store in variables
							$z = $sender->getZ();
							$this->config->add('spawns', array($x, $y, $z)); //add the variables to the config
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
                                        case "checktime":
			                if($sender->hasPermission("skywars.command.checktime") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
			                if($this->aplayers => $this->config->get('neededplayers') and $this->skywarsstarted == false){                              
                                        $this->startCheck = microtime(true);
                                        $ct = $this->checkTime();
                                       	if($sender->getLevel() == $this->config->get('lobby')){
                                        $sender->sendMessage("[SkywarsPe]There is".$ct."time left until game begins");
                                        return true;
                               }else{
                               	     
                                        break;
					case "left":
						if($sender->hasPermission("skywars.command.left") or $sender->hasPermission("skywars.command") or $sender->hasPermission("skywars")){
							if($sender->getLevel() == $this->config->get('aworld')){
								$playersleft = $this->config->get('neededplayers') - $this->aplayers;
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
				}
		}
	}
	

	
	public function onBlockBreak(BlockBreakEvent $event){
		if($event->getPlayer->getLevel() == $this->config->get('lobby') and !$event->getPlayer->hasPermission("skywars.editlobby") || !$event->getPlayer()->hasPermission("skywars")){ //if level is lobby and player hasn't the permission to modify it
			$event->setCancelled(); // cancel the event
			$event->getPlayer()->sendMessage("You don't have permission to edit the lobby.");
		}
	}
	
	public function onBlockPlace(BlockPlaceEvent $event){
		if($event->getPlayer->getLevel() == $this->config->get('lobby') and !$event->getPlayer->hasPermission("skywars.editlobby") || !$event->getPlayer()->hasPermission("skywars")){
			$event->setCancelled();
			$event->getPlayer()->sendMessage("You don't have permission to edit the lobby.");
		}
	}
	
	public function onLevelChange(EntityLevelChangeEvent $event){
		if($event->getTarget() == $this->config->get('aworld')){
			foreach($this->getServer()->getLevel($this->config->get('aworld'))->getPlayers() as $p){
				$p->sendMessage("A player joined the game!");
				$playersleft = $this->config->get('neededplayers') - $this->aplayers;
				$p->sendMessage("Players left untill the game begin: ".$playersleft)
  }
}

public function onBlockPlace(BlockPlaceEvent $event){
        $ID = $event->getBlock()->getID(323); 
        $neededplayerss = $this->aplayers < $this->config->get('neededplayers') and $this->skywarsstarted == false);
        $players = count($event->getPlayer()->getLevel()->getPlayers());
        if($block instanceof Sign){
            $text = $block->getText();
            if(strtolower($text[0]) == "sksign"($text[1] == "aworld","bworld","cworld"){
                $text[0] = "[SkywarsPe]";
                $text[1] = "aworld|a/b/c";
                $text[2] = "$players/6";
                $text[3] = "$skywarsstarted";
                if($neededplayerss == true);
                $text[3] = "Currently not joinable"
                if($neededplayerss == false);
                $text[3] = "JOINABLE")
            }
            $block->scheduleUpdate();
            return true;
$event->getPlayer->hasPermission("skywars.createsign") || !$event->getPlayer()->hasPermission("skywars")){
$event->setCancelled();
        }
}
	public function onPlayerInteract(PlayerInteractEvent $event){
		$ID = $event->getBlock()->getID(323);
                if($block instanceof sign){
                $text = $block->getText();
                if(strtolower($text[0] == [SkywarsPe]); and ($text[3] = "JOINABLE");
                if($neededplayerss = false)
		$p->teleport($this->world = $this->config->get('aworld');
                $event->getPlayer->hasPermission("skywars.createsign") || !$event->getPlayer()->hasPermission("skywars")){.             
                $event->setcancelled();
                return true;
	}
	      }else{
	      	
	      }
		 return false;
	}
        	
        public function onHurt(EntityDamageByEntityEvent $event){
        	if($event->getEntity()->getLevel() == $this->config->get('lobby')){
        		$event->setCancelled(true); //disable pvp in the lobby
        		$event->getEntity()->sendMessage("You cannot hurt players in the lobby.");
        	}
        }
        
        public  function onDeath(EntityDeathEvent $event){
        	if($event->getEntity()->getLevel() == $this->config->get('aworld')){ //if in skywars aworld
        		$this->aplayers = $this->aplayers -1; //remove a player
        		$victim = $event->getEntity()->getName();
        		$this->addDeath($victim);
        		$cause = $event->getEntity()->getLastDamageCause();
        		if($cause instanceof EntityDamageByEntityEvent){ //TODO: we should test this, I don't know if works
				$killer = $cause->getDamager();
				if($killer instanceof Player){
					$this->addKill($killer);
				}
			}
        		if($this->aplayers <= 1){ //if only 1 player is left
        			foreach($this->getServer->getLevel($this->config->get('aworld'))->getPlayers() as $p){ //detects the winner
        				$p->sendMessage("You won the match!");
        				$p->sendMessage("The game has finished, you will be teleported to the lobby.");
        				$p->teleport($this->getServer()->getLevel($this->config->get('lobby'))->getSafeSpawn()); //teleport to the lobby
        				$points = $this->points->get($p[2]) + $this->config->get('points-per-match'); //get points and add
        				$deaths = $this->points->get($player[0]); //get the victim's deaths, add one and store in a variable
       					$kills = $this->points->get($player[1]); //get the players kills and store in a var
        				$this->config->set($p, array($deaths), array($kills), array($points));
        				$this->stopGame($this->config->get('aworld')); //stop the game
        			}
        		}
        	}
        }
        
        public function onChat(PlayerChatEvent $event){
        	$user = event->getPlayer->getName();
        	if($this->config->get('chat-format') == true){
        		$event->setformat("[".$this->points->get($user2])."]<".$user.">: ".$event->getMessage());
        	}
        }
        
        /*Defining my function to start the game*/
	public function startGame($level){
		$this->skywarsstarted == true; //put the array to true
		foreach($this->getServer()->getLevel($level)->getPlayers() as $p){ //get every single player in the level
			$x = $p->getGroundX; 
			$y = $p->getGroundY; //get the ground coordinates
			$z = $p->getGroundZ; //these are needed to break the glass under the player
			//TODO set an air block at $x, $y, $z, to automatically break the block under the player when the game start
			$p->sendMessage("The game starts NOW!! Good luck!");
			$p->sendMessage("You can exit using: /sk exit");
		}
		return true;
	}
	
	public function stopGame($level){
		$this->skywarsstarted == false; //put the array to false
		$this->aplayers == 0; //restore players
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
}

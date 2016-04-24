<?php
/*
*   _________             __      __ 
*  /   _____/    .__     /  \    /  \
*  \_____  \   __|  |___ \   \/\/   /
*  /        \ /__    __/  \        / 
* /_______  /    |__|      \__/\  /  
*         \/                    \/   
*  ____ ___.__   __  .__                __          
* |    |   \  |_/  |_|__| _____ _____ _/  |_  ____  
* |    |   /  |\   __\  |/     \\__  \\   __\/ __ \ 
* |    |  /|  |_|  | |  |  Y Y  \/ __ \|  | \  ___/ 
* |______/ |____/__| |__|__|_|  (____  /__|  \___  >
*                             \/     \/          \/ 
* 
*  @author remote_vase
*  
*/
namespace SWUltimate\signs;

use SWUltimate\Loader;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\level\Position;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\tile\Tile;


class worldtp extends Loader implements Listener{

    public function playerBlockTouch(PlayerInteractEvent $event){
        $prefix = C::BLACK . "[" . C::AQUA . "S&W Ultimate" . C::BLACK . "]";
        if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
            $sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
            if(!($sign instanceof Sign)){
                return;
            }
            $sign = $sign->getText();
            if($sign[0]=='[WORLD]'){
                if(empty($sign[1]) !== true){
                    $mapname = $sign[1];
                    $event->getPlayer()->sendMessage($prefix . " Preparing world '".$mapname."'");
                    //Prevents most crashes
                    if(Server::getInstance()->loadLevel($mapname) != false){
                        $event->getPlayer()->sendMessage($prefix . " Teleporting...");
                        $event->getPlayer()->teleport(Server::getInstance()->getLevelByName($mapname)->getSafeSpawn());
                            }else{
                	           $event->getPlayer()->sendMessage($prefix . " World '".$mapname."' not found.");
                    }
                }
            }
        }
    }
    
    public function tileupdate(SignChangeEvent $event){
        if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
            //Server::getInstance()->broadcastMessage("lv1");
            $sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
            if(!($sign instanceof Sign)){
                return true;
            }
            $sign = $event->getLines();
            if($sign[0]=='[WORLD]'){
                //Server::getInstance()->broadcastMessage("lv2");
                if($event->getPlayer()->isOp()){
                    //Server::getInstance()->broadcastMessage("lv3");
                    if(empty($sign[1]) !==true){
                        //Server::getInstance()->broadcastMessage("lv4");
                        if(Server::getInstance()->loadLevel($sign[1])!==false){
                            //Server::getInstance()->broadcastMessage("lv5");
                            $event->getPlayer()->sendMessage($prefix . " Sign to world '".$sign[1]."' created. Time to Teleport!");
                            return true;
                        }
                        $event->getPlayer()->sendMessage($prefix . " World '".$sign[1]."' does not exist!");
                        //Server::getInstance()->broadcastMessage("f4");
                        $event->setLine(0,"[Broken Sign]");
                        return false;
                    }
				$event->getPlayer()->sendMessage($prefix . " World name not set");
                    //Server::getInstance()->broadcastMessage("f3");
                    $event->setLine(0,"[Broken Sign]");
                    return false;
                }
            $event->getPlayer()->sendMessage($prefix . " You must be an OP to make a sign that teleports");
            //Server::getInstance()->broadcastMessage("f2");
            $event->setLine(0,"[Broken Sign]");
            return false;
            }
        }
        return true;
    }
}

<?php

namespace Phqzing\AntiInterrupt;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\{PlayerDeathEvent, PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent};

class PlayerListener implements Listener {
  
  
  private $plugin;
  
  
  public function __construct(AntiInterrupt $plugin){
    $this->plugin = $plugin;
  }
  
  
  public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer();
    $this->plugin->fighting[$player->getName()] = "none";
  }
  
  
  public function onQuit(PlayerQuitEvent $ev){
    $player = $ev->getPlayer();
    unset($this->plugin->fighting[$player->getName()]);
    unset($this->plugin->timer[$player->getName()]);
  }
  
  
  public function onDeath(PlayerDeathEvent $ev){
    $player = $ev->getPlayer();
    $cause = $player->getLastDamageCause();
    
    if($cause instanceof EntityDamageByEntityEvent){
      $killer = $cause->getDamager();
      if($killer instanceof Player and $player instanceof Player){
        unset($this->plugin->fighting[$player->getName()]);
        unset($this->plugin->fighting[$killer->getName()]);
        unset($this->plugin->timer[$player->getName()]);
        unset($this->plugin->timer[$killer->getName()]);
      }
    }
  }
  
  
  public function onHit(EntityDamageEvent $ev){
    $player = $ev->getEntity();
    $cause = $player->getLastDamageCause();
    
    if($cause instanceof EntityDamageByEntityEvent){
      $damager = $cause->getDamager();
      if($damager instanceof Player and $player instanceof Player){
        if($this->plugin->getEnemy($damager) === $player->getName() and $this->plugin->getEnemy($player) === $damager->getName()){
          $this->plugin->setTimer($damager, $player);
          return;
        }
        
        if($this->plugin->getEnemy($damager) !== $player->getName() or $this->plugin->getEnemy($player) !== $damager->getName()){
          $ev->setCancelled();
          if($this->plugin->getConfig()->get("send-message") === "true"){
          if($this->plugin->getConfig()->get("send-in-chat") === "true"){
            $msg = $this->plugin->getConfig()->get("message");
            $msg = str_replace(["{damager}", "{victim}"], [$damager->getName(), $player->getName()], $msg);
            $damager->sendMessage($msg);
          }else{
            $msg = $this->plugin->getConfig()->get("message");
            $msg = str_replace(["{damager}", "{victim}"], [$damager->getName(), $player->getName()], $msg);
            $damager->sendPopup($msg);
          }
          }
          return;
        }
        
        if($this->plugin->getEnemy($damager) === "none" and $this->plugin->getEnemy($player) === "none"){
          $this->plugin->setEnemy($damager, $player);
          $this->plugin->setTimer($damager, $player);
        }
      }
    }
  }
}

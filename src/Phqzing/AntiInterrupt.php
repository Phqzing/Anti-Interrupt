<?php

namespace Phqzing;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use Phqzing\tasks\AntiInterruptTask;

class AntiInterrupt extends PluginBase {
  
  
  public $fighting = [];
  public $timer = [];
  
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveDefaultConfig();
    $this->getResource("config.yml");
    $this->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $this);
    $this->getScheduler()->scheduleRepeatingTask(new AntiInterruptTask($this), 20);
  }
  
  
  public function getEnemy(Player $player){
    return $this->fighting[$player->getName()];
  }
  
  public function removeEnemy(Player $player){
    $this->fighting[$player->getName()] = "none";
  }
  
  public function setEnemy(Player $player, Player $enemy){
    $this->fighting[$player->getName()] = $enemy->getName();
    $this->fighting[$enemy->getName()] = $player->getName();
  }
  
  public function setTimer($player, $player2){
    $this->plugin->timer[$player->getName()] = $this->getConfig()->get("timer");
    $this->plugin->timer[$player2->getName()] = $this->getConfig()->get("timer");
  }
}

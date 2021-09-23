<?php

namespace Phqzing\tasks;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use Phqzing\AntiInterrupt;

class AntiInterruptTask extends Task {
  
  
  private $plugin;
  
  
  public function __construct(AntiInterrupt $plugin){ 
    $this->plugin = $plugin;
  }
  
  
  public function onRun(int $tick){
    foreach($this->plugin->timer as $name => $time){
      if($time === 0){
        $player = $this->plugin->getServer()->getPlayerExact();
        if($player instanceof Player){
          $this->plugin->removeEnemy($player);
        }
      }
      $this->plugin->timer[$name]--;
    }
  }
}
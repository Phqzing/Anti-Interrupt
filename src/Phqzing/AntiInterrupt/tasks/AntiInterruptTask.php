<?php

namespace Phqzing\AntiInterrupt\tasks;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use Phqzing\AntiInterrupt\AntiInterrupt;

class AntiInterruptTask extends Task {

    private $plugin;

    public function __construct(AntiInterrupt $plugin)
    {
        $this->plugin = $plugin;
    }


    public function onRun():void
    {
        foreach($this->plugin->timer as $name => $time)
        {
            if($time === 0)
            {
                $player = $this->plugin->getServer()->getPlayerExact($name);
                if($player instanceof Player)
                {
                    $this->plugin->removeEnemy($player);
                }
            }
          $this->plugin->timer[$name]--;
        }
    }
}

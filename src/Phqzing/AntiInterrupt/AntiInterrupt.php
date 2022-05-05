<?php

namespace Phqzing\AntiInterrupt;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use Phqzing\AntiInterrupt\tasks\AntiInterruptTask;

class AntiInterrupt extends PluginBase {

    public $fighting = [];
    public $timer = [];

    public function onEnable():void
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $this);
        $this->getScheduler()->scheduleRepeatingTask(new AntiInterruptTask($this), 20);
    }


    public function getEnemy(Player $player)
    {
        return $this->fighting[$player->getName()];
    }

    public function removeEnemy(Player $player):void
    {
        $this->fighting[$player->getName()] = "none";
    }

    public function setEnemy(Player $player, Player $enemy):void
    {
        $this->fighting[$player->getName()] = $enemy->getName();
        $this->fighting[$enemy->getName()] = $player->getName();
    }

    public function setTimer(Player $player, Player $player2):void
    {
        $this->timer[$player->getName()] = $this->getConfig()->get("timer");
        $this->timer[$player2->getName()] = $this->getConfig()->get("timer");
    }
}

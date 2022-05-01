<?php

namespace Phqzing\AntiInterrupt;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\event\player\{PlayerDeathEvent, PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent};

class PlayerListener implements Listener {
  
    private $plugin;

    public function __construct(AntiInterrupt $plugin)
    {
        $this->plugin = $plugin;
    }


    public function onJoin(PlayerJoinEvent $ev):void
    {
        $player = $ev->getPlayer();
        $this->plugin->fighting[$player->getName()] = "none";
    }


    public function onQuit(PlayerQuitEvent $ev):void
    {
        $player = $ev->getPlayer();
        if(isset($this->plugin->fighting[$player->getName()]))
            unset($this->plugin->fighting[$player->getName()]);
        if(isset($this->plugin->timer[$player->getName()]))
            unset($this->plugin->timer[$player->getName()]);
    }


    public function onDeath(PlayerDeathEvent $ev)
    {
        $player = $ev->getPlayer();
        $cause = $player->getLastDamageCause();

        if($cause instanceof EntityDamageByEntityEvent)
        {
            $killer = $cause->getDamager();
            $level = $killer->getWorld()->getFolderName();
            $level2 = $player->getWorld()->getFolderName();
            if(in_array($level, $this->plugin->getConfig()->get("disabled-worlds")) and $this->plugin->getConfig()->get("allow-disabled-worlds")) return;
            if(in_array($level2, $this->plugin->getConfig()->get("disabled-worlds")) and $this->plugin->getConfig()->get("allow-disabled-worlds")) return;
            if($killer instanceof Player and $killer->isConnected())
            {
                unset($this->plugin->fighting[$player->getName()]);
                unset($this->plugin->fighting[$killer->getName()]);
                unset($this->plugin->timer[$player->getName()]);
                unset($this->plugin->timer[$killer->getName()]);
            }
        }
    }


    public function onHit(EntityDamageEvent $ev):void
    {
        $player = $ev->getEntity();

        if(!($player instanceof Player) or !$player->isConnected()) return;

        if($ev instanceof EntityDamageByEntityEvent)
        {
            $damager = $ev->getDamager();
            if(in_array($damager->getWorld()->getFolderName(), $this->plugin->getConfig()->get("disabled-worlds"))) return;
            if(in_array($player->getWorld()->getFolderName(), $this->plugin->getConfig()->get("disabled-worlds"))) return;
            if(($damager instanceof Player) and $damager->isConnected())
            {
                if($this->plugin->getEnemy($damager) == $player->getName() and $this->plugin->getEnemy($player) == $damager->getName())
                {
                    $this->plugin->setTimer($damager, $player);
                    return;
                }

                if($this->plugin->getEnemy($damager) == "none" and $this->plugin->getEnemy($player) == "none")
                {
                    $this->plugin->setEnemy($damager, $player);
                    $this->plugin->setTimer($damager, $player);
                }

                if($this->plugin->getEnemy($damager) != $player->getName() or $this->plugin->getEnemy($player) != $damager->getName())
                {
                    $ev->cancel();
                    var_dump("passed2");
                    if($this->plugin->getConfig()->get("send-message"))
                    {
                        if($this->plugin->getConfig()->get("send-in-chat"))
                        {
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
            }
        }
    }
}

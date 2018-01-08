<?php

namespace AVENDA;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use VultrM\VultrM;

class Announce extends PluginBase implements Listener {
	public $announce, $aDB;
	public $tag = "§f§l[§cAVAnnounce§f] ";
	public function onEnable() {
		@mkdir ( $this->getDataFolder () );
		$this->announce = new Config ( $this->getDataFolder () . "set.yml", Config::YAML, [ 
				"announce-price" => 500 
		] );
		$this->aDB = $this->announce->getAll ();
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
	}
	public function onCommand(CommandSender $player, Command $cmd, string $label, array $args): bool {
		if ($cmd->getName () == "확성기") {
			if (! isset ( $args [0] )) {
				$player->sendMessage ( $this->tag . "/확성기 [할말] - 사용시 " . $this->aDB ["announce-price"] . "원 차감" );
				return true;
			}
			if (VultrM::getInstance ()->mymoney ( $player ) < $this->aDB ["announce-price"]) {
				$player->sendMessage ( $this->tag . "돈이 부족합니다." );
				return true;
			}
			$msg = implode ( " ", $args );
			$this->getServer ()->broadcastMessage ( "§b======[{$player->getName()}]======" );
			$this->getServer ()->broadcastMessage ( $this->tag . "§f" . $msg );
			$this->getServer ()->broadcastMessage ( "§b======[{$player->getName()}]======" );
			VultrM::getInstance ()->reducemoney ( $player, $this->aDB ["announce-price"] );
			$player->sendMessage ( $this->tag . $this->aDB ["announce-price"] . "원이 차감되었습니다." );
		}
		return true;
	}
}

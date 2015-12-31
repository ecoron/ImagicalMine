<?php

/*
 *
 *  _                       _           _ __  __ _             
 * (_)                     (_)         | |  \/  (_)            
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___  
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \ 
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/ 
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___| 
 *                     __/ |                                   
 *                    |___/                                                                     
 * 
 * This program is a third party build by ImagicalMine.
 * 
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 * 
 *
*/

namespace pocketmine\entity;
use pocketmine\item\Item as Dr;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\network\Network;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

class Ozelot extends Animal implements Tameable{
	const NETWORK_ID = 98;
	public $width = 0.6;
	public $length = 1.8;
	public $height = 1;
 public static $range = 10;
	public static $speed = 0.8;
	public static $jump = 1;
	public static $mindist = 10;
public function initEntity(){
		$this->setMaxHealth(1);
		parent::initEntity();
	}
	public function getName(){
		return "Ocelot";
	}
	 public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Ozelot::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y+2;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));
		$player->addEntityMotion($this->getId(), $this->motionX, $this->motionY, $this->motionZ);
		parent::spawnTo($player);
	}
	public function getDrops(){
		return [];
	}
}

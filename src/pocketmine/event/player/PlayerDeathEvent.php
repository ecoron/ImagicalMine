<?php

/**
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link   http://www.pocketmine.net/
 *
 *
 */

namespace pocketmine\event\player;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\TextContainer;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\entity\ExperienceOrb;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\entity\Entity;

class PlayerDeathEvent extends EntityDeathEvent{
	public static $handlerList = null;

	/** @var TextContainer|string */
	private $deathMessage;
	private $keepInventory = false;

	/**
	 * @param Player $entity
	 * @param Item[] $drops
	 * @param string|TextContainer $deathMessage
	 */
	public function __construct(Player $entity, array $drops, $deathMessage){
		parent::__construct($entity, $drops);
		$this->deathMessage = $deathMessage;

		if($entity->getLastDamageCause() instanceof EntityDamageByEntityEvent){
			if($entity->getLastDamageCause() instanceof Player){
				$pk = new AddEntityPacket();
				$pk->type = ExperienceOrb::NETWORK_ID;
				$pk->eid = Entity::$entityCount++;
				$pk->x = $entity->getX();
				$pk->y = $entity->getY();
				$pk->z = $entity->getZ();
				$pk->speedX = 0;
				$pk->speedY = 0;
				$pk->speedZ = 0;
				$pk->metadata = [
					0 => [0, 0],
					1 => [1, 300],
					2 => [4, ""],
					3 => [0, 1],
					4 => [0, 0],
					15 => [0, 0],
				];

				foreach($this->entity->getViewers() as $pa){
					$pa->dataPacket($pk);
				}
			}
		}
	}

	/**
	 * @return Player
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * @return TextContainer|string
	 */
	public function getDeathMessage(){
		return $this->deathMessage;
	}

	/**
	 * @param string|TextContainer $deathMessage
	 */
	public function setDeathMessage($deathMessage){
		$this->deathMessage = $deathMessage;
	}

	public function getKeepInventory(){
		return $this->keepInventory;
	}

	public function setKeepInventory($keepInventory){
		$this->keepInventory = (bool) $keepInventory;
	}

}
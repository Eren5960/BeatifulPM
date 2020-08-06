<?php
/**
 *  _______                   _______ _______ _______  _____
 * (_______)                 (_______|_______|_______)(_____)
 *  _____    ____ _____ ____  ______  _______ ______  _  __ _
 * |  ___)  / ___) ___ |  _ \(_____ \(_____  |  ___ \| |/ /| |
 * | |_____| |   | ____| | | |_____) )     | | |___) )   /_| |
 * |_______)_|   |_____)_| |_(______/      |_|______/ \_____/
 *
 * @author Eren5960
 * @link   https://github.com/Eren5960
 * @date   05 Mayıs 2020
 */
declare(strict_types=1);

namespace Eren5960\BeautifulPM\inventory;

use Eren5960\BeautifulPM\sound\BarrelCloseSound;
use Eren5960\BeautifulPM\sound\BarrelOpenSound;
use pocketmine\block\inventory\ChestInventory;
use pocketmine\world\sound\Sound;
use pocketmine\player\Player;
use Eren5960\BeautifulPM\block\Barrel;
use pocketmine\network\mcpe\protocol\BlockEventPacket;

class BarrelInventory extends ChestInventory{

	protected function getOpenSound(): Sound{
		return new BarrelOpenSound();
	}

	protected function getCloseSound(): Sound{
		return new BarrelCloseSound();
	}

	public function onOpen(Player $who) : void{
		parent::onOpen($who);

		if(count($this->getViewers()) === 1 and $this->getHolder()->isValid()){
			//TODO: this crap really shouldn't be managed by the inventory
			$block = $this->getHolder()->getWorld()->getBlock($this->getHolder());
			if($block instanceof Barrel){
				$this->broadcastBlockEventPacket(true);
				$block->setOpen(true);
			}
			$this->getHolder()->getWorld()->addSound($this->getHolder()->add(0.5, 0.5, 0.5), $this->getOpenSound());
		}
	}

	public function onClose(Player $who) : void{
		if(count($this->getViewers()) === 1 and $this->getHolder()->isValid()){
			//TODO: this crap really shouldn't be managed by the inventory
			$block = $this->getHolder()->getWorld()->getBlock($this->getHolder());
			if($block instanceof Barrel){
				$this->broadcastBlockEventPacket(false);
				$block->setOpen(false);
			}
			$this->getHolder()->getWorld()->addSound($this->getHolder()->add(0.5, 0.5, 0.5), $this->getCloseSound());
		}
		parent::onClose($who);
	}

	protected function broadcastBlockEventPacket(bool $isOpen) : void{
		$holder = $this->getHolder();

		//event ID is always 1 for a chest
		$holder->getWorld()->broadcastPacketToViewers($holder, BlockEventPacket::create(1, $isOpen ? 1 : 0, $holder->asVector3()));
	}
}
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
 * @link https://github.com/Eren5960
 * @date 05 Mayıs 2020
 */
declare(strict_types=1);
 
namespace Eren5960\BeatifulPM\inventory;

use Eren5960\BeatifulPM\block\Barrel;
use Eren5960\BeatifulPM\sound\BarrelCloseSound;
use Eren5960\BeatifulPM\sound\BarrelOpenSound;
use pocketmine\inventory\ChestInventory;
use pocketmine\world\sound\Sound;

class BarrelInventory extends ChestInventory{

	protected function getOpenSound() : Sound{
		return new BarrelOpenSound();
	}

	protected function getCloseSound() : Sound{
		return new BarrelCloseSound();
	}

	protected function broadcastBlockEventPacket(bool $isOpen) : void{
		$barrel = $this->getHolder()->getWorldNonNull()->getBlock($this->getHolder());
		if($barrel instanceof Barrel){
			$barrel->setOpen($isOpen);
		}
	}
}
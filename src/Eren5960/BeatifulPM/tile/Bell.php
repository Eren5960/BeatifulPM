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
 * @date 03 Mayıs 2020
 */
declare(strict_types=1);
 
namespace Eren5960\BeatifulPM\tile;
 
use pocketmine\block\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;

class Bell extends Spawnable{
	public const BELL_ATTACHMENT = 'attachment';
	/** @var string */
	public $attachment = 'standing';

	public function readSaveData(CompoundTag $nbt) : void{
		$this->attachment = $nbt->getString(self::BELL_ATTACHMENT, 'standing');
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$nbt->setString(self::BELL_ATTACHMENT, $this->attachment);
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		$nbt->setString(self::BELL_ATTACHMENT, $this->attachment);
	}
}
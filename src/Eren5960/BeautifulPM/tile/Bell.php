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
 
namespace Eren5960\BeautifulPM\tile;
 
use Eren5960\BeautifulPM\utils\BellAttachments;
use pocketmine\block\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;

class Bell extends Spawnable{
	public const TAG_ATTACHMENT = 'attachment';

	/** @var string */
	protected $attachment = BellAttachments::DEFAULT;

    public function readSaveData(CompoundTag $nbt) : void{
		$this->attachment = $nbt->getString(self::TAG_ATTACHMENT, $this->attachment);
	}

    protected function writeSaveData(CompoundTag $nbt) : void{
		$nbt->setString(self::TAG_ATTACHMENT, $this->attachment);
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		$nbt->setString(self::TAG_ATTACHMENT, $this->attachment);
	}

    /**
     * @return string
     */
    public function getAttachment(): string{
        return $this->attachment;
    }

    /**
     * @param string $attachment
     */
    public function setAttachment(string $attachment): void{
        $this->attachment = $attachment;
    }
}
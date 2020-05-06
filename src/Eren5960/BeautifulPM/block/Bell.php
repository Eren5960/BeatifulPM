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
 * @date 02 Mayıs 2020
 */
declare(strict_types=1);

namespace Eren5960\BeautifulPM\block;

use pocketmine\block\Block;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\EventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Bell extends Block{ // TODO
	/** @var int */
	protected $facing = Facing::NORTH;

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$this->getPos()->getWorldNonNull()->broadcastLevelEvent($this->getPos(), LevelSoundEventPacket::SOUND_BLOCK_BELL_HIT, 1000);
		$this->getPos()->getWorldNonNull()->broadcastPacketToViewers($this->getPos(), BlockEventPacket::create(EventPacket::TYPE_BELL_BLOCK_USED, 1000, $this->getPos()));
		return true;
	}

	protected function writeStateToMeta() : int{
		return BlockDataSerializer::writeHorizontalFacing($this->facing);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->facing = BlockDataSerializer::readHorizontalFacing($stateMeta);
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player !== null){
			$this->facing = Facing::opposite($player->getHorizontalFacing());
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}
}
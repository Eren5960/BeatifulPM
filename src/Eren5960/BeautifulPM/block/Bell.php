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
 * @date   02 Mayıs 2020
 */
declare(strict_types=1);

namespace Eren5960\BeautifulPM\block;

use Eren5960\BeautifulPM\sound\BellUsedSound;
use Eren5960\BeautifulPM\tile\Bell as BellTile;
use Eren5960\BeautifulPM\utils\BellAttachments;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Transparent;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\EventPacket;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Bell extends Transparent{
	/** @var int */
	public $facing = Facing::NORTH;
	/** @var string */
	public $attachment;

	public function __construct(BlockIdentifier $idInfo, string $name, BlockBreakInfo $breakInfo = null){
		parent::__construct($idInfo, $name, $breakInfo ?? new BlockBreakInfo(5, BlockToolType::PICKAXE, 0, 5));
		$this->attachment = BellAttachments::DEFAULT;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null): bool{
		$pos = $this->getPos();
		$pos->getWorld()->addSound($pos, new BellUsedSound());
		$pos->getWorld()->broadcastPacketToViewers($pos, BlockEventPacket::create(EventPacket::TYPE_BELL_BLOCK_USED, 10000, $pos));
		return true;
	}

	protected function writeStateToMeta(): int{
		return $this->facing << 1;
	}

	public function readStateFromData(int $id, int $stateMeta): void{
		$this->facing = $stateMeta;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool{
		if($player !== null){
			$this->facing = Facing::opposite($player->getHorizontalFacing());
		}

		$isEast = $blockReplace->getSide(Facing::EAST)->getId() !== 0;
		$isWest = $blockReplace->getSide(Facing::WEST)->getId() !== 0;

		$isSouth = $blockReplace->getSide(Facing::SOUTH)->getId() !== 0;
		$isNorth = $blockReplace->getSide(Facing::NORTH)->getId() !== 0;

		$isUp = $blockReplace->getSide(Facing::UP)->getId() !== 0;

		if($isUp){
			$this->attachment = BellAttachments::HANGING;
		}elseif($isEast || $isWest){
			$this->attachment = $isEast && $isWest ? BellAttachments::MULTIPLE : BellAttachments::SIDE;
		}elseif($isSouth || $isNorth){
			$this->attachment = $isSouth && $isNorth ? BellAttachments::MULTIPLE : BellAttachments::SIDE;
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function readStateFromWorld(): void{
		parent::readStateFromWorld();

		$tile = $this->pos->getWorld()->getTile($this->pos);
		if($tile instanceof BellTile){
			$this->attachment = $tile->getAttachment();
		}
	}

	public function writeStateToWorld(): void{
		parent::writeStateToWorld();

		$tile = $this->pos->getWorld()->getTile($this->pos);
		if($tile instanceof BellTile){
			$tile->setAttachment($this->attachment);
		}
	}
}
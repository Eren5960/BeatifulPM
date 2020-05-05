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
 
namespace Eren5960\BeatifulPM\block;
 
use Eren5960\BeatifulPM\tile\Barrel as TileBarrel;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\Opaque;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Barrel extends Opaque{
	/** @var int */
	protected $facing = Facing::UP;
	/** @var bool */
	public $open = false;

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null){
		parent::__construct($idInfo, "Barrel", $breakInfo ?? new BlockBreakInfo(2.5, BlockToolType::AXE));
	}

	protected function writeStateToMeta() : int{
		return BlockDataSerializer::writeFacing($this->facing) | ($this->open ? 1 : 0);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->facing = BlockDataSerializer::readFacing($stateMeta);
		$this->open = ($stateMeta & 0x01) !== 0;
	}

	public function readStateFromWorld() : void{
		parent::readStateFromWorld();

		$tile = $this->pos->getWorldNonNull()->getTile($this->pos);
		if($tile instanceof TileBarrel){
			$this->open = $tile->isOpen();
		}
	}

	public function writeStateToWorld() : void{
		parent::writeStateToWorld();

		$tile = $this->pos->getWorldNonNull()->getTile($this->pos);
		if($tile instanceof TileBarrel){
			$tile->setOpen($this->open);
		}
	}

	public function getStateBitmask() : int{
		return 0b111;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player !== null){
			$this->facing = Facing::opposite($this->getPlayerFacing($player));
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player instanceof Player){
			$barrel = $this->pos->getWorldNonNull()->getTile($this->pos);
			if($barrel instanceof TileBarrel){
				if(!$barrel->canOpenWith($item->getCustomName())){
					return true;
				}

				$player->setCurrentWindow($barrel->getInventory());
			}
		}

		return true;
	}

	public function getFuelTime() : int{
		return 150;
	}

	public function getPlayerFacing(Player $player) : int{
		$angle = $player->getLocation()->yaw % 360;
		$pitch = $player->getLocation()->pitch % 90;

		if($angle < 0){
			$angle += 360.0;
		}

		if($pitch > 54){
			return Facing::DOWN;
		}

		if($pitch < -10){
			return Facing::UP;
		}

		if((0 <= $angle and $angle < 45) or (315 <= $angle and $angle < 360)){
			return Facing::SOUTH;
		}
		if(45 <= $angle and $angle < 135){
			return Facing::WEST;
		}
		if(135 <= $angle and $angle < 225){
			return Facing::NORTH;
		}

		return Facing::EAST;
	}

	public function setOpen(bool $isOpen): void{
		if($this->open !== $isOpen){
			$this->open = $isOpen;
			$this->getPos()->getWorldNonNull()->setBlock($this->getPos(), $this);
		}
	}
}
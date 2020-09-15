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

namespace Eren5960\BeautifulPM\block;

use Eren5960\BeautifulPM\tile\Barrel as TileBarrel;
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
    public const BARREL_OPEN_BYTE = 0x08;
	/** @var int */
	protected $facing = Facing::NORTH;
	/** @var bool */
	public $open = false;

	public function __construct(BlockIdentifier $idInfo, ?BlockBreakInfo $breakInfo = null){
		parent::__construct($idInfo, "Barrel", $breakInfo ?? new BlockBreakInfo(2.5, BlockToolType::AXE));
	}

	protected function writeStateToMeta() : int{
		return BlockDataSerializer::writeFacing($this->facing) | ($this->open ? self::BARREL_OPEN_BYTE : 0);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->facing = BlockDataSerializer::readFacing($stateMeta & 0x07);
		$this->open = ($stateMeta & self::BARREL_OPEN_BYTE) === self::BARREL_OPEN_BYTE;
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function isOpen() : bool{
		return $this->open;
	}

	public function setOpen(bool $open) : void{
		$this->open = $open;
		$this->pos->getWorld()->setBlock($this->pos, $this);
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player !== null){
			if(abs($player->getLocation()->getX() - $this->pos->getX()) < 2 && abs($player->getLocation()->getZ() - $this->pos->getZ()) < 2){
				$y = $player->getPosition()->getY() + $player->getEyeHeight();

				if($y - $this->pos->getY() > 2){
					$this->facing = Facing::UP;
				}elseif($this->pos->getY() - $y > 0){
					$this->facing = Facing::DOWN;
				}else{
					$this->facing = Facing::opposite($player->getHorizontalFacing());
				}
			}else{
				$this->facing = Facing::opposite($player->getHorizontalFacing());
			}
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player instanceof Player){

			$barrel = $this->pos->getWorld()->getTile($this->pos);
			if($barrel instanceof TileBarrel){
				if(!$barrel->canOpenWith($item->getCustomName())){
					return true;
				}

				$player->setCurrentWindow($barrel->getInventory());
			}
		}

		return true;
	}

	public function getFuelTime(): int{
		return 300;
	}
}
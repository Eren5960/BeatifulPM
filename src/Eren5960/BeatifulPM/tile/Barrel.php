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
 
namespace Eren5960\BeatifulPM\tile;
 
use Eren5960\BeatifulPM\inventory\BarrelInventory;
use pocketmine\block\tile\Container;
use pocketmine\block\tile\ContainerTrait;
use pocketmine\block\tile\Nameable;
use pocketmine\block\tile\NameableTrait;
use pocketmine\block\tile\Spawnable;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;

class Barrel extends Spawnable implements Container, Nameable{
	use NameableTrait{
		addAdditionalSpawnData as addNameData;
	}
	use ContainerTrait;
	public const TAG_OPEN = 'open';

	/** @var BarrelInventory */
	public $inventory;
	/** @var bool */
	protected $open = false;

	public function __construct(World $world, Vector3 $pos){
		parent::__construct($world, $pos);
		$this->inventory = new BarrelInventory($this->pos);
	}

	public function getRealInventory(){
		return $this->inventory;
	}

	public function getInventory(){
		return $this->inventory;
	}

	public function getDefaultName() : string{
		return "Barrel";
	}

	public function readSaveData(CompoundTag $nbt) : void{
		$this->loadName($nbt);
		$this->loadItems($nbt);
		$this->open = boolval($nbt->getByte(self::TAG_OPEN, 0));
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$this->saveName($nbt);
		$this->saveItems($nbt);
		$nbt->setByte(self::TAG_OPEN, intval($this->open));
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		$this->addNameData($nbt);
		$nbt->setByte(self::TAG_OPEN, intval($this->open));
	}

	public function setOpen(bool $isOpen): void{
		$this->open = $isOpen;
		$this->setDirty(true);
	}

	public function isOpen(): bool{
		return $this->open;
	}
}
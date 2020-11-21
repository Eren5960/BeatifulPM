<?php

namespace Eren5960\BeautifulPM\block;


use Eren5960\BeautifulPM\utils\ComposterRate;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\tile\Hopper;
use pocketmine\block\Transparent;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\player\Player;

class Composter extends Transparent {
    /** @var int */
    public $composter_fill_level;
    /** @var bool */
    public $ready = false;

    public function __construct(BlockIdentifier $idInfo, string $name, BlockBreakInfo $breakInfo = null){
        parent::__construct($idInfo, $name, $breakInfo ?? new BlockBreakInfo(0.6, BlockToolType::AXE, 0, 0.6));
    }

    protected function writeStateToMeta() : int{
        return $this->composter_fill_level;
    }

    public function readStateFromData(int $id, int $stateMeta) : void{
        $this->composter_fill_level = BlockDataSerializer::readBoundedInt("composter_fill_level", $stateMeta, 0, 8);
    }

    public function getStateBitmask() : int{
        return 0b1111;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null): bool{
        if ($this->ready) return false;
        if ($this->composter_fill_level >= 8) {
            $this->composter_fill_level = 0;
            $this->pos->getWorld()->setBlock($this->pos, $this);
            $downTile = $this->pos->getWorld()->getTile($this->pos->down());
            if ($downTile instanceof Hopper && $downTile->getInventory()->canAddItem(VanillaItems::BONE_MEAL())) {
                $downTile->getInventory()->addItem(VanillaItems::BONE_MEAL());
            } else {
                $this->getPos()->getWorld()->dropItem($this->pos->up(), VanillaItems::BONE_MEAL());
            }
            $this->pos->getWorld()->broadcastPacketToViewers($this->pos, LevelSoundEventPacket::create(LevelSoundEventPacket::SOUND_BLOCK_COMPOSTER_EMPTY, $this->pos));
            return true;
        }
        $rate = ComposterRate::getRate($item);
        if ($rate !== null) {
            $item->pop();

            if (rand(0, 99) <= $rate){
                $this->pos->getWorld()->broadcastPacketToViewers($this->pos, LevelSoundEventPacket::create(LevelSoundEventPacket::SOUND_BLOCK_COMPOSTER_FILL_SUCCESS, $this->pos));
                $this->composter_fill_level++;

                if ($this->composter_fill_level >= 7) {
                    $this->ready = true;
                    $this->pos->getWorld()->scheduleDelayedBlockUpdate($this->pos, 15);
                } else {
                    $this->pos->getWorld()->setBlock($this->pos, $this);
                }
            } else {
                $this->pos->getWorld()->broadcastPacketToViewers($this->pos, LevelSoundEventPacket::create(LevelSoundEventPacket::SOUND_BLOCK_COMPOSTER_FILL, $this->pos));
            }
            return true;
        }
        return false;
    }

    public function onScheduledUpdate(): void{
        $this->ready = false;
        $this->composter_fill_level = 8;
        $this->pos->getWorld()->setBlock($this->pos, $this);
        $this->pos->getWorld()->broadcastPacketToViewers($this->pos, LevelSoundEventPacket::create(LevelSoundEventPacket::SOUND_BLOCK_COMPOSTER_READY, $this->pos));
    }
}
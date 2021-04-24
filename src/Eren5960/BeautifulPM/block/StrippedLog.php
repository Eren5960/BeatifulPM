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
 * @date   06 Mayıs 2020
 */
declare(strict_types=1);

namespace Eren5960\BeautifulPM\block;

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

class StrippedLog extends Opaque {
    public $facing = Facing::UP;

    public function __construct(
        BlockIdentifier $idInfo,
        string $name,
        ?BlockBreakInfo $breakInfo = null
    ) {
        parent::__construct(
            $idInfo,
            'Stripped ' . $name,
            $breakInfo ?? new BlockBreakInfo(2.0, BlockToolType::AXE)
        );
    }

    protected function writeStateToMeta(): int {
        return BlockDataSerializer::writeFacing($this->facing);
    }

    public function readStateFromData(int $id, int $stateMeta): void {
        $this->facing = BlockDataSerializer::readFacing($stateMeta);
    }

    public function place(
        BlockTransaction $tx,
        Item $item,
        Block $blockReplace,
        Block $blockClicked,
        int $face,
        Vector3 $clickVector,
        ?Player $player = null
    ): bool {
        if ($player !== null) {
            $this->facing = $face <= 1 ? 0 : ($face > 3 ? 1 : 2);
        }

        return parent::place(
            $tx,
            $item,
            $blockReplace,
            $blockClicked,
            $face,
            $clickVector,
            $player
        );
    }

    public function getFuelTime(): int {
        return 300;
    }

    public function getFlameEncouragement(): int {
        return 5;
    }

    public function getFlammability(): int {
        return 5;
    }
}

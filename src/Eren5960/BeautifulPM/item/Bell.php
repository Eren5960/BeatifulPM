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

namespace Eren5960\BeautifulPM\item;

use pocketmine\block\BlockLegacyIds;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemIdentifier;

class Bell extends ItemBlock{
	public function __construct(int $meta = 0){
		parent::__construct(BlockLegacyIds::BELL, $meta, new ItemIdentifier(ItemIds::BELL, 0));
	}
}
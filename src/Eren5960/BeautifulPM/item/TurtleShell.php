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
 
namespace Eren5960\BeautifulPM\item;

use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\ItemIds;

class TurtleShell extends Armor{
    public function __construct(int $variant){
	    parent::__construct(ItemIds::TURTLE_HELMET, $variant, 'Turtle Shell', new ArmorTypeInfo(1, 56, ArmorInventory::SLOT_HEAD));
    }
}
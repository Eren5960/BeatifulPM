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
 
namespace Eren5960\BeatifulPM\item;
 
use pocketmine\block\Block;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class TurtleShell extends Armor{
    public function __construct(int $variant){
	    parent::__construct(ItemIds::TURTLE_HELMET, $variant, 'Turtle Shell', new ArmorTypeInfo(1, 56, ArmorInventory::SLOT_HEAD));
    }

	/*public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : ItemUseResult{
		$result = parent::onActivate($player, $blockReplace, $blockClicked, $face, $clickVector);
		if($result->equals(ItemUseResult::SUCCESS())){
			$player->getEffects()->add(new EffectInstance(VanillaEffects::WATER_BREATHING(), 200));
		}
		return $result;
	}*/
}
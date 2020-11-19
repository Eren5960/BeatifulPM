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

namespace Eren5960\BeautifulPM;

use Eren5960\BeautifulPM\block\StrippedLog;
use Eren5960\BeautifulPM\block\Bell as BellBlock;
use Eren5960\BeautifulPM\tile\Bell as BellTile;
use Eren5960\BeautifulPM\item\TurtleShell;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\Log;
use pocketmine\block\tile\TileFactory;
use pocketmine\block\utils\TreeType;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Axe;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemIdentifier;

class Loader extends PluginBase implements Listener{
	protected function onEnable(){
	    $bf = BlockFactory::getInstance();
		self::initTiles();
		self::initBlocks($bf);
        self::initItems($bf);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	private static function initItems(BlockFactory $bf): void{
		$items = [
			new ItemBlock(new ItemIdentifier(ItemIds::BELL, 0), $bf->get(Ids::BELL)),
			new TurtleShell(0),
		];
		foreach(TreeType::getAll() as $treeType){
			$items[] = new ItemBlock(new ItemIdentifier(ItemIds::STRIPPED_SPRUCE_LOG - $treeType->getMagicNumber(), 0), $bf->get(Ids::STRIPPED_SPRUCE_LOG + $treeType->getMagicNumber(), 0));
		}
		foreach($items as $item){
			ItemFactory::getInstance()->register($item, true);
			CreativeInventory::getInstance()->add($item);
		}
	}

	private static function initBlocks(BlockFactory $bf): void{
		$blocks = [
			new BellBlock(new BlockIdentifier(Ids::BELL, 0, ItemIds::BELL, BellTile::class), 'Bell')
		];
		foreach(TreeType::getAll() as $treeType){
			$blocks[] = new StrippedLog(
				new BlockIdentifier(Ids::STRIPPED_SPRUCE_LOG + $treeType->getMagicNumber(), 0, ItemIds::STRIPPED_SPRUCE_LOG - $treeType->getMagicNumber()),
				$treeType->getDisplayName() . ' Wood');
		}
		foreach($blocks as $block){
            $bf->register($block, true);
		}
	}

	private static function initTiles(): void{
		TileFactory::getInstance()->register(BellTile::class, ['Bell', 'minecraft:bell']);
	}

	/**
	 * @param PlayerInteractEvent $event
	 *
	 * @priority        HIGHEST
	 * @handleCancelled false
	 */
	public function onInteract(PlayerInteractEvent $event): void{
		$block = $event->getBlock();
		if($event->getAction() === $event::RIGHT_CLICK_BLOCK && ($event->getItem() instanceof Axe && $block instanceof Log)){// stripe logs
			$block->getPos()->getWorld()->setBlock($block->getPos(),
				BlockFactory::getInstance()->get(
					Ids::STRIPPED_SPRUCE_LOG + ($block->getTreeType()->getMagicNumber() === 0 ? 5 : $block->getTreeType()->getMagicNumber() - 1), 0
				),
				false
			);
		}
	}
}
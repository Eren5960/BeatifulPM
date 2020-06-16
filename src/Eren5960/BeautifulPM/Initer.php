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

namespace Eren5960\BeautifulPM;

use Eren5960\BeautifulPM\block\Barrel as BarrelBlock;
use Eren5960\BeautifulPM\block\StrippedLog;
use Eren5960\BeautifulPM\item\Barrel;
use Eren5960\BeautifulPM\item\Bell;
use Eren5960\BeautifulPM\block\Bell as BellBlock;
use Eren5960\BeautifulPM\tile\Barrel as BarrelTile;
use Eren5960\BeautifulPM\tile\Bell as BellTile;
use Eren5960\BeautifulPM\item\TurtleShell;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
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

class Initer extends PluginBase implements Listener{
	protected function onEnable(){
		self::initItems();
		self::initTiles();
		self::initBlocks();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	private static function initItems() : void{
		$items = [
			new Bell(0),
			new TurtleShell(0),
			new Barrel(0),
			new ItemBlock(BlockLegacyIds::BARRIER, 0, ItemIds::BARRIER)
		];
		foreach(TreeType::getAll() as $treeType){
			$items[] = new ItemBlock(BlockLegacyIds::STRIPPED_SPRUCE_LOG + $treeType->getMagicNumber(), 0, ItemIds::STRIPPED_SPRUCE_LOG - $treeType->getMagicNumber());
		}
		foreach($items as $item){
			ItemFactory::getInstance()->register($item, true);
			CreativeInventory::getInstance()->add($item);
		}
	}

	private static function initBlocks() : void{
		$blocks = [
			new BellBlock(new BlockIdentifier(BlockLegacyIds::BELL, 0, ItemIds::BELL, BellTile::class), 'Bell'),
			new BarrelBlock(new BlockIdentifier(BlockLegacyIds::BARREL, 0, ItemIds::BARREL, BarrelTile::class))
		];
		foreach(TreeType::getAll() as $treeType){
			$blocks[] = new StrippedLog(
			    new BlockIdentifier(BlockLegacyIds::STRIPPED_SPRUCE_LOG + $treeType->getMagicNumber(), 0, ItemIds::STRIPPED_SPRUCE_LOG - $treeType->getMagicNumber()),
                $treeType->getDisplayName() . ' Wood');
		}
		foreach($blocks as $block){
			BlockFactory::getInstance()->register($block, true);
		}
	}

	private static function initTiles() : void{
		TileFactory::getInstance()->register(BellTile::class, ['Bell', 'minecraft:bell']);
		TileFactory::getInstance()->register(BarrelTile::class, ['Barrel', 'minecraft:barrel']);
	}

	/**
	 * @param PlayerInteractEvent $event
	 * @priority HIGHEST
	 * @handleCancelled false
	 */
	public function onInteract(PlayerInteractEvent $event) : void{
		$block = $event->getBlock();
		if($event->getAction() === $event::RIGHT_CLICK_BLOCK && ($event->getItem() instanceof Axe && $block instanceof Log)){// stripe logs
			$block->getPos()->getWorldNonNull()->setBlock($block->getPos(),
                BlockFactory::getInstance()->get(
			    BlockLegacyIds::STRIPPED_SPRUCE_LOG + ($block->getTreeType()->getMagicNumber() === 0 ? 5 : $block->getTreeType()->getMagicNumber() - 1)
                ),
                false
            );
		}
	}
}
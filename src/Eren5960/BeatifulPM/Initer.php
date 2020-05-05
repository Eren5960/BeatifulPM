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
 
namespace Eren5960\BeatifulPM;
 
use Eren5960\BeatifulPM\block\Barrel as BarrelBlock;
use Eren5960\BeatifulPM\item\Barrel;
use Eren5960\BeatifulPM\item\Bell;
use Eren5960\BeatifulPM\block\Bell as BellBlock;
use Eren5960\BeatifulPM\tile\Barrel as BarrelTile;
use Eren5960\BeatifulPM\tile\Bell as BellTile;
use Eren5960\BeatifulPM\item\TurtleShell;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\tile\TileFactory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Utils;

class Initer extends PluginBase{
    protected function onEnable(){
		self::initItems();
		self::initTiles();
	    self::initBlocks();
    }

    private static function initItems(): void{
    	$items = [
    		new Bell(0), new TurtleShell(0),
		    new Barrel(0),
	    ];
		foreach($items as $item){
			ItemFactory::register($item, true);
			CreativeInventory::add($item);
		}
    }

	private static function initBlocks(): void{
		$blocks = [
			new BellBlock(new BlockIdentifier(BlockLegacyIds::BELL, 0, ItemIds::BELL, BellTile::class), 'Bell', BlockBreakInfo::instant()),
			new BarrelBlock(new BlockIdentifier(BlockLegacyIds::BARREL, 0, ItemIds::BARREL, BarrelTile::class))
		];
		foreach($blocks as $block){
			BlockFactory::register($block, true);
		}
	}

	private static function initTiles(): void{
		TileFactory::register(BellTile::class, ['Bell', 'minecraft:bell']);
		TileFactory::register(BarrelTile::class, ['Barrel', 'minecraft:barrel']);
	}
}
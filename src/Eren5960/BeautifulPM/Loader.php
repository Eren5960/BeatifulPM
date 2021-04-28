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

use Eren5960\BeautifulPM\block\Composter;
use Eren5960\BeautifulPM\block\Bell as BellBlock;
use Eren5960\BeautifulPM\tile\Bell as BellTile;
use Eren5960\BeautifulPM\item\TurtleShell;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\tile\TileFactory;
use pocketmine\event\Listener;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemIdentifier;

class Loader extends PluginBase implements Listener {
    protected function onEnable(): void {
        $bf = BlockFactory::getInstance();
        self::initTiles();
        self::initBlocks($bf);
        self::initItems($bf);
    }

    private static function initItems(BlockFactory $bf): void {
        $items = [
            new ItemBlock(
                new ItemIdentifier(ItemIds::BELL, 0),
                $bf->get(Ids::BELL, 0)
            ),
            new TurtleShell(0),
            new ItemBlock(
                new ItemIdentifier(ItemIds::COMPOSTER, 0),
                $bf->get(Ids::COMPOSTER, 0)
            )
        ];

        foreach ($items as $item) {
            ItemFactory::getInstance()->register($item, true);
            self::addItemsToCreativeInventory($item, [408, 422, 428, 431]);
        }
    }

    public static function addItemsToCreativeInventory(
        Item $item,
        array $protocols
    ): void {
        foreach ($protocols as $protocol) {
            CreativeInventory::getInstance($protocol)->add($item);
        }
    }

    private static function initBlocks(BlockFactory $bf): void {
        $blocks = [
            new BellBlock(
                new BlockIdentifier(
                    Ids::BELL,
                    0,
                    ItemIds::BELL,
                    BellTile::class
                ),
                'Bell'
            ),
            new Composter(
                new BlockIdentifier(Ids::COMPOSTER, 0, ItemIds::COMPOSTER),
                'Composter'
            )
        ];
        foreach ($blocks as $block) {
            $bf->register($block, true);
        }
    }

    private static function initTiles(): void {
        TileFactory::getInstance()->register(BellTile::class, [
            'Bell',
            'minecraft:bell'
        ]);
    }
}

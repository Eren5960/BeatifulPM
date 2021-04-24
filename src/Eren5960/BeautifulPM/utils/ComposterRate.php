<?php

namespace Eren5960\BeautifulPM\utils;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;

class ComposterRate {
    const DATA = [
        30 => [
            ItemIds::BEETROOT_SEEDS,
            ItemIds::DRIED_KELP,
            ItemIds::GRASS,
            ItemIds::KELP,
            ItemIds::LEAVES,
            ItemIds::MELON_SEEDS,
            ItemIds::NETHER_WART,
            ItemIds::PUMPKIN_SEEDS,
            ItemIds::SAPLING,
            ItemIds::SEAGRASS,
            // Grass but not block
            ItemIds::SWEET_BERRIES,
            ItemIds::WHEAT_SEEDS
        ],
        50 => [
            ItemIds::CACTUS,
            ItemIds::DRIED_KELP_BLOCK,
            ItemIds::MELON_SLICE,
            ItemIds::SUGARCANE,
            ItemIds::TALL_GRASS,
            ItemIds::VINES
        ],
        65 => [
            ItemIds::APPLE,
            ItemIds::BEETROOT,
            ItemIds::CARROT,
            ItemIds::COCOA,
            // Ferns
            ItemIds::CHORUS_FLOWER,
            ItemIds::POPPY,
            ItemIds::DANDELION,
            ItemIds::LILY_PAD,
            ItemIds::PUMPKIN,
            ItemIds::POTATO,
            ItemIds::MELON,
            ItemIds::BROWN_MUSHROOM,
            ItemIds::RED_MUSHROOM,
            // Mushroom stem
            ItemIds::SEA_PICKLE,
            ItemIds::WHEAT
            // fungus
            // roots
        ],
        85 => [
            ItemIds::BAKED_POTATO,
            ItemIds::BREAD,
            ItemIds::COOKIE,
            ItemIds::HAY_BALE,
            ItemIds::BROWN_MUSHROOM_BLOCK,
            ItemIds::RED_MUSHROOM_BLOCK,
            ItemIds::NETHER_WART_BLOCK
        ],
        100 => [ItemIds::CAKE, ItemIds::PUMPKIN_PIE]
    ];

    /** @var int|null[] */
    public static $rateCache = [];

    public static function getRate(Item $item): ?int {
        if (isset(self::$rateCache[$item->getId()])) {
            return self::$rateCache[$item->getId()];
        }
        foreach (self::DATA as $rate => $data) {
            if (in_array($item->getId(), $data)) {
                return self::$rateCache[$item->getId()] = $rate;
            }
        }

        return self::$rateCache[$item->getId()] = null;
    }
}

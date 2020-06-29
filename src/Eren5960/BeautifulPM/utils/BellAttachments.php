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
 * @date   23 Mayıs 2020
 */
declare(strict_types=1);

namespace Eren5960\BeautifulPM\utils;

final class BellAttachments{
	public const DEFAULT = self::STANDING;

	public const STANDING = "standing";
	public const SIDE = "side";
	public const MULTIPLE = "multiple";
	public const HANGING = "hanging";

	public function __construct(){
	}
}
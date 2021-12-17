<?php
/**
 * This file is a part of "furqansiddiqui/bip32-keypair-php" package.
 * https://github.com/furqansiddiqui/bip32-keypair-php
 *
 * Copyright (c) 2020 Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/bip32-keypair-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace FurqanSiddiqui\BIP32\Exception;

/**
 * Class ChildKeyDeriveException
 * @package FurqanSiddiqui\BIP32\Exception
 */
class ChildKeyDeriveException extends ExtendedKeyException
{
    public const HINT_TRY_NEXT_INDEX = 1;
}
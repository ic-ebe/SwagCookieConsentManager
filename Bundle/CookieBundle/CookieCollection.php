<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace SwagCookieConsentManager\Bundle\CookieBundle;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Bundle\CookieBundle\Structs\CookieStruct;

class CookieCollection extends ArrayCollection implements \JsonSerializable
{
    /**
     * @return bool
     */
    public function isValid()
    {
        foreach ($this as $cookieStruct) {
            if (!$cookieStruct instanceof CookieStruct) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $cookieName
     *
     * @return bool
     */
    public function hasCookieWithName( $cookieName)
    {
        return $this->exists(static function ($key, CookieStruct $cookieStruct) use ($cookieName) {
            return preg_match($cookieStruct->getMatchingPattern(), $cookieName) === 1;
        });
    }

    /**
     * @param string $cookieName
     *
     * @return CookieStruct|null
     */
    public function getCookieByName($cookieName)
    {
        /** @var CookieStruct $cookieStruct */
        foreach ($this as $cookieStruct) {
            if (!preg_match($cookieStruct->getMatchingPattern(), $cookieName)) {
                continue;
            }

            return $cookieStruct;
        }

        return null;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}

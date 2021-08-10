<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShopwareCli;

use ShopwareCli\Services\PassUtil;

class PassCommandConfig implements \ArrayAccess
{
    /**
     * @var Config
     */
    private $inner;

    /**
     * @var PassUtil|null
     */
    private $passUtil = null;

    public function __construct(Config $inner)
    {
        $this->inner = $inner;

        $passCommand = $this->inner->offsetGet('general.passCommand');
        $passCommandNamespace = $this->inner->offsetGet('general.passCommandNamespace');

        if ($passCommand && $passCommandNamespace) {
            $this->passUtil = new PassUtil($passCommand, $passCommandNamespace);
        }
    }

    public function offsetExists($offset)
    {
        if ($this->inner->offsetExists($offset)) {
            return true;
        }

        if ($this->passUtil === null) {
            return false;
        }

        return (bool) $this->passUtil->get($offset);
    }

    public function offsetGet($offset)
    {
        if ($this->inner->offsetExists($offset)) {
            return $this->inner->offsetGet($offset);
        }

        if ($this->passUtil === null) {
            return null;
        }

        return $this->passUtil->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->inner->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->inner->offsetUnset($offset);
    }
}

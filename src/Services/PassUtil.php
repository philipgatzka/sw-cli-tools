<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShopwareCli\Services;

use Symfony\Component\Process\Process;

class PassUtil
{
    /**
     * @var string
     */
    private $passCommand;

    /**
     * @var string
     */
    private $passCommandNamespace;

    public function __construct(string $passCommand, string $passCommandNamespace)
    {
        $this->passCommand = $passCommand;
        $this->passCommandNamespace = $passCommandNamespace;
    }

    public function get(string $argument): string
    {
        $process = new Process([
            $this->passCommand,
            \implode(\DIRECTORY_SEPARATOR, [$this->passCommandNamespace, $argument]),
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(\sprintf(
                "Command \"%s\" failed. Error Output:\n\n%s%s",
                \implode(' ', [$this->passCommand, $argument]),
                $process->getErrorOutput(),
                $process->getExitCode()
            ));
        }

        return $process->getOutput();
    }
}

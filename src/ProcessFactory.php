<?php

namespace Webit\PHPgs;

use Symfony\Component\Process\Process;
use Webit\PHPgs\Options\Options;

class ProcessFactory
{
    /** @var string */
    private $gsBin;

    /** @var int */
    public static $timeout = 60;

    /**
     * ProcessFactory constructor.
     * @param string $gsBin
     */
    public function __construct($gsBin = 'gs')
    {
        $this->gsBin = $gsBin;
    }

    /**
     * @param Input $input
     * @param Output $output
     * @param Options $options
     * @return Process
     */
    public function createProcess(Input $input, Output $output, Options $options)
    {
        $this->ensureOutputDirExists($output);
        $cmd = sprintf(
            '%s %s -sOutputFile=%s %s',
            $this->gsBin,
            (string)$options,
            (string)$output,
            (string)$input
        );
        return Process::fromShellCommandline($cmd, null, null,null, self::$timeout);
        //return new Process($cmd);
    }

    /**
     * @param Output $output
     */
    private function ensureOutputDirExists(Output $output)
    {
        $dir = dirname($output->filenameOrPattern());
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}

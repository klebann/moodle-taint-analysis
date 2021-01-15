<?php
namespace Klebann\MoodleTaintAnalysis;

use Klebann\MoodleTaintAnalysis\Hooks\MoodleScanner;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        if(class_exists(MoodleScanner::class)){
            $psalm->registerHooksFromClass(MoodleScanner::class);
        }
    }
}

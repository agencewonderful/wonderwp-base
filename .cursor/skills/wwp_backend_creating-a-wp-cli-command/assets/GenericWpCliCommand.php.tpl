<?php

namespace WonderWp\Plugin\Actu\Child\Commands;

use WonderWp\Component\Logging\LoggerInterface;
use WonderWp\Component\Logging\WpCliLogger;
use WonderWp\Component\Task\Definition\AbstractWpCliCommand;

class MyPluginCommand extends AbstractWpCliCommand
{
    protected LoggerInterface $logger;

    public static function getName(): string
    {
        return 'my-plugin:my-command';
    }

    public static function getArgsDefinition(): array
    {
        return [
            'shortdesc' => 'Test command for my plugin',
            'synopsis' => [
                [
                    'type' => 'assoc',
                    'name' => 'my-first-arg',
                    'description' => 'An example argument for the command',
                    'optional' => true,
                ],
            ],
        ];
    }

    protected function bootstrap(array $args, array $assocArgs): void
    {
        $this->logger = new WpCliLogger();
    }

    public function __invoke(array $args = [], array $assocArgs = [])
    {
        $start = microtime(true);

        $this->bootstrap($args, $assocArgs);
        $this->logger->info('Executing my-plugin:my-command with args', ['args' => $args, 'assocArgs' => $assocArgs]);

        // Command logic here...

        $endTime = microtime(true);
        $executionTime = round($endTime - $start, 2);
        $this->logger->success("My plugin command executed successfully! Execution time: {$executionTime}s");
    }
}

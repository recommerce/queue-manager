<?php

return [
    'service_manager' => [
        'factories' => [
            'recommerce.queue-manager.queue-reader' => \Recommerce\QueueManager\Factory\QueueReaderFactory::class,
            'recommerce.queue-manager.queue-writer' => \Recommerce\QueueManager\Factory\QueueWriterFactory::class,
            'recommerce.queue-manager.adapter-client' => \Recommerce\QueueManager\Factory\AdapterFactory::class,
            'recommerce.queue-manager.adapter.logger-client' => \Recommerce\QueueManager\Adapter\LoggerClientFactory::class,
            'recommerce.queue-manager.adapter.sqs-client' => \Recommerce\QueueManager\Adapter\SqsClientFactory::class
        ]
    ],
];

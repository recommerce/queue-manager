<?php

return [
    'service_manager' => [
        'factories' => [
            'recommerce.queue-manager.queue-reader' => \Recommerce\QueueManager\QueueReaderFactory::class,
            'recommerce.queue-manager.queue-writer' => \Recommerce\QueueManager\QueueWriterFactory::class,
            'recommerce.queue-manager.adapter.sqs-client' => \Recommerce\QueueManager\Adapter\SqsClientFactory::class
        ]
    ],
];

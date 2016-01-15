[![Build Status](https://travis-ci.org/recommerce/queue-manager.svg?branch=master)](https://travis-ci.org/recommerce/queue-manager) [![Code Climate](https://codeclimate.com/github/recommerce/queue-manager/badges/gpa.svg)](https://codeclimate.com/github/recommerce/queue-manager) [![Test Coverage](https://codeclimate.com/github/recommerce/queue-manager/badges/coverage.svg)](https://codeclimate.com/github/recommerce/queue-manager/coverage)

# Recommerce queue-manager

This library provides an interface and some implementation to handle queue system.

Current implementations are :
* AWS SQS : Amazon SQS service (using AWS SDK library).

## Installation with composer

```sh
composer require recommerce/queue-manager:^0.0
composer update
```

## Queue manager events

QueueManager uses zend framework events.

### Queue reader events

* QueueReader::NEXT_MESSAGE_START ;
* QueueReader::NEXT_MESSAGE_END ;
* QueueReader::BEFORE_RECEIVE_MESSAGE ;
* QueueReader::AFTER_RECEIVE_MESSAGE ;
* QueueReader::DELETE_CURRENT_MESSAGE.

## Usage examples

### Queue reader usage
```php
    use Zend\ServiceManager\Config;
    use Zend\ServiceManager\ServiceManager;

    $config = require 'config/services.config.php';
    $config['queue_client'] = [
        // Params for constructor
        // see aws sdk documentation : http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.Sqs.SqsClient.html
        'url' => '<YOUR_SQS_QUEUE_URL>',
        'params' => [
            'profile' => 'default',
            'region' => 'eu-west-1',
            'version' => 'latest',
            'key' => '<YOUR_SQS_KEY>',
            'secret' => '<YOUR_SQS_SECRET>'
        ],
        'options' => []
    ];

    try {
        $serviceManager = new ServiceManager(new Config($config['service_manager']));
        $serviceManager->setService('Config', $config);

        $queueManager = $serviceManager->get('recommerce.queue-manager.queue-reader');
    } catch (\Exception $e) {
        // Problem in queue reader creation
    }

    while ($message = $queueManager->getNextMessage()) {
        // Do some stuff
        // ...

        $queueManager->deleteCurrentMessage();
    }
```
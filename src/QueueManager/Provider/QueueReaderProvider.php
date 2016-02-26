<?php
namespace Recommerce\QueueManager\Provider;

use Recommerce\QueueManager\QueueReaderInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class QueueReaderProvider
 *
 * @package Recommerce\QueueManager\Provider
 */
final class QueueReaderProvider
{
    /**
     * Init an instance of QueueReader and configure an internal instance of ServiceManager
     *
     * @param array $config
     * @return QueueReaderInterface
     */
    public static function get(array $config)
    {
        $services = new ServiceManager(
            new Config(
                include dirname(dirname(dirname(__DIR__))) . '/config/services.config.php'
            )
        );
        $services->setService("Config", $config);

        return $services->get('recommerce.queue-manager.queue-reader');
    }
}
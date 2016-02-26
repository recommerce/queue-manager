<?php
namespace Recommerce\QueueManager\Provider;

use Recommerce\QueueManager\QueueWriterInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * Class QueueWriterProvider
 *
 * @package Recommerce\QueueManager\Provider
 */
final class QueueWriterProvider
{
    /**
     * Init an instance of QueueWriter and configure an internal instance of ServiceManager
     *
     * @param array $config
     * @return QueueWriterInterface
     */
    public static function get(array $config)
    {
        $services = new ServiceManager(
            new Config(
                include dirname(dirname(dirname(__DIR__))) . '/config/services.config.php'
            )
        );
        $services->setService("Config", $config);

        return $services->get('recommerce.queue-manager.queue-writer');
    }
}
<?php

use CleverReach\WordPress\Components\Utility\Database;
use CleverReach\WordPress\IntegrationCore\BusinessLogic\Sync\FormSyncTask;
use CleverReach\WordPress\IntegrationCore\BusinessLogic\Sync\InitialSyncTask;
use CleverReach\WordPress\IntegrationCore\Infrastructure\Interfaces\Required\Configuration;
use CleverReach\WordPress\IntegrationCore\Infrastructure\Interfaces\Required\TaskQueueStorage;
use CleverReach\WordPress\IntegrationCore\Infrastructure\ServiceRegister;
use CleverReach\WordPress\IntegrationCore\Infrastructure\TaskExecution\Queue;
use CleverReach\WordPress\IntegrationCore\Infrastructure\TaskExecution\QueueItem;

/** @var Configuration $config_service */
$config_service     = ServiceRegister::getService( Configuration::CLASS_NAME );
/** @var TaskQueueStorage $task_queue_service */
$task_queue_service = ServiceRegister::getService( TaskQueueStorage::CLASS_NAME);
$initial_sync_task_item = $task_queue_service->findLatestByType( InitialSyncTask::getClassName());

if ( $initial_sync_task_item && $initial_sync_task_item->getStatus() === QueueItem::COMPLETED) {
	ServiceRegister::getService( Queue::CLASS_NAME )->enqueue( $config_service->getQueueName(), new FormSyncTask() );
}

if ( ! function_exists( 'cleverreach_wp_get_database_queries' ) ) {
	function cleverreach_wp_get_database_queries() {
		$queries   = array();
		$queries[] = 'CREATE TABLE IF NOT EXISTS `' . Database::table( Database::ENTITY_TABLE ) . '` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `type` VARCHAR(127),
            `index_1` VARCHAR(127),
            `index_2` VARCHAR(127),
            `index_3` VARCHAR(127),
            `index_4` VARCHAR(127),
            `index_5` VARCHAR(127),
            `index_6` VARCHAR(127),
            `index_7` VARCHAR(127),
            `data` LONGTEXT,
            PRIMARY KEY (`id`)
        )';

		return $queries;
	}
}

return cleverreach_wp_get_database_queries();

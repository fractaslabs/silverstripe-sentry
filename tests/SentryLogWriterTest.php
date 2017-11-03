<?php

/**
 * Class: SentryLogWriterTest.
 *
 * @author  Russell Michell 2017 <russ@theruss.com>
 * @package phptek/sentry
 */

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;

/**
 * Exercises SentryLogger.
 */
class SentryLogWriterTest extends SapphireTest
{
    /**
     * Setup test-specific context
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        // No idea why these need to be explicitly set. Although the suite runs,
        // we always see nest() / unnest() errors from phpunit..
//        Injector::nest();
//        Config::nest();

		\Phockito::include_hamcrest(true);

        // Setup a dummy Sentry DSN so our errors are not actually sent anywhere
        Config::inst()->update(
            'phptek\Sentry\Adaptor\SentryClientAdaptor',
            'opts',
            ['dsn' => 'http://deacdf9dfedb24ccdce1b90017b39dca:deacdf9dfedb24ccdce1b90017b39dca@sentry.mydomain.nz/44']
        );
	}

    /**
     * Simply tests that when SS_Log::log() is called, _write() is also called.
     *
     * @return void
     */
    public function testWriteIsCalled()
    {
        // Mock the SentryLogger
        $spy = \Phockito::spy('PHPTek\Sentry\Log\SentryLogger');

        // Register it
        \SS_Log::add_writer($spy, \SS_Log::ERR, '<=');

        // Indirectly invoke SentryLogWriter::_write()
        \SS_Log::log('You have one minute to reach minimum safe distance.', \SS_Log::ERR);

        // Verificate
        \Phockito::verify($spy, 1)->_write(arrayValue());
    }

}

<?php

/**
 * Class: SentryLogWriterTest.
 *
 * @author  Russell Michell 2017 <russ@theruss.com>
 * @package silverstripe/sentry
 */

use \SilverStripeSentry\SentryLogWriter;

require_once THIRDPARTY_PATH . '/Zend/Log/Formatter/Interface.php';

/**
 * Uses Phockito to mock an instance of SentryLogWriter to verify it's working as
 * we expect.
 */

class SentryLogWriterTest extends \SapphireTest
{

    /**
     * @return void
     */
    public function setUpOnce()
    {
        if (class_exists('Phockito')) {
           \Phockito::include_hamcrest();
        }

        parent::setUpOnce();
    }

    /**
     * Check that the client send() method is correctly invoked.
     */
    public function testClientSend()
    {
       //$config = Config::nest();
       // $config->remove('RavenClient', 'dependencies');

        // Mock a version of RavenClient and invert control via Injector
       // $mockClient = \Phockito::mock('\SilverStripeSentry\Adaptor\RavenClient');

        // Register the writer as usual
        $mockWriter = \Phockito::mock('SilverStripeSentry\SentryLogWriter');
        \SS_Log::add_writer($mockWriter, SS_Log::ERR, '<=');

        // Log an error
        \SS_Log::log('You have 2m to reach minimum safe distance.', \SS_Log::ERR);

        // Verify the mock-client's send() method is invoked
        Phockito::verify($mockWriter, 1)->_write(anything());
    }
    
}

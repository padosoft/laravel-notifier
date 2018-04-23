<?php

namespace Padosoft\Laravel\Notification\Notifier\Test;

use Padosoft\Laravel\Notification\Notifier\Notifier;

class NotifierTest extends TestBaseOrchestra
{
    protected $notifier;

    public function setUp()
    {
        parent::setUp();

        $this->notifier = new Notifier($this->app['session']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [\Padosoft\Laravel\Notification\Notifier\Notifier::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Notifier' => Padosoft\Laravel\Notification\Notifier\Facades\Notifier::class
        ];
    }

    /** @test */
    public function testOK()
    {
        $this->assertTrue(1, 1);

        //$urls = $this->invokeMethod($this->googleStructuredDataTestTool, 'findUrls', ['sdadadada', $this->mockCommand]);
    }

}

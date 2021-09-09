<?php
namespace MaximeRainville\SS;

use Symfony\Component\Console\Application as SymfonyApp;

class App extends SymfonyApp
{
    /**
     * @throws \Exception
     */
    public static function boot()
    {
        $me = new self();

        $me->add(new Env\Command());
        $me->add(new Host\Command());

        $me->setName("Maxime's magical Silverstripe CMS Helper command");
        $me->run();
    }
}
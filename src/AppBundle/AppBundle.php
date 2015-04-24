<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function boot()
    {
        $this->container->set('entity_manager',
            $this->container->get('doctrine')->getManager()
        );
    }
}

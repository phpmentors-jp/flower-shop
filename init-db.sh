#!/bin/sh
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console h4cc_alice_fixtures:load:sets src/AppBundle/Resources/fixtureSet.php

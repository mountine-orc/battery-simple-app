<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BatteriesControllerTest extends WebTestCase
{
    public function testHomepageIsWorking()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Battery project")')->count());
    }

    public function testForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/batteries/statistic');

        //get amount of batteries before form sending
        $batteryCountBefore = $crawler->filter('td:contains("TestType")')
            ->first()
            ->nextAll()
            ->last()
            ->text();

        //get add form link
        $link = $crawler
            ->filter('a:contains("Add battery")')
            ->first()
            ->link();

        $crawler = $client->click($link);

        //fill and send form
        $form = $crawler->selectButton('form[save]')->form();
        $form['form[username]'] = 'Lucas';
        $form['form[type]'] = 'TestType';
        $form['form[amount]'] = '6';
        $client->submit($form);
        $crawler = $client->followRedirect();

        //get amount of batteries after form sending
        $batteryCountAfter = $crawler->filter('td:contains("TestType")')
            ->first()
            ->nextAll()
            ->last()
            ->text();

        $this->assertEquals(6, $batteryCountAfter - $batteryCountBefore);
    }
}
<?php

namespace App\Helper;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \Symfony\Bundle\FrameworkBundle\Client client
 */
class KartoKloudTestCase extends WebTestCase
{
    protected static $staticClient;

    protected static $application;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    public static function setUpBeforeClass()
    {
        self::$staticClient = static::createClient(['environment' => 'test']);
        // kernel boot, so we can get the container and use our services
        self::bootKernel();
    }

    /**
     * @param      $method
     * @param      $urlPath
     * @param null $rawRequestBody
     * @param null $username
     * @param null $password
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    public function performClientRequest($method, $urlPath, $rawRequestBody = null, $username = null, $password = null)
    {
        if ($username != null) {
            $this->doLogin($username, $password);
        } else {
            $this->client = static::createClient([]);
        }

        return $this->client->request($method, $urlPath, [], [], [], $rawRequestBody);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return Crawler
     */
    public function doLogin(string $username, string $password) :Crawler
    {
        $this->client = static::createClient([], []);
        $crawler = $this->client->request('GET', '/login');
        dump($crawler);
        $form = $crawler->selectButton('_submit')->form(['_username' => $username, '_password' => $password]);
        $this->client->submit($form);
        self::assertTrue($this->client->getResponse()->isRedirect());

        return $this->client->followRedirect();
    }

    /**
     * @param $id
     *
     * @return object
     */
    protected function getService($id) :object
    {
        return self::$kernel->getContainer()->get($id);
    }

    protected function setUp()
    {
        $this->container     = self::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    /**
     * @throws \Exception
     */
    protected function resetDb()
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load --no-interaction');
    }

    /**
     *
     * @param string $command
     * @return int
     * @throws \Exception
     */
    protected static function runCommand(string $command) :int
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    /**
     * @return Application
     */
    protected static function getApplication() :Application
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    // purposefully not calling parent class, which shuts down the kernel}
    protected function tearDown() {}
}
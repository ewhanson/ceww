<?php

namespace AppBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use GuzzleHttp\Client;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Update place data from Geonames.
 */
class PlaceNamesCommand extends ContainerAwareCommand
{
    /**
     * PSR Logger.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Doctrine database connection.
     *
     * @var Registry
     */
    private $em;

    /**
     * Geonames username, from parameters.yml.
     *
     * @var string
     */
    private $geonames_account;

    /**
     * Geonames endpoint.
     */
    const GEONAMES_SEARCH = 'http://api.geonames.org/search';

    /**
     * {@inheritDoc}
     */
    protected function configure() {
        $this
            ->setName('ceww:placenames')
            ->setDescription('Update the place names with results from Geonames');
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->logger = $container->get('logger');
        $this->em = $container->get('doctrine')->getManager();
        $this->geonames_account = $container->getParameter('geonames_account');
    }

    /**
     * Get an HTTP Client.
     *
     * @return Client a Guzzle Client.
     */
    private function getClient() {
        $client = new Client(array(
            'headers' => array(
                'User-Agent' => 'CEWW API Client/1.0',
                'Accept' => 'application/json',
            )
        ));
        return $client;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $client = $this->getClient();
        $query = $this->em->createQuery('SELECT p FROM AppBundle:Place p');
        $iterator = $query->iterate();
        foreach ($iterator as $row) {
            $place = $row[0];
            $this->logger->notice($place->getName());
            $response = $client->get(self::GEONAMES_SEARCH, array(
                'query' => array(
                    'q' => rawurlencode(utf8_encode($place->getName())),
                    'country_bias' => 'ca',
                    'maxRows' => 1,
                    'type' => 'xml',
                    'style' => 'FULL',
                    'username' => $this->geonames_account,
                )
            ));
            print_r($response->getBody()->getContents());
            return;
        }
    }

}
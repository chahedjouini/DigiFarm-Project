<?php

namespace App\Tests\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CommandeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $commandeRepository;
    private string $path = '/commande/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->commandeRepository = $this->manager->getRepository(Commande::class);

        foreach ($this->commandeRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commande index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'commande[statut]' => 'Testing',
            'commande[type]' => 'Testing',
            'commande[quantite]' => 'Testing',
            'commande[prixUnitaire]' => 'Testing',
            'commande[montantTotal]' => 'Testing',
            'commande[dateCommande]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->commandeRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commande();
        $fixture->setStatut('My Title');
        $fixture->setType('My Title');
        $fixture->setQuantite('My Title');
        $fixture->setPrixUnitaire('My Title');
        $fixture->setMontantTotal('My Title');
        $fixture->setDateCommande('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Commande');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commande();
        $fixture->setStatut('Value');
        $fixture->setType('Value');
        $fixture->setQuantite('Value');
        $fixture->setPrixUnitaire('Value');
        $fixture->setMontantTotal('Value');
        $fixture->setDateCommande('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'commande[statut]' => 'Something New',
            'commande[type]' => 'Something New',
            'commande[quantite]' => 'Something New',
            'commande[prixUnitaire]' => 'Something New',
            'commande[montantTotal]' => 'Something New',
            'commande[dateCommande]' => 'Something New',
        ]);

        self::assertResponseRedirects('/commande/');

        $fixture = $this->commandeRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getQuantite());
        self::assertSame('Something New', $fixture[0]->getPrixUnitaire());
        self::assertSame('Something New', $fixture[0]->getMontantTotal());
        self::assertSame('Something New', $fixture[0]->getDateCommande());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Commande();
        $fixture->setStatut('Value');
        $fixture->setType('Value');
        $fixture->setQuantite('Value');
        $fixture->setPrixUnitaire('Value');
        $fixture->setMontantTotal('Value');
        $fixture->setDateCommande('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/commande/');
        self::assertSame(0, $this->commandeRepository->count([]));
    }
}

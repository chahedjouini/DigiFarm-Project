<?php

namespace App\Tests\Controller;

use App\Entity\Veterinaire;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VeterinaireControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $veterinaireRepository;
    private string $path = '/veterinaire/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->veterinaireRepository = $this->manager->getRepository(Veterinaire::class);

        foreach ($this->veterinaireRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Veterinaire index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'veterinaire[nom]' => 'Testing',
            'veterinaire[num_tel]' => 'Testing',
            'veterinaire[email]' => 'Testing',
            'veterinaire[adresse_cabine]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->veterinaireRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Veterinaire();
        $fixture->setNom('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAdresse_cabine('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Veterinaire');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Veterinaire();
        $fixture->setNom('Value');
        $fixture->setNum_tel('Value');
        $fixture->setEmail('Value');
        $fixture->setAdresse_cabine('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'veterinaire[nom]' => 'Something New',
            'veterinaire[num_tel]' => 'Something New',
            'veterinaire[email]' => 'Something New',
            'veterinaire[adresse_cabine]' => 'Something New',
        ]);

        self::assertResponseRedirects('/veterinaire/');

        $fixture = $this->veterinaireRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getNum_tel());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getAdresse_cabine());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Veterinaire();
        $fixture->setNom('Value');
        $fixture->setNum_tel('Value');
        $fixture->setEmail('Value');
        $fixture->setAdresse_cabine('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/veterinaire/');
        self::assertSame(0, $this->veterinaireRepository->count([]));
    }
}

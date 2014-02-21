<?php

namespace Acme\StoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Acme\StoreBundle\Document\Product;

/**
 * Hello World command for demo purposes.
 *
 * You could also extend from Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
 * to get access to the container via $this->getContainer().
 *
 * @author Tobias Schultze <http://tobion.de>
 */
class HelloWorldCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('acme:hello')
            ->setDescription('Hello World example command')
            ->addArgument('who', InputArgument::OPTIONAL, 'Who to greet.', 'World')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command greets somebody or everybody:

<info>php %command.full_name%</info>

The optional argument specifies who to greet:

<info>php %command.full_name%</info> Fabien
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $output->writeln(sprintf('Hello <comment>%s</comment>!', $input->getArgument('who')));


        $product = new Product();
        $product->setLocale('es');
        $product->setName('A Foo Bar ES');
        $product->setPrice('19.99');
        $product->setTranslatableLocale('es');
        $dm->persist($product);
        $dm->flush();


        $p2 = $dm->getRepository('AcmeStoreBundle:Product')->find($product->getId());

        $p2->setName('A Foo Bar ru');
        $p2->setBody('ru');
        $p2->setPrice('19.99');
        $p2->setTranslatableLocale('de_de');

        $dm->persist($p2);
        $dm->flush();


        $p2 = $dm->getRepository('AcmeStoreBundle:Product')->find($product->getId());
        $p2->setLocale('es');
        $output->writeln($p2->getName());

    }
}

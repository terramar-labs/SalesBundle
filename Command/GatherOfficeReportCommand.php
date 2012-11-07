<?php

namespace TerraMar\Bundle\SalesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use TerraMar\Bundle\SalesBundle\Report\AbstractOfficeReport;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates a snapshot of a report for each active Office
 */
class GatherOfficeReportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('reports:office:gather')
            ->addArgument('report', InputArgument::REQUIRED, 'The report to generate snapshots for')
            ->setDescription('Gathers data for a specified report and creates a snapshot for each office')
            ->setHelp('The <info>reports:office:gather</info> command will gather information for a report and create
a snapshot that represents the specified report at the given time.

This command specifically handles Office-based reporting, collecting facts for
each report individually.

This command is mainly intended to be run as a job or other scheduling system
such as <comment>crontab</comment>.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $factory = $this->getContainer()->get('orkestra.report_factory');

        $report = $factory->getReport($input->getArgument('report'));

        if (!$report instanceof AbstractOfficeReport) {
            throw new \RuntimeException('Report must extend AbstractOfficeReport');
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $offices = $em->getRepository('TerraMarSalesBundle:Office')->findBy(array('active' => true));

        foreach ($offices as $office) {
            $snapshot = $report->createSnapshot($office);
            $em->persist($snapshot);
        }

        $em->flush();
    }
}

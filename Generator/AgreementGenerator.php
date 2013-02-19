<?php

namespace Terramar\Bundle\SalesBundle\Generator;

use Orkestra\Bundle\PdfBundle\Generator\AbstractPdfGenerator;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgreementGenerator extends AbstractPdfGenerator
{
    /**
     * Generates a new Agreement PDF
     *
     * @param array $parameters
     * @param array $options
     *
     * @return \TCPDF
     */
    public function doGenerate(array $parameters, array $options)
    {
        $pdf = $this->createPdf($options);

        $pdf->addPage();

        $logo = $parameters['office']->getLogo();

        if ($logo) {
            $pdf->image($logo->getPath(), 15, 10, 0, 15);
        }

        $pdf->write(0, $this->render('TerramarSalesBundle:Pdf/Agreement:welcomeLetter.txt.twig', $parameters));

        $pdf->addPage();

        if ($logo) {
            $pdf->image($logo->getPath(), 15, 10, 0, 15);
        }

        $pdf->write(0, $this->render('TerramarSalesBundle:Pdf/Agreement:agreementBody.txt.twig', $parameters));

        if (($signature = $parameters['contract']->getSignature())) {
            $pdf->image($signature->getPath(), '', '', 0, 30);
        }

        return $pdf;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'office',
            'customer',
            'contract',
            'agreement'
        ));

        $resolver->setAllowedTypes(array(
            'office' =>    'Terramar\Bundle\SalesBundle\Entity\Office',
            'customer' =>  'Terramar\Bundle\CustomerBundle\Entity\Customer',
            'contract' =>  'Terramar\Bundle\SalesBundle\Entity\Contract',
            'agreement' => 'Terramar\Bundle\SalesBundle\Entity\Agreement'
        ));
    }
}

<?php

namespace TerraMar\Bundle\SalesBundle\Form\Signature;

use Symfony\Component\Form\Exception\TransformationFailedException;
use TerraMar\Bundle\SalesBundle\Helper\FileHelper;
use Symfony\Component\Form\DataTransformerInterface;

class SignatureTransformer implements DataTransformerInterface
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Helper\FileHelper
     */
    protected $fileHelper;

    /**
     * @param \TerraMar\Bundle\SalesBundle\Helper\FileHelper $fileHelper
     */
    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    /**
     * A signature will always start blank
     *
     * @param mixed $val
     *
     * @return string
     */
    public function transform($val)
    {
        return '';
    }

    /**
     * @param mixed $val
     *
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function reverseTransform($val)
    {
        if (!$val) {
            return null;
        }

        $gdResource = SignatureConverter::convertToImage($val, array('imageSize' => array(400, 200)));

        $file = $this->fileHelper->saveImageToPng($gdResource, $this->fileHelper->getSignaturePath());

        return $file;
    }
}

<?php

namespace Terramar\Bundle\SalesBundle\Helper;

use Symfony\Component\HttpFoundation\File\File;
use Orkestra\Bundle\ApplicationBundle\Entity\File as OrkestraFile;
use Terramar\Bundle\SalesBundle\Model\ContractInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Terramar\Bundle\SalesBundle\Entity\Invoice;
use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\SalesBundle\Entity\Office;

class FileHelper
{
    /**
     * @var string
     */
    protected $internalPath;

    /**
     * Constructor
     *
     * @param string $internalPath
     */
    public function __construct($internalPath)
    {
        $this->internalPath = $internalPath;
    }

    /**
     * Creates a new logo File for an Office using the given UploadedFile
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function createLogoFromUploadedFile(UploadedFile $file, Office $office)
    {
        return $this->createFileFromUploadedFile($file, $this->getLogoPath($office));
    }

    /**
     * Creates a new File entity with the given UploadedFile and path
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string $path
     *
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function createFileFromUploadedFile(UploadedFile $file, $path = null)
    {
        if (!$path) {
            $path = $this->internalPath;
        }

        return OrkestraFile::createFromUploadedFile($file, $path, $this->generateUploadFilename($file));
    }

    /**
     * Generates a unique filename while attempting to maintain the original file extension
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    public function generateUploadFilename(File $file)
    {
        return $this->generateFilename($file->getExtension() ?: ($file->guessExtension() ?: 'file'));
    }

    public function generateFilename($extension)
    {
        return md5(uniqid(uniqid(), true)) . '.' . $extension;
    }

    /**
     * Gets the path where invoice PDFs are stored
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Invoice $invoice
     *
     * @return string
     */
    private function getInvoicePath(Invoice $invoice)
    {
        return $this->ensureDirectoryExists($this->internalPath . '/invoices');
    }

    public function getInvoiceFilename(Invoice $invoice)
    {
        if (!$invoice->getId()) {
            return $this->getInvoicePath($invoice) . DIRECTORY_SEPARATOR . $this->generateFilename('pdf');
        }

        return $this->getInvoicePath($invoice) . DIRECTORY_SEPARATOR . $invoice->getId() . '.pdf';
    }

    /**
     * Gets the path where contract PDFs are stored
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @return string
     */
    private function getContractPath(ContractInterface $contract)
    {
        return $this->ensureDirectoryExists($this->internalPath . DIRECTORY_SEPARATOR . 'contracts');
    }

    public function getContractFilename(ContractInterface $contract)
    {
        if (!$contract->getId()) {
            return $this->getContractPath($contract) . DIRECTORY_SEPARATOR . $this->generateFilename('pdf');
        }

        return $this->getContractPath($contract) . DIRECTORY_SEPARATOR . $contract->getId() . '.pdf';
    }

    /**
     * Gets the path where signatures are stored
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @return string
     */
    public function getSignaturePath()
    {
        return $this->ensureDirectoryExists($this->internalPath . '/signatures');
    }

    public function getSignatureFilename(ContractInterface $contract)
    {
        if (!$contract->getId()) {
            return $this->getSignaturePath() . DIRECTORY_SEPARATOR . $this->generateFilename('png');
        }

        return $this->getSignaturePath() . DIRECTORY_SEPARATOR . $contract->getId() . '.png';
    }

    /**
     * @param resource $gdResource  A GD image resource
     * @param string $path          The path (no filename) to save the image
     * @param string $filename      The filename to save the image as
     *
     * @throws \RuntimeException
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function saveImageToPng($gdResource, $path = null, $filename = null)
    {
        if (null === $path) {
            $path = $this->internalPath;
        }

        if (null === $filename) {
            $filename = md5(uniqid('imagepng', true)) . '.png';
        }

        $fullPath = $path . '/' . $filename;
        $result = imagepng($gdResource, $fullPath, 5);

        if (!$result) {
            throw new \RuntimeException(sprintf('Could not save image to %s', $fullPath));
        }

        $file = new File($fullPath, $filename, 'image/png', filesize($fullPath));

        return $file;
    }

    /**
     * Gets the path where logos are stored
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return string
     */
    public function getLogoPath(Office $office)
    {
        return $this->ensureDirectoryExists($this->internalPath . '/logos');
    }

    /**
     * Attempts to create the given directory, throwing an exception if unable to
     *
     * @param string $path
     *
     * @return string $path
     * @throws \RuntimeException
     */
    private function ensureDirectoryExists($path)
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new \RuntimeException(sprintf('Could not create directory "%s"', $path));
            }
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getSavePath()
    {
        return $this->internalPath;
    }
}

<?php

namespace App\Serializer;

use ApiPlatform\Core\Problem\Serializer\ErrorNormalizerTrait;
use App\Entity\Delivrable;
use App\Entity\HomeworkFile;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

class FileNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'AppFileNormalizerAlreadyCalled';

    public function __construct(private StorageInterface $storage)
    {
        
    }

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return !isset($context [self::ALREADY_CALLED]) && ($data instanceof Delivrable || $data instanceof HomeworkFile);
    }

    /**
     * @param Delivrable $object
     * @param HomeworkFile $object
     */
    public function normalize($object, ?string $format = null, array $context = [])
    {
        $object->setFileUrl($this->storage->resolveUri($object, 'file'));
        $context [self::ALREADY_CALLED] = true;
        return $this->normalizer->normalize($object, $format, $context);
    }
    
}

<?php

namespace App\Upload;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class DelivrableDirectoryNamer implements DirectoryNamerInterface
{
    public function directoryName($obj, PropertyMapping $mapping): string
    {
        $homework = strtolower($obj->getHomework()->getName());
        $replace = $this->cleanString($homework);
        
        $classroomSlug = $obj->getHomework()->getClassroom()->getSlug();
        $schoolSlug = $obj->getHomework()->getClassroom()->getSchool()->getSlug();

        $year = date("Y");

        $path = "$schoolSlug/$year/$classroomSlug/$replace";
        
        return $path;
    }

    function cleanString($text) {
        
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[óòôõºö]/u'   =>   'o',
            '/[úùûü]/u'     =>   'u', 
            '/ç/'           =>   'c',
            '/ñ/'           =>   'n',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   '', // Literally a single quote
            '/[“”«»„]/u'    =>   '', // Double quote
            '/ /'           =>   '_',
            '/:/'           =>   '',
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
}
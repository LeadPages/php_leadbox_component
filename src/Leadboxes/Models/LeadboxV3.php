<?php

namespace Leadpages\Leadboxes\Models;

use Twig_Environment;
use Twig_Loader_Filesystem;

class LeadboxV3 extends Leadbox
{
    public $options = [
        'type' => 'text', // button, timed, exit, image
        'text' => 'Click to Subscribe',

        'days' => 0, // days between showing
        'views' => 0, // visits between showing 
        'delay' => 5, // seconds

        // button-only
        'btnColor' => '#32C88C',
        'btnTextColor' => '#FFFFFF',
        'btnRoundness' => 20,
        'btnShadowSize' => 2,
    ];

    public function setFromArray(Array $data)
    {
        $content = $data['content'];
        $meta = $data['_meta'];

        $this->setName($content['name'])
            ->setId($meta['id'])
            ->setMeta($meta)
            ->setContent($content);

        return $this;
    }

    public function renderEmbedCode()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates/');
        $twig = new Twig_Environment($loader, []);
       
        $html = $twig->render('leadboxv3.html', [
            'id' => $this->getId(),
            'options' => $this->options,
        ]); 

        $this->setEmbedCode($html);

        return $this->getEmbedCode();
    }
}

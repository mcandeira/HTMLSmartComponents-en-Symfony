<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension{

    private string $nextID = 'smartID-AAA';

    public function getFunctions():array
    {
        return [
            new TwigFunction('start', [$this, 'start']),
            new TwigFunction('end', [$this, 'end']),
        ];
    }

    public function start(string $path, ?array $vals = []): object{

        $vals['error'] = $path;

        $component = (object) array(
            'ID' => $this -> nextID,
            'component' => ["smartComponents/$path.html.twig", "smartComponents/$path.html", 'smartComponents/includeError.html.twig'],
            'vals' => $vals
        );

        $this -> nextID .= '-AA';

        return $component;
    }

    public function end(): object{
        $this -> nextID = substr($this -> nextID, 0, strrpos($this -> nextID, '-'));
        $this -> nextID++;
        return (object) ['ID' => substr($this -> nextID, 0, strrpos($this -> nextID, '-'))];
    }

}
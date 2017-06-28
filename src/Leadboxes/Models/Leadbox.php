<?php

namespace Leadpages\Leadboxes\Models;

use Leadpages\Leadboxes\Contracts\LeadboxInterface;

abstract class Leadbox implements LeadboxInterface
{
	public $_meta = [];

    public $content = [];

	public $id;

	public $name;

    public $embed_code;

    public $type = 'text';

    protected $valid_types = [
        'text',
        'button',
        'image',
        'timed',
        'exit',
    ];

	public function getId()
	{
        return $this->id;
	}

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

	public function getName()
	{
        return $this->name;
	}

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

	public function getEmbedCode()
	{
        return $this->embed_code;
	}

    public function setEmbedCode($html)
    {
        $this->embed_code = $html;
        return $this;
    }

	public function getMeta()
	{
        return $this->_meta;
	}

    public function setMeta($meta)
    {
        $this->_meta = $meta;
        return $this;
    }

    public function getContent()
	{
        return $this->content;
	}

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}

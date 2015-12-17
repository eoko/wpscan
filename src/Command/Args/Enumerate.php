<?php

namespace Eoko\Wpscan\Command\Args;

class Enumerate implements ArgsInterface
{
    protected $enum = [];

    protected function hasEnum($name)
    {
        return in_array($name, $this->enum, true);
    }

    protected function addEnum($name)
    {
        if (!$this->hasEnum($name)) {
            $this->enum[] = $name;
        }
    }

    protected function removeEnum($name)
    {
        if (($key = array_search($name, $this->enum, true)) !== false) {
            unset($this->enum[$key]);
        }
    }

    public function plugins($enum = true)
    {
        (boolean) $enum ? $this->addEnum('p') : $this->removeEnum('p');
        return $this;
    }

    public function allPlugins($enum = true)
    {
        (boolean) $enum ? $this->addEnum('ap') : $this->removeEnum('ap');
        return $this;
    }

    public function vulnerablePlugins($enum = true)
    {
        (boolean) $enum ? $this->addEnum('vp') : $this->removeEnum('vp');
        return $this;
    }

    public function __toString()
    {
        return '--enumerate ' . implode(',', $this->enum);
    }
}

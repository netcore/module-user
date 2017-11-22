<?php
namespace Modules\User\Traits;

trait ReplaceableAttributes {

    /**
     * Returns replaceable data
     *
     * @return array
     */
    public function getReplacaeble(): array
    {
        $attributes     = $this->replaceable       ?? [];
        $prefix         = $this->replaceablePrefix ?? '';
        $replaceable    = [];

        foreach ($attributes as $attribute)
        {
            $replaceable[$prefix . $attribute] = $this[$attribute] ?? null;
        }

        return $replaceable;
    }
    
}

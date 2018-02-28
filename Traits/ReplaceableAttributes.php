<?php

namespace Modules\User\Traits;

trait ReplaceableAttributes
{

    /**
     * Returns replaceable data
     *
     * @return array
     */
    public function getReplaceable(): array
    {
        $attributes = $this->replaceable ?? [];
        $prefix = $this->replaceablePrefix ?? '';
        $replaceable = [];

        foreach ($attributes as $attribute) {
            $replaceable[strtoupper($prefix . $attribute)] = $this[$attribute] ?? null;
        }

        return $replaceable;
    }

}

<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer as LeagueArraySerializer;

/**
 * This serializer compose data without resource key
 * also if value is null it will return actual null!
 */
class ArraySerializer extends LeagueArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return $this->serializeWithResourceKey($resourceKey, $data);
    }

    public function item($resourceKey, array $data)
    {
        return $this->serializeWithResourceKey($resourceKey, $data);
    }

    public function null()
    {
        return null;
    }

    protected function serializeWithResourceKey($resourceKey, array $data)
    {
        return $resourceKey === 'data' ? [$resourceKey => $data] : $data;
    }
}
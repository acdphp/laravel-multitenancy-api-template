<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, FastRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function wrappedData(array $data): array
    {
        return [
            'data' => $data,
        ];
    }

    protected function paginatedStructure(array $data): array
    {
        return [
            'data' => [array_is_list($data) ? $data : array_keys($data)],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ];
    }
}

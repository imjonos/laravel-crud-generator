<?php
/**
 * Eugeny Nosenko 2021
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected function ajax($method, $route, array $parameters = []) {
        return $this->json(
            $method, $route, $parameters, ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );
    }
}

<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Traits;

use Nos\CRUD\Http\Requests\ExportRequest;

/**
 * Trait Exportable for the CRUD Controller
 * @package Nos\CRUD\Traits
 */
trait Exportable
{

    /**
     * Export action
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        return $this->getExportObject()->download('export.xlsx');
    }

    /**
     * Set the Export class
     * @return mixed
     */
    abstract public function getExportObject();
}

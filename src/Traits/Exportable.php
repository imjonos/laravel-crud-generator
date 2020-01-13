<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Traits;

use CodersStudio\CRUD\Http\Requests\ExportRequest;

/**
 * Trait Exportable for the CRUD Controller
 * @package CodersStudio\CRUD\Traits
 */
trait Exportable{

    /**
     * Set the Export class
     * @return mixed
     */
    abstract public function getExportObject();

    /**
     * Export action
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        return $this->getExportObject()->download('export.xlsx');
    }
}

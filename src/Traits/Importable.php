<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Traits;


use Illuminate\Support\Facades\Storage;
use Nos\CRUD\Http\Requests\ImportRequest;

/**
 * Trait Imporrtable for the CRUD Controller
 * @package Nos\CRUD\Traits
 */
trait Importable
{

    /**
     * @param ImportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(ImportRequest $request)
    {
        $file = $request->import_file->store("", "local");
        $this->getImportObject()->import($file, 'local', \Maatwebsite\Excel\Excel::XLSX);
        Storage::delete($file);

        return response()->json([], 204);
    }

    /**
     * Set the Import class
     * @return mixed
     */
    abstract public function getImportObject();
}

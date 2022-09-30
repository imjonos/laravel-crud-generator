<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Traits;


use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function import(ImportRequest $request): JsonResponse
    {
        $file = $request->import_file->store("", "local");
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        $readerType = '';

        if ($ext === 'csv') {
            $readerType = \Maatwebsite\Excel\Excel::CSV;
        } elseif ($ext === 'xls') {
            $readerType = \Maatwebsite\Excel\Excel::XLSX;
        }

        $this->getImportObject()->import($file, 'local', $readerType);
        Storage::delete($file);

        return response()->json([], 204);
    }

    /**
     * Set the Import class
     * @return mixed
     */
    abstract public function getImportObject(): mixed;
}

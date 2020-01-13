<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Traits;


use CodersStudio\CRUD\Http\Requests\ImportRequest;
use Illuminate\Support\Facades\Storage;

/**
 * Trait Imporrtable for the CRUD Controller
 * @package CodersStudio\CRUD\Traits
 */
trait Importable{

    /**
     * Set the Import class
     * @return mixed
     */
    abstract public function getImportObject();

    /**
     * @param ImportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(ImportRequest $request)
    {
        $file = $request->import_file->store("","local");
        $this->getImportObject()->import($file, 'local', \Maatwebsite\Excel\Excel::XLSX);
        Storage::delete($file);
        return response()->json([],204);
    }
}

<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DocumentType::query();

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'name';

        // Filtro por nombre
        if ($filterType == 'name' && $request->filled('name_search')) {
            $buscado = strtolower(trim($request->name_search));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por abreviatura
        if ($filterType == 'abbreviation' && $request->filled('abbreviation_search')) {
            $buscado = strtolower(trim($request->abbreviation_search));
            $query->whereRaw('LOWER(abbreviation) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por rango de fechas
        if ($filterType == 'date_range') {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        // Paginamos y mantenemos los filtros en la URL
        $documentTypes = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search', 'abbreviation_search', 'date_from', 'date_to']));

        return view('modules.document_type.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.document_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:document_types',
            'abbreviation' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.document-types.create')
                ->withErrors($validator)
                ->withInput();
        }

        DocumentType::create($request->all());

        return redirect()->route('admin.document-types.index')
            ->with('success', 'Tipo de documento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        return view('modules.document_type.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        return view('modules.document_type.edit', compact('documentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:document_types,name,' . $documentType->id,
            'abbreviation' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.document-types.edit', $documentType->id)
                ->withErrors($validator)
                ->withInput();
        }

        $documentType->update($request->all());

        return redirect()->route('admin.document-types.index')
            ->with('success', 'Tipo de documento: '.$documentType->name.' actualizado  exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        // Verificar si hay usuarios asociados a este tipo de documento
        if ($documentType->users()->count() > 0) {
            return redirect()->route('admin.document-types.index')
                ->with('error', 'No se puede eliminar este Tipo de Documento porque hay usuarios asociados.');
        }

        try {
            $documentType->delete();
            return redirect()->route('admin.document-types.index')
                ->with('success', 'Tipo de Documento eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.document-types.index')
                ->with('error', 'No se puede eliminar este Tipo de Documento porque está siendo utilizado en el sistema.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    public function index(Request $request)
    {
        $query = Question::with('sections');

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'text';

        // Filtro por texto de la pregunta
        if ($filterType == 'text' && $request->filled('text_search')) {
            $buscado = strtolower(trim($request->text_search));
            $query->whereRaw('LOWER(text) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por sección
        if ($filterType == 'section' && $request->filled('section_search')) {
            $buscado = strtolower(trim($request->section_search));
            $query->whereHas('sections', function($q) use ($buscado) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
            });
        }

        // Filtro por tipo de vehículo
        if ($filterType == 'vehicle_type' && $request->filled('vehicle_type_search')) {
            $buscado = strtolower(trim($request->vehicle_type_search));
            $query->whereHas('sections.vehicleTypes', function($q) use ($buscado) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
            });
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
        $questions = $query
            ->paginate(20)
            ->appends($request->only(['filter_type', 'text_search', 'section_search', 'vehicle_type_search', 'date_from', 'date_to']));

        return view('modules.question.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // Cargar todas las secciones con sus tipos de vehículo relacionados
        $sections = Section::with('vehicleTypes')->get();
        
        return view('modules.question.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'sections' => 'required|array',
            'sections.*' => 'exists:sections,id',
            'text' => 'required|string|unique:questions,text',
        ], [
            'sections.required' => 'Debe seleccionar al menos una sección.',
            'sections.*.exists' => 'Una de las secciones seleccionadas no existe.',
            'text.required' => 'El texto de la pregunta es obligatorio.',
            'text.unique' => 'Ya existe una pregunta con este texto.',
        ]);

        $question = Question::create([
            'text' => $request->text,
        ]);

        // Adjuntar las secciones seleccionadas a la pregunta
        $question->sections()->attach($request->sections);

        return redirect()->route(Auth::user()->role->name . '.questions.index')
            ->with('success', 'Pregunta "'.$request->text.'" creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
        $question->load('sections');
        return view('modules.question.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
        $sections = Section::all();
        $selectedSections = $question->sections->pluck('id')->toArray();
        return view('modules.question.edit', compact('question', 'sections', 'selectedSections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
        $request->validate([
            'sections' => 'required|array',
            'sections.*' => 'exists:sections,id',
            'text' => 'required|string|unique:questions,text,' . $question->id,
        ], [
            'sections.required' => 'Debe seleccionar al menos una sección.',
            'sections.*.exists' => 'Una de las secciones seleccionadas no existe.',
            'text.required' => 'El texto de la pregunta es obligatorio.',
            'text.unique' => 'Ya existe una pregunta con este texto.',
        ]);

        $question->update([
            'text' => $request->text,
        ]);

        // Sincronizar las secciones seleccionadas
        $question->sections()->sync($request->sections);

        return redirect()->route(Auth::user()->role->name . '.questions.index')
            ->with('success', 'Pregunta: "'.$question->text.'" actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
     * 
     * Aunque implementamos la lógica, esta funcionalidad no estará
     * disponible en la interfaz para ningún rol por razones de transparencia.
     */
    public function destroy(Question $question)
    {
        // Verificar si hay respuestas asociadas a esta pregunta
        if ($question->answers()->count() > 0) {
            return redirect()->route(Auth::user()->role->name . '.questions.index')
                ->with('error', 'No se puede eliminar esta pregunta('.$question->text.') porque tiene respuestas asociadas.');
        }
        
        try {
            $question->delete();
            return redirect()->route(Auth::user()->role->name . '.questions.index')
                ->with('success', 'Pregunta '.$question->text.' eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name . '.questions.index')
                ->with('error', 'No se puede eliminar la pregunta '.$question->text.' porque está siendo utilizada en el sistema.');
        }
    }
}

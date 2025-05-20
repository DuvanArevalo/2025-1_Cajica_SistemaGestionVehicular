<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\PreoperationalForm;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Answer::with(['form', 'question']);

        // Filtrar por usuario si es conductor
        if (strtolower(Auth::user()->role->name) === 'conductor') {
            $query->whereHas('form', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'form';

        // Filtro por formulario
        if ($filterType == 'form' && $request->filled('form_search')) {
            $buscado = trim($request->form_search);
            $query->whereHas('form', function($q) use ($buscado) {
                $q->where('id', 'LIKE', "%{$buscado}%");
            });
        }

        // Filtro por pregunta
        if ($filterType == 'question' && $request->filled('question_search')) {
            $buscado = strtolower(trim($request->question_search));
            $query->whereHas('question', function($q) use ($buscado) {
                $q->whereRaw('LOWER(text) LIKE ?', ["%{$buscado}%"]);
            });
        }

        // Filtro por valor
        if ($filterType == 'value' && $request->filled('value_search')) {
            $query->where('value', $request->value_search);
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
        $answers = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'form_search', 'question_search', 'value_search', 'date_from', 'date_to']));

        return view('modules.answer.index', compact('answers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forms = PreoperationalForm::with([
            'vehicle.vehicleType.sections' => function($query) {
                $query->with('questions');
            }
        ])->orderBy('created_at', 'desc')->get();
        
        return view('modules.answer.create', compact('forms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_id' => 'required|exists:preoperational_forms,id',
            'section_id' => 'required|exists:sections,id',
            'question_id' => 'required|exists:questions,id',
            'value' => 'required|boolean',
        ], [
            'form_id.required' => 'El formulario es obligatorio.',
            'form_id.exists' => 'El formulario seleccionado no existe.',
            'section_id.required' => 'La sección es obligatoria.',
            'section_id.exists' => 'La sección seleccionada no existe.',
            'question_id.required' => 'La pregunta es obligatoria.',
            'question_id.exists' => 'La pregunta seleccionada no existe.',
            'value.required' => 'Debe seleccionar Sí o No como respuesta.',
            'value.boolean' => 'El valor debe ser Sí o No.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si ya existe una respuesta para esta combinación de formulario y pregunta
        $existingAnswer = Answer::where('form_id', $request->form_id)
            ->where('question_id', $request->question_id)
            ->first();
    
        // Asegurarse de que el valor sea booleano
        $value = (bool)$request->value;
        
        // Obtener información del formulario y la pregunta para mensajes más específicos
        $questionText = Question::find($request->question_id)->text;
        $formInfo = PreoperationalForm::with('vehicle')->find($request->form_id);
        $formIdentifier = "Formulario #{$formInfo->id} - {$formInfo->vehicle->plate}";
    
        if ($existingAnswer) {
            // Actualizar la respuesta existente
            $existingAnswer->update([
                'value' => $value,
            ]);
            
            return redirect()->route(Auth::user()->role->name . '.answers.index')
                ->with('warning', "¡Atención! La respuesta a la pregunta \"{$questionText}\" del {$formIdentifier} ya existía y ha sido actualizada.");
        } else {
            // Crear una nueva respuesta
            $answer = Answer::create([
                'form_id' => $request->form_id,
                'question_id' => $request->question_id,
                'value' => $value,
            ]);
    
            return redirect()->route(Auth::user()->role->name . '.answers.index')
                ->with('success', "Respuesta creada exitosamente para la pregunta \"{$questionText}\" del {$formIdentifier}.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        $answer->load(['form', 'question']);
        return view('modules.answer.show', compact('answer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Answer $answer)
    {
        $forms = PreoperationalForm::orderBy('created_at', 'desc')->get();
        $questions = Question::all();
        
        return view('modules.answer.edit', compact('answer', 'forms', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|boolean',
        ], [
            'value.required' => 'Debe seleccionar Sí o No como respuesta.',
            'value.boolean' => 'El valor debe ser Sí o No.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Asegurarse de que el valor sea booleano
        $value = (bool)$request->value;
        
        // Cargar información relacionada para mensajes más específicos
        $answer->load(['form.vehicle', 'question']);
        $questionText = $answer->question->text;
        $formIdentifier = "Formulario #{$answer->form->id} - {$answer->form->vehicle->plate}";
        
        $answer->update([
            'value' => $value,
        ]);
    
        return redirect()->route(Auth::user()->role->name . '.answers.index')
            ->with('success', "Respuesta actualizada exitosamente para la pregunta \"{$questionText}\" del {$formIdentifier}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        try {
            // Cargar información relacionada para mensajes más específicos
            $answer->load(['form.vehicle', 'question']);
            $questionText = $answer->question->text;
            $formIdentifier = "Formulario #{$answer->form->id} - {$answer->form->vehicle->plate}";
            
            $answer->delete();
            
            return redirect()->route(Auth::user()->role->name . '.answers.index')
                ->with('success', "Respuesta eliminada exitosamente para la pregunta \"{$questionText}\" del {$formIdentifier}.");
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name . '.answers.index')
                ->with('error', 'No se puede eliminar la respuesta porque está siendo utilizada en el sistema.');
        }
    }
}

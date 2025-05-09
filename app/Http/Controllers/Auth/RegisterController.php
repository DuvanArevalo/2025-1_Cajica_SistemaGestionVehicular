<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @return string
     */
    protected function redirectTo() {        
        if (Auth::check() && Auth::user()->role) {
            $roleName = strtolower(Auth::user()->role->name);
    
            return match ($roleName) {
                'admin'     => route('admin.dashboard'),
                'sst'       => route('sst.dashboard'),
                'conductor' => route('conductor.dashboard'),
                default     => route('login'),
            };
        }
        return route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Muestra el formulario de registro con tipos de documento.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // 1. Recupera todos los tipos de documento
        $documentTypes = DocumentType::all();

        // 2. Pasa la colección a la vista auth.register
        return view('auth.register', compact('documentTypes'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name1' => ['required', 'string', 'max:255'],
            'name2' => ['nullable', 'string', 'max:255'], // Campo opcional
            'lastname1' => ['required', 'string', 'max:255'],
            'lastname2' => ['nullable', 'string', 'max:255'], // Campo opcional
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Asegura unicidad en la tabla users
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'document_type_id' => ['required', 'integer', 'exists:document_types,id'], // Valida que exista en la tabla document_types
            'document_number' => ['required', 'string', 'max:20',
                // Regla de unicidad compuesta: el par document_type_id y document_number debe ser único en la tabla users
                Rule::unique('users')->where(function ($query) use ($data) {
                    return $query->where('document_type_id', $data['document_type_id'] ?? null);
                }),
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Asignar un role_id por defecto. Cambia '1' por el ID del rol deseado para nuevos usuarios.
        // Podrías buscarlo si tienes el nombre: Role::where('name', 'Cliente')->firstOrFail()->id;
        return User::create([
            'name1' => $data['name1'],
            'name2' => $data['name2'] ?? null, // Asigna null si no viene
            'lastname1' => $data['lastname1'],
            'lastname2' => $data['lastname2'] ?? null, // Asigna null si no viene
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'document_type_id' => $data['document_type_id'],
            'document_number' => $data['document_number'],
            'role_id' => 3,             // conductor por defecto
            'is_active' => false,       // inactivo por defecto
        ]);
    }
}

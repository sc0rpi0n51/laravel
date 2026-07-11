<?php
namespace App\Http\Controllers\Admin;use App\Http\Controllers\Controller;use App\Models\Asistencia;use Illuminate\View\View;
class AsistenciaController extends Controller{public function index():View{return view('admin.asistencias.index',['asistencias'=>Asistencia::with(['estudiante.user','claseProfesor.materia'])->latest('fecha')->paginate(20)]);}}

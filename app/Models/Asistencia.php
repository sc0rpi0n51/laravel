<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Asistencia extends Model{use SoftDeletes;protected $fillable=['estudiante_id','clase_profesor_id','fecha','hora','estado','metodo','observacion'];protected $casts=['fecha'=>'date'];public function estudiante():BelongsTo{return $this->belongsTo(Estudiante::class);}public function claseProfesor():BelongsTo{return $this->belongsTo(ClaseProfesor::class);}}

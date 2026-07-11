<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;use Illuminate\Database\Eloquent\Relations\HasMany;
class Grado extends Model{use SoftDeletes;protected $fillable=['nombre'];public function estudiantes():HasMany{return $this->hasMany(Estudiante::class);}public function clasesProfesor():HasMany{return $this->hasMany(ClaseProfesor::class);}}

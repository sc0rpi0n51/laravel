<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;use Illuminate\Database\Eloquent\Relations\HasMany;
class Materia extends Model{use SoftDeletes;protected $fillable=['nombre','codigo'];public function clasesProfesor():HasMany{return $this->hasMany(ClaseProfesor::class);}}

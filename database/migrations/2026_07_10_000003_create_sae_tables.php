<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('grados',fn(Blueprint $t)=>$this->catalogo($t));
  Schema::create('secciones',fn(Blueprint $t)=>$this->catalogo($t));
  Schema::create('materias',function(Blueprint $t){$t->id();$t->string('nombre');$t->string('codigo')->unique();$t->timestamps();$t->softDeletes();});
  Schema::create('estudiantes',function(Blueprint $t){$t->id();$t->foreignId('user_id')->unique()->constrained()->restrictOnDelete();$t->foreignId('grado_id')->constrained('grados')->restrictOnDelete();$t->foreignId('seccion_id')->constrained('secciones')->restrictOnDelete();$t->string('matricula')->unique();$t->string('qr_token',64)->unique();$t->timestamps();$t->softDeletes();});
  Schema::create('clase_profesor',function(Blueprint $t){$t->id();$t->foreignId('profesor_id')->constrained('users')->restrictOnDelete();$t->foreignId('materia_id')->constrained('materias')->restrictOnDelete();$t->foreignId('grado_id')->constrained('grados')->restrictOnDelete();$t->foreignId('seccion_id')->constrained('secciones')->restrictOnDelete();$t->timestamps();$t->softDeletes();$t->unique(['profesor_id','materia_id','grado_id','seccion_id'],'carga_docente_unique');});
  Schema::create('asistencias',function(Blueprint $t){$t->id();$t->foreignId('estudiante_id')->constrained('estudiantes')->restrictOnDelete();$t->foreignId('clase_profesor_id')->constrained('clase_profesor')->restrictOnDelete();$t->date('fecha');$t->time('hora');$t->enum('estado',['presente','retardo','falta'])->default('presente');$t->enum('metodo',['qr','manual'])->default('qr');$t->text('observacion')->nullable();$t->timestamps();$t->softDeletes();$t->unique(['estudiante_id','clase_profesor_id','fecha'],'asistencia_diaria_unique');});
 }
 private function catalogo(Blueprint $t):void{$t->id();$t->string('nombre')->unique();$t->timestamps();$t->softDeletes();}
 public function down():void{foreach(['asistencias','clase_profesor','estudiantes','materias','secciones','grados'] as $table)Schema::dropIfExists($table);}
};

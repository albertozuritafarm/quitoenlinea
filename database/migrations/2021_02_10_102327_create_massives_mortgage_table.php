<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMassivesMortgageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('massives_mortgage', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_suc_apertura');
            $table->string('codigo_age_apertura');
            $table->string('sucursal_agencia_apertura');
            $table->string('codigo_suc_manejo');
            $table->string('codigo_age_manejo');
            $table->string('sucursal_agencia_manejo');
            $table->string('producto');
            $table->string('nombre_cliente');
            $table->string('identificacion');
            $table->string('sexo');
            $table->string('fecha_nacimiento');
            $table->string('fecha_desembolso');
            $table->string('fecha_vencimiento');
            $table->string('fecha_vencimiento_original');
            $table->string('numero_operacion');
            $table->string('status');
            $table->string('monto_prestamo');
            $table->string('saldo');
            $table->string('saldo_interes_corriente_cuota');
            $table->string('tasa');
            $table->string('numero_cuota');
            $table->string('fecha_inicio_cuota');
            $table->string('fecha_cuota');
            $table->string('tipo_seguro');
            $table->string('factor_gravamen');
            $table->string('factor_incendio');
            $table->string('factor_deuda_protegida');
            $table->string('monto_asegurado');
            $table->string('valor_desgravamen');
            $table->string('valor_incendio');
            $table->string('valor_deuda_protegida');
            $table->string('valor_seguro_vehiculo');
            $table->string('valor_seguro_dispositivo_rastreo');
            $table->string('valor_seguros_premium');
            $table->string('numero_poliza_incendio');
            $table->string('nombre_conyuge');
            $table->string('identificacion_conyuge');
            $table->string('fecha_nacimiento_conyuge');
            $table->string('estraprimado');
            $table->string('nombre_oficial');
            $table->string('identificacion_aseguradora');
            $table->string('nombre_aseguradora');
            $table->string('operacion_referencia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('massives_mortgage');
    }
}

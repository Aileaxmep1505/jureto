@extends('layouts.app')

@section('title', $client->exists ? 'Editar cliente' : 'Nuevo cliente')
@section('header', $client->exists ? 'Editar cliente' : 'Nuevo cliente')

@section('content')
@php
  $isEdit = $client->exists;
  $v = function($key,$default=null) use ($client){ return old($key, $client->{$key} ?? $default); };
@endphp

<style>
:root{
  --mint:#48cfad; --mint-dark:#34c29e;
  --ink:#2a2e35; --muted:#7a7f87; --line:#e9ecef; --card:#ffffff;
}
*{ box-sizing:border-box }
.page{ max-width:1100px; margin:12px auto 24px; padding:0 14px }

/* ===== Panel ===== */
.panel{ background:var(--card); border-radius:16px; box-shadow:0 16px 40px rgba(18,38,63,.12); overflow:hidden }
.panel-head{
  padding:20px 22px; border-bottom:1px solid var(--line);
  display:flex; align-items:center; justify-content:space-between; gap:14px
}
.hgroup h2{ margin:0; font-weight:800; color:var(--ink); letter-spacing:-.02em }
.hgroup p{ margin:6px 0 0; color:var(--muted); font-size:14px }
@media (max-width:640px){ .hgroup p{ display:none } }

.back-link{
  display:inline-flex; align-items:center; gap:8px; color:var(--muted);
  text-decoration:none; padding:9px 12px; border-radius:10px; border:1px solid var(--line); background:#fff;
}
.back-link:hover{ color:var(--ink); border-color:#dfe3e8 }

/* ===== Form ===== */
.form{ padding:22px }
.grid{ display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px }
.grid-3{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:20px }
@media (max-width:1000px){ .grid-3{ grid-template-columns:repeat(2,minmax(0,1fr)) } }
@media (max-width:720px){ .grid,.grid-3{ grid-template-columns:1fr } }

.field{
  position:relative; background:#fff; border:1px solid var(--line); border-radius:12px;
  padding:16px 14px 10px; transition:box-shadow .2s, border-color .2s
}
.field:focus-within{ border-color:#d8dee6; box-shadow:0 8px 24px rgba(18,38,63,.08) }
.field input{
  width:100%; border:0; outline:0; background:transparent; font-size:15px; color:var(--ink); padding-top:8px
}
.field label{
  position:absolute; left:14px; top:12px; color:var(--muted); font-size:13px;
  transition:transform .15s, color .15s, font-size .15s, top .15s; pointer-events:none
}
.field input::placeholder{ color:transparent }
.field input:focus + label,
.field input:not(:placeholder-shown) + label{
  top:6px; transform:translateY(-9px); font-size:11px; color:var(--mint-dark)
}

/* ===== Switch (con leyendas) ===== */
.switch-wrap{
  display:flex; align-items:center; justify-content:space-between; gap:16px;
  background:#fff; border:1px solid var(--line); border-radius:12px; padding:14px
}
.switch-legend{ display:flex; align-items:center; gap:10px; color:var(--muted); font-size:14px }
.dot{ width:8px; height:8px; border-radius:999px; background:#e5e7eb }
.dot--on{ background:#10b981 }

.switch{ display:inline-flex; align-items:center; gap:10px; user-select:none }
.switch input{ display:none }
.switch .track{
  width:48px; height:26px; border-radius:999px; background:#e9edf2; position:relative; transition:background .2s
}
.switch .thumb{
  width:22px; height:22px; border-radius:50%; background:#fff; position:absolute; top:2px; left:2px;
  box-shadow:0 2px 8px rgba(0,0,0,.15); transition:left .18s ease
}
.switch input:checked + .track{ background:var(--mint) }
.switch input:checked + .track .thumb{ left:24px }

/* ===== Actions ===== */
.actions{ display:flex; gap:12px; justify-content:flex-end; margin-top:16px; padding:0 4px }
.btn{
  border:0; border-radius:12px; padding:11px 16px; font-weight:800; cursor:pointer;
  transition:transform .05s, box-shadow .2s, background .2s, color .2s
}
.btn:active{ transform:translateY(1px) }
.btn-primary{ background:var(--mint); color:#fff; box-shadow:0 12px 22px rgba(72,207,173,.26) }
.btn-primary:hover{ background:#fff; color:#111; box-shadow:0 16px 32px rgba(0,0,0,.18) }
.btn-ghost{ background:#fff; color:#111; border:1px solid var(--line) }          /* sin línea verde */
.btn-ghost:hover{ background:#fff; color:#111; box-shadow:0 12px 26px rgba(0,0,0,.14); border-color:#fff }

.is-invalid{ border-color:#f9c0c0 !important }
.error{ color:#cc4b4b; font-size:12px; margin-top:6px }
</style>

<div class="page">
  <div class="panel">
    <div class="panel-head">
      <div class="hgroup">
        <h2>{{ $isEdit ? 'Editar cliente' : 'Agregar cliente' }}</h2>
        <p>{{ $isEdit ? 'Actualiza los datos del cliente.' : 'Crea un nuevo cliente (Gobierno o Empresa).' }}</p>
      </div>
      <a href="{{ route('clients.index') }}" class="back-link" title="Volver">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Volver
      </a>
    </div>

    <form class="form"
      action="{{ $isEdit ? route('clients.update',$client) : route('clients.store') }}"
      method="POST">
      @csrf
      @if($isEdit) @method('PUT') @endif

      {{-- Fila 1: nombre / email (requeridos) --}}
      <div class="grid">
        <div>
          <div class="field @error('nombre') is-invalid @enderror">
            <input type="text" name="nombre" id="f-nombre" value="{{ $v('nombre') }}" placeholder=" " required>
            <label for="f-nombre">Nombre (requerido)</label>
          </div>
          @error('nombre')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <div class="field @error('email') is-invalid @enderror">
            <input type="email" name="email" id="f-email" value="{{ $v('email') }}" placeholder=" " required>
            <label for="f-email">Correo (requerido)</label>
          </div>
          @error('email')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Fila 2: tipo_cliente / RFC --}}
      <div class="grid">
        <div>
          <div class="field @error('tipo_cliente') is-invalid @enderror">
            <input type="text" name="tipo_cliente" id="f-tipo" value="{{ $v('tipo_cliente') }}" placeholder=" ">
            <label for="f-tipo">Tipo de cliente (gobierno/empresa)</label>
          </div>
          @error('tipo_cliente')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <div class="field @error('rfc') is-invalid @enderror">
            <input type="text" name="rfc" id="f-rfc" value="{{ $v('rfc') }}" placeholder=" ">
            <label for="f-rfc">RFC / Número fiscal</label>
          </div>
          @error('rfc')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Fila 3: contacto / teléfono --}}
      <div class="grid">
        <div>
          <div class="field @error('contacto') is-invalid @enderror">
            <input type="text" name="contacto" id="f-contacto" value="{{ $v('contacto') }}" placeholder=" ">
            <label for="f-contacto">Persona de contacto</label>
          </div>
          @error('contacto')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <div class="field @error('telefono') is-invalid @enderror">
            <input type="text" name="telefono" id="f-telefono" value="{{ $v('telefono') }}" placeholder=" ">
            <label for="f-telefono">Teléfono</label>
          </div>
          @error('telefono')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Fila 4: calle / colonia --}}
      <div class="grid">
        <div>
          <div class="field @error('calle') is-invalid @enderror">
            <input type="text" name="calle" id="f-calle" value="{{ $v('calle') }}" placeholder=" ">
            <label for="f-calle">Calle</label>
          </div>
          @error('calle')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <div class="field @error('colonia') is-invalid @enderror">
            <input type="text" name="colonia" id="f-colonia" value="{{ $v('colonia') }}" placeholder=" ">
            <label for="f-colonia">Colonia</label>
          </div>
          @error('colonia')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Fila 5: ciudad / estado --}}
      <div class="grid">
        <div>
          <div class="field @error('ciudad') is-invalid @enderror">
            <input type="text" name="ciudad" id="f-ciudad" value="{{ $v('ciudad') }}" placeholder=" ">
            <label for="f-ciudad">Ciudad</label>
          </div>
          @error('ciudad')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
          <div class="field @error('estado') is-invalid @enderror">
            <input type="text" name="estado" id="f-estado" value="{{ $v('estado') }}" placeholder=" ">
            <label for="f-estado">Estado</label>
          </div>
          @error('estado')<div class="error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Fila 6: CP / Estatus --}}
      <div class="grid">
        <div>
          <div class="field @error('cp') is-invalid @enderror">
            <input type="text" name="cp" id="f-cp" value="{{ $v('cp') }}" placeholder=" ">
            <label for="f-cp">Código postal</label>
          </div>
          @error('cp')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="switch-wrap">
          <div class="switch-legend">
            <span class="dot {{ $v('estatus', $isEdit ? (int)$client->estatus : 1) ? 'dot--on' : '' }}"></span>
            <span>Estatus</span>
            <small style="color:var(--muted)">
              {{ $v('estatus', $isEdit ? (int)$client->estatus : 1) ? 'Activo' : 'Inactivo' }}
            </small>
          </div>
          <label class="switch">
            <input type="checkbox" name="estatus" value="1"
                   {{ $v('estatus', $isEdit ? (int)$client->estatus : 1) ? 'checked' : '' }}
                   onchange="this.nextElementSibling.parentElement.previousElementSibling.querySelector('small').textContent = this.checked ? 'Activo' : 'Inactivo';
                            this.checked ? this.parentElement.parentElement.querySelector('.dot').classList.add('dot--on')
                                         : this.parentElement.parentElement.querySelector('.dot').classList.remove('dot--on');">
            <span class="track"><span class="thumb"></span></span>
          </label>
        </div>
      </div>

      <div class="actions">
        <a href="{{ route('clients.index') }}" class="btn btn-ghost">Cancelar</a>
        <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
      </div>
    </form>
  </div>
</div>
@endsection

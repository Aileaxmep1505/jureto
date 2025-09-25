@extends('layouts.app')
@section('title','Mi perfil')
@section('titulo','Mi perfil')

@push('styles')
<link href="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">

<style>
/* ================= ID CARD (aislado) ================= */
:root{
  /* Paleta local */
  --idc-bg:#f6f7fb;
  --idc-surface:#ffffff;
  --idc-ink:#0f172a;
  --idc-muted:#667085;
  --idc-line:#e6e8ef;
  --idc-brand:#7ea2ff;
  --idc-ok:#16a34a;
  --idc-danger:#ef4444;

  --idc-radius:22px;
  --idc-shadow:0 28px 70px rgba(18,38,63,.12);
}

.idc-wrap{max-width:1100px;margin:84px auto 32px;padding:0 18px}
.idc-grid{display:grid;grid-template-columns:440px 1fr;gap:22px}
@media (max-width:1024px){ .idc-grid{grid-template-columns:1fr} }

/* Credencial */
.idc-card{
  position:relative;background:var(--idc-surface);
  border:1px solid var(--idc-line);border-radius:var(--idc-radius);
  box-shadow:var(--idc-shadow);overflow:hidden;
}
.idc-card__header{
  position:relative;height:128px;
  background: radial-gradient(120% 120% at 0% 0%, #e7efff 0%, transparent 50%),
              radial-gradient(120% 120% at 100% 0%, #f4e8ff 0%, transparent 55%),
              linear-gradient(180deg, #ffffff, #f7f8ff);
  border-bottom:1px solid var(--idc-line);
}
.idc-card__logo{
  position:absolute;left:16px;top:16px;display:flex;align-items:center;gap:10px
}
.idc-card__logo img{height:28px;width:auto;display:block}
.idc-card__logo span{font-weight:800;color:#1e293b;letter-spacing:.2px}

.idc-avatar-shell{
  position:absolute;left:50%;bottom:-58px;transform:translateX(-50%);
  width:116px;height:116px;border-radius:50%;
  background:#fff;border:1px solid var(--idc-line);
  box-shadow:0 8px 40px rgba(15,23,42,.18), inset 0 0 0 8px rgba(126,162,255,.10);
  display:grid;place-items:center;overflow:hidden;
}
.idc-avatar{
  width:100%;height:100%;border-radius:50%;object-fit:cover;display:block;
}

.idc-card__body{padding:80px 20px 20px}
.idc-row{display:flex;gap:14px}
.idc-col{flex:1}
.idc-label{font-size:12px;color:var(--idc-muted);margin:0 0 6px 2px;display:block}
.idc-value{
  background:#fff;border:1px solid var(--idc-line);border-radius:12px;padding:12px 14px;
  color:#0f172a
}
.idc-hint{font-size:12px;color:var(--idc-muted);margin-top:6px}

.idc-actions{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:14px}
.idc-btn{
  appearance:none;border:1px solid var(--idc-line);background:#fff;color:#0b1220;
  border-radius:999px;padding:10px 16px;font-weight:700;cursor:pointer;
  box-shadow:0 10px 22px rgba(13,38,76,.06);transition:transform .06s, box-shadow .2s;
}
.idc-btn:hover{transform:translateY(-1px)}
.idc-btn--brand{background:var(--idc-brand);color:#0b1220}
.idc-btn--ghost{background:#fff}
.idc-btn--danger{background:#fee2e2;border-color:#fecaca;color:#7f1d1d}

/* Panel derecho */
.idc-panel{
  background:#fff;border:1px solid var(--idc-line);border-radius:var(--idc-radius);
  box-shadow:var(--idc-shadow);overflow:hidden
}
.idc-panel__head{padding:16px 18px;border-bottom:1px solid var(--idc-line);font-weight:800}
.idc-panel__body{padding:18px}

/* Inputs */
.idc-input{
  width:100%;background:#fff;border:1px solid var(--idc-line);border-radius:12px;
  padding:12px 14px;font:inherit;transition:border-color .2s, box-shadow .2s;
}
.idc-input:focus{outline:none;border-color:#cbd5ff;box-shadow:0 0 0 4px rgba(137,168,255,.25)}
.idc-input[disabled]{background:#f8fafc;color:#6b7280;cursor:not-allowed}

/* Mensajes */
.idc-alert{border-radius:14px;padding:10px 12px;margin:12px 0;border:1px solid var(--idc-line);background:#f9fafb}
.idc-alert--ok{border-color:#c7f0d9;background:#ecfdf5;color:#065f46}
.idc-alert--err{border-color:#fecaca;background:#fef2f2;color:#991b1b}

/* Input file */
.idc-file{display:block;width:100%}
.idc-file::-webkit-file-upload-button{visibility:hidden;width:0;border:0;padding:0;margin:0}
.idc-file::before{
  content:'Seleccionar imagen';display:inline-block;background:#ffffff;border:1px dashed #cbd5e1;color:#334155;
  border-radius:12px;padding:10px 14px;margin-right:10px;cursor:pointer;font-weight:700
}
.idc-file:hover::before{border-color:#94a3b8}

/* Modal recorte circular */
.idc-cropper__backdrop{
  position:fixed;inset:0;background:rgba(15,23,42,.55);
  display:none;align-items:center;justify-content:center;z-index:1000;
}
.idc-cropper{
  width:min(92vw,880px);background:#fff;border-radius:20px;border:1px solid var(--idc-line);
  box-shadow:0 28px 80px rgba(16,24,40,.35);overflow:hidden;
}
.idc-cropper__head{padding:14px 18px;border-bottom:1px solid var(--idc-line);font-weight:800}
.idc-cropper__body{padding:14px;display:grid;gap:12px}
.idc-cropper__actions{display:flex;justify-content:flex-end;gap:10px;border-top:1px solid var(--idc-line);padding:12px 16px}
#idc-cropper__stage{position:relative;max-height:64vh;overflow:auto;border-radius:14px;background:#0b122006}
#idc-cropper__img{max-width:100%;display:block}

/* Anillo de máscara circular (solo visual) */
.idc-mask-ring:after{
  content:"";pointer-events:none;position:absolute;inset:0;
  background:
   radial-gradient(circle at 50% 50%, rgba(0,0,0,0) 34%, rgba(0,0,0,.45) 36%, rgba(0,0,0,.45) 100%);
  mix-blend-mode:multiply;
}
</style>
@endpush

@section('content')
<div class="idc-wrap">

  @if (session('ok'))
    <div class="idc-alert idc-alert--ok">{{ session('ok') }}</div>
  @endif

  @if ($errors->any())
    <div class="idc-alert idc-alert--err">
      <strong>Revisa los campos:</strong>
      <ul style="margin:6px 0 0 18px">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="idc-grid">

    {{-- ========== CREDENCIAL ==========
         Logo arriba, avatar circular, datos de nombre/email/id
    --}}
    <div class="idc-card">
      <div class="idc-card__header">
        <div class="idc-card__logo">
          <img src="{{ asset('images/logo-credencial.svg') }}"
               alt="Logo" onerror="this.style.display='none'">
          <span>Identificación</span>
        </div>
        <div class="idc-avatar-shell">
          <img id="avatarPreview" class="idc-avatar" alt="Avatar"
               src="{{ $user->avatar_url }}"
               onerror="this.onerror=null; this.src='https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email ?? ''))) }}?s=300&d=mp';">
        </div>
      </div>

      <div class="idc-card__body">
        <div class="idc-row" style="margin-bottom:10px">
          <div class="idc-col">
            <label class="idc-label">Nombre</label>
            <div class="idc-value">{{ $user->name }}</div>
          </div>
        </div>
        <div class="idc-row" style="margin-bottom:10px">
          <div class="idc-col">
            <label class="idc-label">Email</label>
            <div class="idc-value">{{ $user->email }}</div>
          </div>
        </div>

        <form id="photoForm" action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" style="margin-top:12px">
          @csrf
          @method('PUT')

          <label class="idc-label" for="photo">Actualizar foto (JPG, PNG o WebP · máx 3MB)</label>
          <input class="idc-input idc-file" type="file" id="photo" name="photo" accept="image/*">
          <input type="hidden" name="avatar_cropped" id="avatar_cropped">

          <div class="idc-actions">
            <button class="idc-btn idc-btn--ghost" id="btnOpenCrop" type="button">Recortar en círculo</button>
            <button class="idc-btn idc-btn--brand" type="submit">Guardar foto</button>
          </div>
          <p class="idc-hint">El recorte se guardará **circular** con fondo transparente.</p>
        </form>
      </div>
    </div>

    {{-- Panel de seguridad / contraseña --}}
    <div class="idc-panel">
      <div class="idc-panel__head">Seguridad de la cuenta</div>
      <div class="idc-panel__body">
        <form action="{{ route('profile.update.password') }}" method="POST" autocomplete="off">
          @csrf
          @method('PUT')

          <div class="idc-row" style="gap:16px;margin-bottom:10px">
            <div class="idc-col">
              <label class="idc-label" for="current_password">Contraseña actual</label>
              <input class="idc-input" type="password" id="current_password" name="current_password" required>
            </div>
            <div class="idc-col">
              <label class="idc-label" for="password">Nueva contraseña</label>
              <input class="idc-input" type="password" id="password" name="password" required>
              <div class="idc-hint">Usa al menos 8 caracteres. Evita contraseñas comunes.</div>
            </div>
            <div class="idc-col">
              <label class="idc-label" for="password_confirmation">Confirmar nueva</label>
              <input class="idc-input" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
          </div>

          <div class="idc-actions" style="justify-content:flex-end">
            <button type="submit" class="idc-btn idc-btn--brand">Actualizar contraseña</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

{{-- ========== Modal de recorte circular real ==========
     El recorte se exporta con CLIPPING circular a PNG (transparente),
     así el archivo es redondo en todos lados.
--}}
<div class="idc-cropper__backdrop" id="cropperBackdrop" aria-hidden="true">
  <div class="idc-cropper" role="dialog" aria-modal="true" aria-labelledby="cropperTitle">
    <div class="idc-cropper__head" id="cropperTitle">Ajusta tu foto en círculo</div>
    <div class="idc-cropper__body">
      <div id="idc-cropper__stage" class="idc-mask-ring">
        <img id="idc-cropper__img" alt="Recorte">
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <button type="button" class="idc-btn idc-btn--ghost" id="btnZoomIn">+ Zoom</button>
        <button type="button" class="idc-btn idc-btn--ghost" id="btnZoomOut">- Zoom</button>
        <button type="button" class="idc-btn idc-btn--ghost" id="btnRotate">Rotar 90°</button>
        <button type="button" class="idc-btn idc-btn--ghost" id="btnReset">Reiniciar</button>
      </div>
    </div>
    <div class="idc-cropper__actions">
      <button type="button" class="idc-btn idc-btn--ghost" id="btnCloseCrop">Cancelar</button>
      <button type="button" class="idc-btn idc-btn--brand" id="btnApplyCrop">Aplicar recorte</button>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
(function(){
  const inputFile   = document.getElementById('photo');
  const preview     = document.getElementById('avatarPreview');
  const hiddenData  = document.getElementById('avatar_cropped');
  const openBtn     = document.getElementById('btnOpenCrop');
  const backdrop    = document.getElementById('cropperBackdrop');
  const cropImg     = document.getElementById('idc-cropper__img');
  const btnClose    = document.getElementById('btnCloseCrop');
  const btnApply    = document.getElementById('btnApplyCrop');
  const btnZoomIn   = document.getElementById('btnZoomIn');
  const btnZoomOut  = document.getElementById('btnZoomOut');
  const btnRotate   = document.getElementById('btnRotate');
  const btnReset    = document.getElementById('btnReset');
  let cropper = null;

  function openModalWithFile(file){
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
      cropImg.src = ev.target.result;
      backdrop.style.display = 'flex';
      if (cropper) { cropper.destroy(); cropper = null; }
      cropper = new Cropper(cropImg, {
        aspectRatio: 1,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 1,
        background: false,
        movable: true, zoomable: true, rotatable: true, responsive: true,
        checkCrossOrigin: false,
      });
    };
    reader.readAsDataURL(file);
  }

  openBtn?.addEventListener('click', () => {
    const file = inputFile?.files?.[0];
    if (!file) inputFile?.click(); else openModalWithFile(file);
  });

  inputFile?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (file) openModalWithFile(file);
  });

  btnZoomIn?.addEventListener('click', () => cropper?.zoom(0.1));
  btnZoomOut?.addEventListener('click', () => cropper?.zoom(-0.1));
  btnRotate?.addEventListener('click', () => cropper?.rotate(90));
  btnReset?.addEventListener('click', () => cropper?.reset());

  function closeModal(){
    backdrop.style.display = 'none';
    if (cropper) { cropper.destroy(); cropper = null; }
  }
  btnClose?.addEventListener('click', closeModal);
  backdrop?.addEventListener('click', (e) => { if (e.target === backdrop) closeModal(); });

  // Exporta CÍRCULO real con transparencia (PNG)
  function exportCirclePNGFromSquareCanvas(sqCanvas, size=1024){
    const c = document.createElement('canvas');
    c.width = c.height = size;
    const ctx = c.getContext('2d');
    ctx.clearRect(0,0,size,size);
    ctx.save();
    ctx.beginPath();
    ctx.arc(size/2, size/2, size/2, 0, Math.PI*2, false);
    ctx.closePath();
    ctx.clip();
    // Dibuja el cuadrado recortado dentro del círculo
    ctx.drawImage(sqCanvas, 0, 0, size, size);
    ctx.restore();
    // PNG soporta transparencia
    return c.toDataURL('image/png'); 
  }

  btnApply?.addEventListener('click', () => {
    if (!cropper) return;

    // 1) obtenemos el recorte cuadrado de alta calidad
    const squareCanvas = cropper.getCroppedCanvas({
      width: 1024, height: 1024,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high'
    });

    // 2) lo convertimos a círculo real (PNG transparente)
    const circleDataURL = exportCirclePNGFromSquareCanvas(squareCanvas, 1024);

    // 3) guardamos el dataURL en el hidden para el backend
    hiddenData.value = circleDataURL;

    // 4) previsualizamos en el avatar de la credencial
    preview.src = circleDataURL;

    // listo
    closeModal();
  });
})();
</script>
@endpush
@endsection

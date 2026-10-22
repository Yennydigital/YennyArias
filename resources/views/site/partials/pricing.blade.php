<section id="planes" class="pr-sec">
<style>
.pr-sec{position:relative;padding:110px 16px 120px;color:#e5e7eb;
    background:radial-gradient(900px 400px at 50% 0%,rgba(255,64,160,.18),transparent 70%),
               radial-gradient(700px 400px at 90% 100%,rgba(34,211,238,.12),transparent 70%),#05051e}
.pr-top{text-align:center;max-width:720px;margin:0 auto 46px}
.pr-pill{display:inline-block;padding:5px 16px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:.12em;
    text-transform:uppercase;color:#ff40a0;border:1px solid rgba(255,64,160,.45);background:rgba(255,64,160,.08)}
.pr-top h2{margin:18px 0 10px;font-size:clamp(2rem,5vw,3.2rem);font-weight:800;color:#fff;
    text-shadow:0 0 28px rgba(255,64,160,.45)}
.pr-top p{margin:0;color:#94a3b8;font-size:1.05rem}
.pr-sec .pl-head{margin:0 auto 34px}
.pr-sec .pl-card{padding:32px 28px;border-radius:22px}
.pr-sec .pl-grid .pl-card:last-child{border-color:rgba(255,64,160,.6);
    box-shadow:0 0 50px rgba(255,64,160,.22);transform:translateY(-6px)}
.pr-sec .pl-grid .pl-card:last-child::before{content:"RECOMENDADO";position:absolute;top:-13px;left:50%;
    transform:translateX(-50%);padding:4px 14px;border-radius:999px;font-size:11px;font-weight:800;
    letter-spacing:.08em;background:#ff40a0;color:#fff}
.pr-sec .pl-cta{padding:14px 18px;font-size:15px}
.pr-login{margin-top:26px;text-align:center;color:#94a3b8;font-size:14px}
.pr-login a{color:#22d3ee;text-decoration:none}
</style>
    <div class="pr-top">
        <span class="pr-pill">Planes</span>
        <h2>Empieza gratis, crece cuando quieras</h2>
        <p>Sin tarjeta para empezar. Para contratar Plus necesitas una cuenta.</p>
    </div>
    @include('plans._cards', ['mode' => 'public'])
    <p class="pr-login">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</section>

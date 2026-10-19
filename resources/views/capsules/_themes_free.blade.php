<style>
/* ===== LOVE LETTER: rosa, serif, corazones ===== */
body:has(.tpl-love-letter){background:linear-gradient(rgba(40,5,25,.72),rgba(15,2,15,.9)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat fixed}
.tpl-love-letter{border:1px solid rgba(251,113,133,.5)!important;background:rgba(40,8,28,.72)!important;
  box-shadow:0 0 60px rgba(251,113,133,.25),0 24px 60px rgba(0,0,0,.55)!important}
.tpl-love-letter::before{content:"\2665";display:block;text-align:center;font-size:30px;color:#fb7185;
  text-shadow:0 0 20px rgba(251,113,133,.8);margin-bottom:8px;animation:float 3s ease-in-out infinite}
.tpl-love-letter .to{font-family:Georgia,serif;font-style:italic;font-size:20px;color:#fda4af!important}
.tpl-love-letter .msg{font-family:Georgia,serif;font-size:18px;line-height:1.9;color:#ffe4e6}
.tpl-love-letter .from{font-family:Georgia,serif;color:#fda4af!important}
.tpl-love-letter .btn{background:#fb7185;color:#2a0510}
.tpl-love-letter .btn:hover{background:#fda4af;box-shadow:0 0 22px rgba(251,113,133,.5)}

/* ===== BIRTHDAY: confeti, colores vivos ===== */
body:has(.tpl-birthday){background:
  radial-gradient(circle at 15% 20%,rgba(250,204,21,.9) 0 3px,transparent 4px),
  radial-gradient(circle at 80% 15%,rgba(244,114,182,.9) 0 3px,transparent 4px),
  radial-gradient(circle at 60% 70%,rgba(34,211,238,.9) 0 3px,transparent 4px),
  radial-gradient(circle at 25% 80%,rgba(163,230,53,.9) 0 3px,transparent 4px),
  linear-gradient(160deg,#2a0a4a,#0a0a2a) fixed;
  background-size:180px 180px,220px 220px,160px 160px,200px 200px,100% 100%;animation:confetti 18s linear infinite}
.tpl-birthday{border:2px solid #facc15!important;background:rgba(30,10,60,.78)!important;
  box-shadow:0 0 50px rgba(250,204,21,.3),0 24px 60px rgba(0,0,0,.55)!important}
.tpl-birthday::before{content:"\1F382  \00A1Feliz cumplea\00F1os!  \1F389";display:block;text-align:center;
  font-size:20px;font-weight:800;color:#facc15;margin-bottom:14px;letter-spacing:.03em}
.tpl-birthday .to{font-size:22px;font-weight:800;color:#f472b6!important}
.tpl-birthday .msg{font-size:17px;line-height:1.75;color:#fff}
.tpl-birthday .from{color:#facc15!important;font-weight:700;font-style:normal}
.tpl-birthday .btn{background:#facc15;color:#2a1a00}
.tpl-birthday .btn:hover{background:#fde047;box-shadow:0 0 22px rgba(250,204,21,.55)}
@keyframes confetti{to{background-position:60px 180px,-80px 220px,90px 160px,-50px 200px,0 0}}

/* ===== THANK YOU: verde calmo, dorado, tipo papel ===== */
body:has(.tpl-thank-you){background:linear-gradient(rgba(2,25,22,.78),rgba(2,10,12,.92)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat fixed}
.tpl-thank-you{border:1px solid rgba(110,231,183,.45)!important;border-top:4px solid #fcd34d!important;
  background:rgba(6,30,28,.78)!important;box-shadow:0 0 50px rgba(52,211,153,.18),0 24px 60px rgba(0,0,0,.55)!important}
.tpl-thank-you::before{content:"Gracias";display:block;text-align:center;font-family:Georgia,serif;font-size:28px;
  color:#fcd34d;letter-spacing:.12em;margin-bottom:12px}
.tpl-thank-you .to{color:#6ee7b7!important}
.tpl-thank-you .msg{font-size:17px;line-height:1.8;color:#ecfdf5}
.tpl-thank-you .from{color:#fcd34d!important}
.tpl-thank-you .btn{background:#34d399;color:#03231b}
.tpl-thank-you .btn:hover{background:#6ee7b7;box-shadow:0 0 22px rgba(52,211,153,.5)}

/* ===== TITULO ===== */
.tpl-love-letter .ttl{font-family:Georgia,serif;font-style:italic;font-size:26px;font-weight:400;text-align:center;color:#fda4af;margin:0 0 18px}
.tpl-birthday .ttl{font-size:24px;font-weight:800;text-align:center;color:#f472b6;margin:0 0 16px}
.tpl-thank-you .ttl{font-family:Georgia,serif;font-size:22px;font-weight:400;text-align:center;color:#6ee7b7;margin:0 0 16px}
.letter .ttl{margin-top:0}
</style>

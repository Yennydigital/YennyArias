<style>
/* ===== STARRY NIGHT ===== */
body:has(.tpl-starry-night){background:radial-gradient(ellipse at 50% 0%,#1b1250 0%,#0a0a2a 55%,#03030f 100%) fixed}
body:has(.tpl-starry-night) .stars{opacity:1;animation:drift 120s linear infinite,twinkle 4s ease-in-out infinite}
.tpl-starry-night{border:1px solid rgba(196,181,253,.45)!important;background:rgba(14,10,50,.7)!important;
  box-shadow:0 0 70px rgba(129,140,248,.28),0 0 140px rgba(34,211,238,.1),0 24px 60px rgba(0,0,0,.6)!important}
.tpl-starry-night .to{font-family:Georgia,'Times New Roman',serif;font-size:20px;color:#c4b5fd!important}
.tpl-starry-night .msg{font-family:Georgia,'Times New Roman',serif;font-size:18px;line-height:1.9;color:#eef2ff;text-shadow:0 0 18px rgba(165,180,252,.35)}
.tpl-starry-night .from{font-family:Georgia,serif;color:#a5b4fc!important}
.tpl-starry-night img{border-radius:12px;box-shadow:0 0 24px rgba(129,140,248,.45)}
.tpl-starry-night::before{content:"\263E";display:block;text-align:center;font-size:34px;color:#fde68a;
  text-shadow:0 0 22px rgba(253,230,138,.8);margin-bottom:10px;animation:float 5s ease-in-out infinite}
@keyframes twinkle{0%,100%{opacity:.6}50%{opacity:1}}

/* ===== PIXEL CITY ===== */
body:has(.tpl-pixel-city){background:linear-gradient(rgba(20,0,40,.62),rgba(2,2,20,.82)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat fixed}
body:has(.tpl-pixel-city)::after{content:"";position:fixed;inset:0;pointer-events:none;z-index:5;
  background:repeating-linear-gradient(0deg,rgba(0,0,0,.18) 0,rgba(0,0,0,.18) 1px,transparent 1px,transparent 3px)}
.tpl-pixel-city{font-family:ui-monospace,Menlo,Consolas,monospace;border-radius:4px!important;
  border:2px solid #f0abfc!important;background:rgba(8,0,24,.82)!important;
  box-shadow:6px 6px 0 #22d3ee,0 0 40px rgba(240,171,252,.35)!important}
.tpl-pixel-city::before{content:"> umbra.exe --open letter";display:block;margin-bottom:14px;padding-bottom:10px;
  border-bottom:1px dashed rgba(34,211,238,.5);color:#22d3ee;font-size:12px;letter-spacing:.08em}
.tpl-pixel-city .to{color:#f0abfc!important;text-transform:uppercase;letter-spacing:.1em}
.tpl-pixel-city .to::before{content:"> ";color:#22d3ee}
.tpl-pixel-city .msg{font-size:15px;line-height:1.8;color:#e0f2fe;text-shadow:0 0 8px rgba(34,211,238,.45)}
.tpl-pixel-city .from{font-style:normal;color:#f0abfc!important}
.tpl-pixel-city .from::after{content:"\2588";margin-left:6px;color:#22d3ee;animation:blink 1s steps(1) infinite}
.tpl-pixel-city img{image-rendering:pixelated;border:2px solid #22d3ee;border-radius:0;box-shadow:4px 4px 0 #f0abfc}
.tpl-pixel-city .btn{border-radius:0;background:#f0abfc;box-shadow:4px 4px 0 #22d3ee}
@keyframes blink{50%{opacity:0}}

/* ===== TITULO ===== */
.tpl-starry-night .ttl{font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:400;text-align:center;color:#c4b5fd;text-shadow:0 0 20px rgba(165,180,252,.5);margin:0 0 18px}
.tpl-pixel-city .ttl{font-size:18px;text-transform:uppercase;letter-spacing:.1em;color:#f0abfc;margin:0 0 16px}
.tpl-pixel-city .ttl::before{content:"# ";color:#22d3ee}
</style>

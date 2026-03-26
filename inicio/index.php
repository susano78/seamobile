<?php
/*
https://ia.seamobile.es/inicio/index.html
https://ia.seamobile.es/inicio/index.php?idafiliado=9e53ec0e24a28a51399d389198fff5f8
*/

define('RUTA_RELATIVA_REGISTRO','../registro/altaSeaMobile-IA.php');

$idafiliado_md5='';
if(isset($_GET['idafiliado'])){
   $idafiliado_md5=$_GET['idafiliado']; 
}

$enlace_registro='#';
if(hasdato($idafiliado_md5)){
    $enlace_registro=RUTA_RELATIVA_REGISTRO.'?idafiliado='.$idafiliado_md5;
    $enlace_registro_solo_cliente=RUTA_RELATIVA_REGISTRO.'?idcliente='.$idafiliado_md5;
}

?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Feliz-IA | La IA que te ayuda a ser más Feliz</title>
  <meta name="description"
    content="Feliz-IA: IAs especializadas para salud, jurídico, educación, cocina saludable y consultas generales. Úsala como cliente o participa como afiliado." />
  <meta name="robots" content="index,follow" />

  <!-- Open Graph (para compartir) -->
  <meta property="og:title" content="Feliz-IA | La IA que te ayuda a ser más Feliz" />
  <meta property="og:description"
    content="Úsala como cliente o participa como afiliado. Vídeos explicativos y registro en 2 minutos." />
  <meta property="og:type" content="website" />
  <!-- Cambia esto cuando tengas dominio -->
  <meta property="og:url" content="https://feliz-ia.com/inicio" />
  <!-- Opcional: imagen para compartir -->
  <meta property="og:image" content="https://feliz-ia.com/logo_feliz-ia.jpg" />

	<!-- Favicon (icono del navegador) -->
   <link rel="icon" href="/feliz-ia-favicon.ico" type="image/x-icon">
   <link rel="shortcut icon" href="/feliz-ia-favicon.ico" type="image/x-icon">  <style>
    :root {
      --bg: #0b1020;
      --panel: rgba(255, 255, 255, .06);
      --panel2: rgba(255, 255, 255, .10);
      --txt: rgba(255, 255, 255, .92);
      --muted: rgba(255, 255, 255, .72);
      --muted2: rgba(255, 255, 255, .60);
      --line: rgba(255, 255, 255, .12);
      --accent: #39d3ff;
      --accent2: #7cf7c7;
      --danger: #ff6b6b;
      --shadow: 0 20px 60px rgba(0, 0, 0, .45);
      --radius: 18px;
      --radius2: 26px;
      --max: 1120px;
    }

    * {
      box-sizing: border-box
    }

    html {
      scroll-behavior: smooth
    }

    body {
      margin: 0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      color: var(--txt);
      background:
        radial-gradient(1200px 700px at 20% 0%, rgba(57, 211, 255, .22), transparent 60%),
        radial-gradient(900px 600px at 90% 15%, rgba(124, 247, 199, .18), transparent 55%),
        radial-gradient(900px 700px at 50% 90%, rgba(57, 211, 255, .12), transparent 60%),
        var(--bg);
      line-height: 1.5;
    }

    a {
      color: inherit
    }

    .wrap {
      max-width: var(--max);
      margin: 0 auto;
      padding: 0 18px
    }

    .topbar {
      position: sticky;
      top: 0;
      z-index: 50;
      backdrop-filter: blur(14px);
      background: linear-gradient(to bottom, rgba(11, 16, 32, .92), rgba(11, 16, 32, .55));
      border-bottom: 1px solid var(--line);
    }

    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 0;
      gap: 12px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      font-weight: 800;
      letter-spacing: .2px;
    }

    .logo {
      width: auto;
      height: 34px;
      border-radius: 8px;
    }

    .menu {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .menu a {
      text-decoration: none;
      font-size: 14px;
      color: var(--muted);
      padding: 8px 10px;
      border-radius: 12px;
      border: 1px solid transparent;
    }

    .menu a:hover {
      color: var(--txt);
      border-color: var(--line);
      background: rgba(255, 255, 255, .04);
    }

    .cta {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none;
      font-weight: 700;
      border-radius: 14px;
      padding: 12px 14px;
      border: 1px solid rgba(255, 255, 255, .16);
      background: rgba(255, 255, 255, .06);
      box-shadow: 0 12px 40px rgba(0, 0, 0, .25);
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      white-space: nowrap;
    }

    .cta:hover {
      transform: translateY(-1px);
      background: rgba(255, 255, 255, .09);
      border-color: rgba(255, 255, 255, .22)
    }

    .cta.primary {
      background: linear-gradient(135deg, rgba(57, 211, 255, .95), rgba(124, 247, 199, .88));
      color: #07101b;
      border-color: rgba(255, 255, 255, .35);
      box-shadow: 0 18px 60px rgba(57, 211, 255, .25);
    }

    .cta.primary:hover {
      transform: translateY(-1px) scale(1.01)
    }

    .pill {
      display: inline-flex;
      gap: 8px;
      align-items: center;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .12);
      color: var(--muted);
      font-size: 13px;
    }

    .hero {
      padding: 46px 0 22px;
    }

    .grid-hero {
      display: grid;
      grid-template-columns: 1.15fr .85fr;
      gap: 20px;
      align-items: stretch;
    }

    @media (max-width: 920px) {
      .grid-hero {
        grid-template-columns: 1fr;
      }

      .menu {
        display: none
      }
    }

    h1 {
      margin: 12px 0 8px;
      font-size: clamp(34px, 4vw, 52px);
      line-height: 1.06;
      letter-spacing: -.8px;
    }

    .subtitle {
      margin: 0 0 18px;
      font-size: 18px;
      color: var(--muted);
      max-width: 62ch;
    }

    .hero-card {
      border-radius: var(--radius2);
      background: linear-gradient(180deg, rgba(255, 255, 255, .08), rgba(255, 255, 255, .04));
      border: 1px solid rgba(255, 255, 255, .14);
      box-shadow: var(--shadow);
      padding: 18px;
      overflow: hidden;
      position: relative;
    }

    .hero-card:before {
      content: "";
      position: absolute;
      inset: -2px;
      background:
        radial-gradient(600px 320px at 15% 20%, rgba(57, 211, 255, .20), transparent 55%),
        radial-gradient(600px 320px at 85% 35%, rgba(124, 247, 199, .14), transparent 58%);
      pointer-events: none;
    }

    .hero-card>* {
      position: relative
    }

    .two-ways {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-top: 14px;
    }

    @media (max-width: 560px) {
      .two-ways {
        grid-template-columns: 1fr
      }
    }

    .way {
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, .14);
      background: rgba(255, 255, 255, .05);
      padding: 14px;
    }

    .way h3 {
      margin: 0 0 6px;
      font-size: 16px
    }

    .way p {
      margin: 0;
      color: var(--muted);
      font-size: 14px
    }

    .kpis {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 12px;
    }

    .kpis span {
      font-size: 13px;
      color: var(--muted);
      border: 1px solid rgba(255, 255, 255, .12);
      background: rgba(255, 255, 255, .04);
      padding: 8px 10px;
      border-radius: 999px;
    }

    section {
      padding: 26px 0
    }

    .section-title {
      font-size: 26px;
      margin: 0 0 6px;
      letter-spacing: -.4px;
    }

    .section-desc {
      margin: 0 0 16px;
      color: var(--muted);
      max-width: 80ch;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 12px;
    }

    .card {
      grid-column: span 6;
      border-radius: var(--radius);
      border: 1px solid rgba(255, 255, 255, .12);
      background: rgba(255, 255, 255, .05);
      padding: 16px;
    }

    .card.small {
      grid-column: span 4
    }

    .card.full {
      grid-column: span 12
    }

    @media (max-width: 920px) {

      .card,
      .card.small {
        grid-column: span 12
      }
    }

    .card h3 {
      margin: 0 0 8px;
      font-size: 18px
    }

    .card p {
      margin: 0;
      color: var(--muted)
    }

    .list {
      margin: 10px 0 0;
      padding: 0;
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .list li {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      color: var(--muted);
      padding: 10px 10px;
      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, .10);
      background: rgba(255, 255, 255, .03);
    }

    .dot {
      width: 10px;
      height: 10px;
      border-radius: 999px;
      margin-top: 5px;
      background: linear-gradient(135deg, rgba(57, 211, 255, .95), rgba(124, 247, 199, .85));
      flex: 0 0 auto;
    }

    .split {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    @media (max-width: 920px) {
      .split {
        grid-template-columns: 1fr
      }
    }

    .video-grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 12px;
    }

    .video {
      grid-column: span 6;
      border-radius: var(--radius);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, .12);
      background: rgba(255, 255, 255, .04);
      box-shadow: 0 16px 50px rgba(0, 0, 0, .25);
    }

    .video iframe {
      width: 100%;
      aspect-ratio: 16/9;
      border: 0;
      display: block;
    }

    @media (max-width: 920px) {
      .video {
        grid-column: span 12;
      }
    }

    .video .cap {
      padding: 12px 14px;
      color: var(--muted);
      font-size: 14px;
      border-top: 1px solid rgba(255, 255, 255, .10);
    }

    .cta-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 12px;
    }

    .notice {
      margin-top: 12px;
      color: var(--muted2);
      font-size: 13px;
    }

    .faq details {
      border: 1px solid rgba(255, 255, 255, .12);
      background: rgba(255, 255, 255, .04);
      border-radius: 16px;
      padding: 12px 14px;
    }

    .faq summary {
      cursor: pointer;
      font-weight: 700;
      color: var(--txt);
    }

    .faq p {
      color: var(--muted);
      margin: 8px 0 0
    }

    footer {
      padding: 26px 0 38px;
      border-top: 1px solid var(--line);
      color: var(--muted2);
      font-size: 13px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.2fr .8fr;
      gap: 12px;
      align-items: start;
    }

    @media (max-width: 920px) {
      .footer-grid {
        grid-template-columns: 1fr;
      }
    }

    .muted {
      color: var(--muted)
    }

    .tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 10px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .04);
      border: 1px solid rgba(255, 255, 255, .10);
      color: var(--muted);
      font-size: 13px;
    }

    /* Botón flotante móvil */
    .fab {
      position: fixed;
      right: 16px;
      bottom: 16px;
      z-index: 80;
      display: none;
    }

    @media (max-width: 920px) {
      .fab {
        display: block
      }
    }
  </style>
</head>

<body>

  <!-- Topbar -->
  <div class="topbar">
    <div class="wrap">
      <div class="nav">
        <a class="brand" href="#inicio" aria-label="Ir al inicio">
          <img src="https://feliz-ia.com/logo_feliz-ia.jpg" alt="Logo Feliz-IA" class="logo">
          <span>Feliz-IA</span>
        </a>

        <div class="menu" aria-label="Navegación">
          <a href="#que-es">Qué es Feliz-IA</a>
          <a href="#clientes">Clientes</a>
          <a href="#afiliados">Afiliados</a>
          <a href="#videos">Vídeos</a>
          <a href="#faq">FAQ</a>
          <a class="cta" href="#registro">Registro</a>
        </div>

        <a class="cta primary"
          href="<?=$enlace_registro;?>">Empezar
          ahora</a>
      </div>
    </div>
  </div>

  <!-- Hero -->
  <header id="inicio" class="hero">
    <div class="wrap">
      <div class="grid-hero">
        <div>
          <span class="pill">✨ La IA (inteligencia artificial) que te ayuda a ser más Feliz</span>
          <h1>Resuelve dudas, toma mejores decisiones y ahorra tiempo con IAs especializadas.</h1>
          <p class="subtitle">
            Feliz-IA integra IAs temáticas para <strong>salud</strong>, <strong>jurídico</strong>,
            <strong>educación</strong>, <strong>cocina saludable</strong> y <strong>consultas generales</strong>. Por tan solo 11€/mes.
            Entra como cliente o participa como afiliado.
          </p>

          <div class="cta-row">
            <!-- CAMBIA ESTOS ENLACES -->
            <a class="cta primary"
              href="<?=$enlace_registro_solo_cliente;?>">Quiero
              usar Feliz-IA</a>
            <a class="cta"
              href="<?=$enlace_registro;?>">Quiero
              ser afiliado</a>
          </div>

          <div class="kpis" role="list">
            <span role="listitem">✅ Fácil de usar</span>
            <span role="listitem">✅ Respuestas guiadas</span>
            <span role="listitem">🔒 Privacidad y RGPD</span>
            <span role="listitem">⚠️ Sin ingresos garantizados</span>
          </div>

          <p class="notice">
            Nota: Feliz-IA no sustituye a un profesional. Para decisiones importantes, consulta a tu
            médico/abogado/profesional.
          </p>
        </div>

        <div class="hero-card" aria-label="Tarjeta resumen de valor">
          <h2 style="margin:0 0 10px; font-size:20px; letter-spacing:-.3px;">Elige tu camino (sin líos)</h2>

          <div class="two-ways">
            <div class="way">
              <h3>🧠 cliente</h3>
              <p>Entra, pregunta y recibe respuestas claras con pasos. Ideal si quieres resultados ya.</p>
            </div>
            <div class="way">
              <h3>🤝 Afiliado </h3>
              <p>Usa la herramienta y recomiéndala con un modelo transparente de comisiones y bonos.</p>
            </div>
          </div>

          <div style="margin-top:12px; border-top:1px solid rgba(255,255,255,.12); padding-top:12px;">
            <div class="tag">🎥 4 vídeos explicativos incluidos</div>
            <div class="tag">🚀 Registro en 2 minutos</div>
          </div>

          <div class="cta-row" style="margin-top:14px;">
            <a class="cta primary" href="#videos">Ver vídeos</a>
            <a class="cta" href="#que-es">Leer más</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Qué es -->
  <section id="que-es">
    <div class="wrap">
      <h2 class="section-title">Qué es Feliz-IA (en 20 segundos)</h2>
      <p class="section-desc">
        Feliz-IA es una plataforma que integra varias <strong>IAs (inteligencias artificiales) especializadas</strong>
        para ayudarte en tu día a día y,
        si quieres, participar como afiliado recomendando la herramienta con reglas claras.
      </p>

      <div class="cards">
        <div class="card small">
          <h3>1) Entras</h3>
          <p>Creas tu cuenta y eliges la temática que necesitas.</p>
        </div>
        <div class="card small">
          <h3>2) Preguntas</h3>
          <p>Hablas normal. Sin tecnicismos. Como se lo explicarías a alguien.</p>
        </div>
        <div class="card small">
          <h3>3) Actúas</h3>
          <p>Recibes pasos claros, opciones y recomendaciones prácticas.</p>
        </div>

        <div class="card full">
          <h3>Dos formas de participar</h3>
          <ul class="list">
            <li><span class="dot"></span><span><strong>cliente:</strong> usa Feliz-IA para resolver dudas y avanzar más
                rápido.</span></li>
            <li><span class="dot"></span><span><strong>Afiliado:</strong> recomienda Feliz-IA con materiales oficiales y
                reglas transparentes.</span></li>
          </ul>

          <div class="cta-row">
            <a class="cta primary"
              href="<?=$enlace_registro_solo_cliente;?>">Crear
              cuenta de cliente</a>
            <a class="cta"
              href="<?=$enlace_registro;?>">Crear
              cuenta de afiliado</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- clientes -->
  <section id="clientes">
    <div class="wrap">
      <h2 class="section-title">Para ti si quieres usar Feliz-IA como cliente.</h2>
      <p class="section-desc">
        Si quieres claridad, rapidez y menos estrés mental, aquí tienes lo que más se usa:
      </p>

      <div class="cards">
        <div class="card">
          <h3>🩺 Salud</h3>
          <p class="muted">Orientación informativa, hábitos y preparación de preguntas para tu médico.</p>
          <ul class="list">
            <li><span class="dot"></span><span>Entender síntomas comunes con prudencia y señales de alarma.</span></li>
            <li><span class="dot"></span><span>Preparar preguntas para consulta médica.</span></li>
            <li><span class="dot"></span><span>Plan de hábitos: sueño, ejercicio y nutrición.</span></li>
          </ul>
        </div>

        <div class="card">
          <h3>⚖️ Jurídico</h3>
          <p class="muted">Aclaración de conceptos, pasos y documentos. Para lo serio: revisión profesional.</p>
          <ul class="list">
            <li><span class="dot"></span><span>Entender tus opciones y próximos pasos.</span></li>
            <li><span class="dot"></span><span>Ordenar documentación y tiempos.</span></li>
            <li><span class="dot"></span><span>Borradores de textos para revisar.</span></li>
          </ul>
        </div>

        <div class="card">
          <h3>🎓 Educación</h3>
          <p class="muted">Explicaciones claras, resúmenes y planes de estudio.</p>
          <ul class="list">
            <li><span class="dot"></span><span>“Explícamelo como si tuviera 10 años”.</span></li>
            <li><span class="dot"></span><span>Resúmenes y mapas de aprendizaje.</span></li>
            <li><span class="dot"></span><span>Ejercicios y corrección guiada.</span></li>
          </ul>
        </div>

        <div class="card">
          <h3>🥗 Cocina saludable</h3>
          <p class="muted">Menús, recetas con lo que tienes y lista de compra.</p>
          <ul class="list">
            <li><span class="dot"></span><span>Plan semanal según objetivo.</span></li>
            <li><span class="dot"></span><span>Recetas rápidas y saludables.</span></li>
            <li><span class="dot"></span><span>Batch cooking (cocinar por tandas) + lista de compra.</span></li>
          </ul>
        </div>

        <div class="card full">
          <h3>Micro-promesa (sin humo)</h3>
          <p class="muted">
            Más claridad, menos bloqueo y decisiones mejor informadas. Entra, pregunta, aplica.
          </p>
          <div class="cta-row">
            <a class="cta primary"
              href="<?=$enlace_registro_solo_cliente;?>">Empezar
              como cliente</a>
            <a class="cta" href="#videos">Ver vídeos primero</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vídeos -->
  <section id="videos">
    <div class="wrap">
      <h2 class="section-title">Vídeos (mira esto antes de decidir)</h2>
      <p class="section-desc">
        Cuatro vídeos cortos para entenderlo todo en orden. Solo pega tus enlaces de YouTube/Vimeo (o el embed).
      </p>

      <div class="video-grid">


        <div class="video">
          <div id="player1"></div>
          <div class="cap">1) Cómo funciona Feliz-IA</div>
        </div>
        <div class="video">
          <div id="player2"></div>
          <div class="cap">2) El modelo de negocio de Feliz-IA</div>
        </div>
        <div class="video">
          <div id="player3"></div>
          <div class="cap">3) Por qué lo comercializamos con un marketing multinivel (network marketing)</div>
        </div>
        <div class="video">
          <div id="player4"></div>
          <div class="cap">4) Cómo funciona la matriz única forzada 8×4</div>
        </div>
      </div>

      <div class="cta-row" style="margin-top:14px;">
        <a class="cta primary"
          href="<?=$enlace_registro_solo_cliente;?>">Quiero
          usar Feliz-IA</a>
        <a class="cta"
          href="<?=$enlace_registro;?>">Quiero
          ser afiliado</a>
      </div>

      <p class="notice">
        ⚠️ Transparencia: el plan de afiliados no garantiza ingresos. Dependen del trabajo, constancia y cumplimiento de
        normas.
      </p>
    </div>
  </section>

  <!-- Afiliados -->
  <section id="afiliados">
    <div class="wrap">
      <h2 class="section-title">Para ti si quieres ser afiliado </h2>
      <p class="section-desc">
        Si te gusta la herramienta, puedes recomendarla de forma legal y transparente.
        Aquí se explica lo esencial para que nadie se confunda.
      </p>

      <div class="split">
        <div class="card">
          <h3>✅ Reglas claras (para cobrar)</h3>
          <ul class="list">
            <li><span class="dot"></span><span><strong>Activo:</strong> tienes la membresía al día.</span></li>
            <li><span class="dot"></span><span><strong>Calificado:</strong> tienes al menos <strong>2
                  patrocinados</strong> activos y calificados.</span></li>
            <li><span class="dot"></span><span><strong> y cumplir la regla del 1%:</strong> Alcanzar al menos el 1% de
                tus ingresos recurrentes en número de clientes, ejemplo para poder cobrar 1000€ al menos solamente
                tienes que tener 10 clientes.</span></li>
          </ul>
          <p class="notice">Esto evita “matrices fantasma” y mantiene el enfoque en uso real.</p>
        </div>

        <div class="card">
          <h3>💶 Comisiones y bonos (resumen)</h3>
          <ul class="list">
            <li><span class="dot"></span><span><strong>Comisión por alta:</strong> 25€ por cada nuevo afiliado que se
                inscriba y pague su alta.</span></li>
            <li><span class="dot"></span><span><strong>Bono de inicio rápido:</strong> primeras 24h, si inscribes 2
                afiliados → 30€ adicionales (además de tus comisiones).</span></li>
            <li><span class="dot"></span><span><strong>Estructuras:</strong> unilevel (unilevel – red por niveles) +
                matriz forzada (forced matrix – matriz forzada).</span></li>
          </ul>
          <p class="notice">Ajusta aquí importes o condiciones si tu plan final difiere.</p>
        </div>
      </div>

      <div class="card full" style="margin-top:12px;">
        <h3>Matriz única forzada 8×4 (explicación sencilla)</h3>
        <ul class="list">
          <li><span class="dot"></span><span><strong>8×4:</strong> hasta 8 posiciones en el nivel 1 y 4 niveles de
              profundidad.</span></li>
          <li><span class="dot"></span><span><strong>Única:</strong> una sola matriz para todo el equipo.</span></li>
          <li><span class="dot"></span><span><strong>Forzada:</strong> se completa de izquierda a derecha, nivel por
              nivel.</span></li>
        </ul>

        <div class="cta-row">
          <a class="cta primary"
            href="<?=$enlace_registro;?>">Crear
            cuenta de afiliado</a>
          <a class="cta" href="#faq">Ver preguntas frecuentes</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq">
    <div class="wrap">
      <h2 class="section-title">Preguntas frecuentes (FAQ)</h2>
      <p class="section-desc">Respuestas directas, para que la gente no se quede con dudas.</p>

      <div class="cards faq">
        <div class="card full">
          <details>
            <summary>¿Feliz-IA sustituye a un médico o abogado u otro profesional?</summary>
            <p>No. Es asistencia informativa y de orientación. Para decisiones importantes o urgencias, consulta a un
              profesional.</p>
          </details>
        </div>

        <div class="card full">
          <details>
            <summary>Me preocupa la privacidad (RGPD – Reglamento General de Protección de Datos)</summary>
            <p>Cumplimos con toda la normativa del RGPD, pero aun así se recomienda compartir solo lo necesario, evitar
              datos de terceros y usar consentimientos claros, especialmente en categorías sensibles como salud.</p>
          </details>
        </div>

        <div class="card full">
          <details>
            <summary>¿Esto es un multinivel (network marketing)?</summary>
            <p>Si, aunque realmente es un hibrido, somos una Sociedad Limitada y para la comercialización usamos 2
              estrategias de pagos que se suelen usar en los multiniveles (un unilevel y una matriz forzada unica de
              8x4, usamos las 2 a la vez, porque conseguimos que nuestros afiliados consigan ingresos estables a corto
              plazo, y en el largo plazo puedan facturar todo lo que quieran. Además tenemos nuestras propias reglas
              diferentes de las habituales de los multiniveles clasicos. No se permiten promesas de ingresos, y los
              resultados dependen del trabajo y cumplimiento del plan.</p>
          </details>
        </div>

        <div class="card full">
          <details>
            <summary>¿Tengo que vender para estar?</summary>
            <p>No. Puedes ser solo cliente, y si eres afiliado deberias recomendar la herramienta de IA a las personas
              que la vayan a usar de verdad.</p>
          </details>
        </div>
      </div>
    </div>
  </section>

  <!-- Registro -->
  <section id="registro">
    <div class="wrap">
      <div class="card full" style="background: linear-gradient(180deg, rgba(255,255,255,.09), rgba(255,255,255,.04));">
        <h2 style="margin:0 0 6px; font-size: 26px; letter-spacing:-.4px;">Empieza hoy en 2 minutos</h2>
        <p class="section-desc" style="margin-bottom:10px;">
          Elige tu camino. Acceso inmediato. Materiales oficiales. Uso responsable.
        </p>

        <div class="cta-row">
          <a class="cta primary"
            href="<?=$enlace_registro_solo_cliente;?>">Crear
            cuenta como cliente</a>
          <a class="cta"
            href="<?=$enlace_registro;?>">Crear
            cuenta como afiliado</a>
        </div>

        <p class="notice">
          ✅ Acceso inmediato • ✅ Soporte y materiales • ⚠️ Sin ingresos garantizados (depende del trabajo y constancia)
        </p>
      </div>
    </div>
  </section>

  <footer>
    <div class="wrap">
      <div class="footer-grid">
        <div>
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
            <img src="https://feliz-ia.com/logo_feliz-ia.jpg" alt="Logo Feliz-IA" class="logo">
            <strong>Feliz-IA</strong>
          </div>
          <p style="margin:0 0 10px;">
            Feliz-IA ofrece asistencia mediante IAs (inteligencias artificiales). No sustituye asesoramiento
            profesional.
            El programa de afiliados no garantiza ingresos; dependen del esfuerzo, habilidades y cumplimiento del plan y
            políticas.
          </p>
          <p style="margin:0;">
            Tratamiento de datos conforme a RGPD (Reglamento General de Protección de Datos) y consentimientos
            aplicables, incluyendo categorías especiales (salud) cuando el cliente lo autoriza.
          </p>
        </div>

        <div>
          <p style="margin:0 0 10px;"><strong>Atajos</strong></p>
          <p style="margin:0 0 6px;"><a href="#videos">Vídeos</a></p>
          <p style="margin:0 0 6px;"><a href="#clientes">clientes</a></p>
          <p style="margin:0 0 6px;"><a href="#afiliados">Afiliados</a></p>
          <p style="margin:0 0 6px;"><a
              href="<?=$enlace_registro;?>">Registro</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Botón flotante móvil -->
  <div class="fab">
    <a class="cta primary" href="#registro">Empezar</a>
  </div>

  <script>
    // YouTube Player API Configuration
    const videoData = [
      { id: 'player1', videoId: 'ZWnFPo11OMg' }, // Pon aquí la ID del vídeo 1
      { id: 'player2', videoId: 'hz9XVIGkO4I' }, // Pon aquí la ID del vídeo 2
      { id: 'player3', videoId: 'RAtD0b7sIJ4' }, // Pon aquí la ID del vídeo 3
      { id: 'player4', videoId: 'kYpDhqLtBfI' }  // Pon aquí la ID del vídeo 4
    ];

    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function onYouTubeIframeAPIReady() {
      videoData.forEach(video => {
        new YT.Player(video.id, {
          height: '100%',
          width: '100%',
          videoId: video.videoId,
          playerVars: {
            'playsinline': 1,
            'rel': 0,
            'modestbranding': 1,
            'origin': window.location.origin === "null" ? "http://localhost" : window.location.origin
          },
          events: {
            'onError': (e) => console.error(`Error en ${video.id}:`, e.data)
          }
        });
      });
    }

    // Pequeña ayuda: si alguien deja el enlace como "REGISTRO_cliente_URL", avisamos en consola.
    (function () {
      const placeholders = ["REGISTRO_cliente_URL", "REGISTRO_AFILIADO_URL"];
      const html = document.documentElement.innerHTML;
      const missing = placeholders.filter(p => html.includes(p));
      if (missing.length) {
        console.warn("Faltan enlaces por configurar en el HTML:", missing);
      }
    })();
  </script>

</body>

</html>

<?php
function hasdato($valor){
  if(trim($valor)==''){
      return false;
  }
  else{
      return true;  
  }
}

?>
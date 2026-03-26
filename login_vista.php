<?php
extract($arvista)
  ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
		<!-- Favicon (icono del navegador) -->
<link rel="icon" href="/feliz-ia-favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/feliz-ia-favicon.ico" type="image/x-icon">
  <title>Login · Sea Mobile-IA by IAS SOLUCIONES</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: #f5f7fb;
      color: #1f2933;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .app {
      width: 100%;
      max-width: 420px;
      padding: 12px;
    }

    /* Barra superior */
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 14px 16px;
      background: #0f172a;
      color: #ffffff;
      border-radius: 14px 14px 0 0;
    }

    .top-bar__url {
      font-size: 15px;
      font-weight: 600;
      letter-spacing: 0.4px;
    }

    /* Tarjeta principal */
    .card {
      background: #ffffff;
      border-radius: 0 0 18px 18px;
      padding: 20px 16px 22px;
      box-shadow: 0 10px 22px rgba(15, 23, 42, 0.12);
    }

    .header-card {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #ffffff;
      border-radius: 16px;
      padding: 18px 16px;
      margin-bottom: 18px;
    }

    .header-card h1 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .header-card p {
      font-size: 13px;
      opacity: 0.9;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    label {
      font-size: 13px;
      font-weight: 600;
      color: #374151;
    }

    input {
      width: 100%;
      border-radius: 999px;
      border: 1px solid #d1d5db;
      padding: 11px 14px;
      font-size: 14px;
      outline: none;
      transition: border 0.15s ease, box-shadow 0.15s ease;
    }

    input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    .btn-primary {
      margin-top: 10px;
      background: #2563eb;
      color: #ffffff;
      border: none;
      border-radius: 999px;
      padding: 12px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-primary:hover {
      background: #1e4ed8;
    }

    .text-muted {
      margin-top: 14px;
      font-size: 13px;
      text-align: center;
      color: #6b7280;
    }

    .text-muted a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 600;
    }

    .footer {
      margin-top: 16px;
      text-align: center;
      font-size: 12px;
      opacity: 0.7;
    }

    /* APK Download Support */
    .apk-section {
      text-align: center;
      padding: 20px 0;
      margin-top: 20px;
      border-top: 1px solid #e5e7eb;
    }

    .btn-android {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      background: #3DDC84;
      color: #07101b;
      border-radius: 999px;
      padding: 12px 24px;
      font-size: 15px;
      font-weight: 600;
      transition: transform 0.15s ease, background 0.15s ease;
      box-shadow: 0 4px 12px rgba(61, 220, 132, 0.2);
    }

    .btn-android:hover {
      background: #35c676;
      transform: translateY(-2px);
    }

    #android-suggestion {
      display: none;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      padding: 12px;
      border-radius: 12px;
      margin-top: 20px;
      animation: fadeInUp 0.5s ease-out;
    }

    #android-suggestion p {
      margin: 0;
      color: #166534;
      font-size: 13px;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <div class="app">

    <header class="top-bar">
      <div class="top-bar__url">Sea Mobile-IA by IAS SOLUCIONES</div>
    </header>

    <div class="card">
      <div class="header-card">
        <h1>Acceso a Sea Mobile-IA</h1>
        <p>Introduce tus credenciales para continuar</p>
      </div>

      <form action="felizia.php?p=dologin" method="POST">
        <div>
          <label for="empresa">Empresa</label>
          <input type="text" placeholder="Empresa" readonly value="Sea Mobile-IA"  required />
          <input id="empresa" name="empresa" type="hidden" value="seamobile" />
        </div>

        <div>
          <label for="usuario">Usuario</label>
          <input id="username" name="username" type="text" placeholder="Username" required />
        </div>

        <div>
          <label for="password">Contraseña</label>
          <input id="password" name="password" type="password" placeholder="Password" required />
        </div>

        <button type="submit" class="btn-primary">Entrar</button>
      </form>

      <!-- <div class="text-muted">
        ¿Problemas para acceder? <a href="#">Contacta con soporte</a>
      </div> -->

      <div class="footer">
        © Sea Mobile-IA by IAS SOLUCIONES · Acceso seguro
      </div>

      <!-- APK Download Section -->
      <!-- <div class="apk-section">
        <a href="App_feliz-ia.apk" class="btn-android">
          <i class="fab fa-android" style="font-size: 20px;"></i> Descargar App (APK)
        </a>
        <div id="android-suggestion">
          <p><strong>✨ Android detectado</strong></p>
          <p>Te recomendamos descargar nuestra App oficial para una mejor experiencia.</p>
        </div>
      </div> -->
    </div>
  </div>

  <script>
    // Android Device Detection & App Mode Check
    (function () {
      const ua = navigator.userAgent;
      const isAndroid = /Android/i.test(ua);
      const isWebView = /wv/.test(ua); // Detecta si está dentro de una WebView (la App)
      const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

      // Solo mostramos la sección si es Android Y NO está ya en modo "App" o WebView
      if (isAndroid && (isStandalone || isWebView)) {
        // Si ya está en la App, nos aseguramos de que todo esté oculto
        document.querySelector('.apk-section').style.display = 'none';
      } else if (isAndroid) {
        // Si es Android pero está en el navegador normal, mostramos la sugerencia
        document.getElementById('android-suggestion').style.display = 'block';
      }
    })();
  </script>
</body>

</html>
<?php

function ecd($texto){
    return utf8_encode($texto);
}

function dcd($texto){
    return utf8_decode($texto);
}

function estilos(){
    ?>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
        sans-serif;
      background: #f5f7fb;
      color: #1f2933;
    }

    .app {
      min-height: 100vh;
      max-width: 480px;
      margin: 0 auto;
      padding-bottom: 90px; /* espacio para el menú flotante */
    }

    /* Barra superior simulada */
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      background: #0f172a;
      color: #ffffff;
    }

    .top-bar__url {
      font-size: 14px;
      opacity: 0.8;
    }

    .top-bar__icons span {
      margin-left: 8px;
      font-size: 18px;
    }

    /* Cabecera tipo tarjeta */
    .header-card {
      margin: 12px 12px 0;
      padding: 16px;
      border-radius: 16px;
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #ffffff;
    }

    .header-card__row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }

    .header-card__title {
      font-size: 18px;
      font-weight: 600;
    }

    .tag-vip {
      padding: 4px 10px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.16);
      font-size: 12px;
    }

    .header-card__balance-label {
      font-size: 13px;
      opacity: 0.9;
    }

    .header-card__balance-value {
      font-size: 24px;
      font-weight: 700;
    }

    main {
      padding: 12px;
    }

    /* Pantallas */
    .screen {
      display: none;
      animation: fade-in 0.2s ease-out;
    }

    .screen.active {
      display: block;
    }

    @keyframes fade-in {
      from { opacity: 0; transform: translateY(4px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .card {
      background: #ffffff;
      border-radius: 16px;
      padding: 16px;
      margin-bottom: 12px;
      box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }

    .card-title {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .row strong {
      font-weight: 600;
    }

    .btn-primary {
      display: block;
      width: 100%;
      text-align: center;
      background: #2563eb;
      color: #ffffff;
      padding: 12px;
      border-radius: 999px;
      border: none;
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      margin-top: 8px;
    }

    .text-muted {
      font-size: 13px;
      opacity: 0.8;
    }

    /* MENÚ INFERIOR FLOTANTE (estilo captura) */
    .bottom-nav-wrapper {
      position: fixed;
      left: 50%;
      bottom: 0;
      transform: translateX(-50%);
      width: 100%;
      max-width: 480px;
      padding: 0 10px 8px;
      background: transparent;
      pointer-events: none;
    }

    .bottom-nav {
      pointer-events: auto;
      background: #ffffff;
      border-radius: 18px 18px 0 0;
      box-shadow: 0 -4px 12px rgba(15, 23, 42, 0.12);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 6px 10px 10px;
      position: relative;
    }

    .nav-item {
      border: none;
      background: none;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      color: #9ca3af;
      padding: 4px 0;
      cursor: pointer;
    }

    .nav-item__icon {
      width: 26px;
      height: 26px;
      margin-bottom: 2px;
    }

    .nav-item svg {
      width: 100%;
      height: 100%;
      stroke: currentColor;
      fill: none;
      stroke-width: 2.1;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    /* Botón central flotante */
    .nav-fab {
      position: absolute;
      left: 50%;
      top: -26px;
      transform: translateX(-50%);
      width: 64px;
      height: 64px;
      border-radius: 999px;
      border: none;
      background: radial-gradient(circle at 30% 0, #60a5fa, #2563eb);
      box-shadow: 0 10px 18px rgba(37, 99, 235, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
    }

    .nav-fab .nav-item__icon {
      margin-bottom: 0;
      width: 28px;
      height: 28px;
    }

    .nav-fab svg {
      stroke: #ffffff;
    }

    /* Estado activo para los otros iconos */
    .nav-item--edge.active {
      color: #2563eb;
      font-weight: 600;
    }

    /* === ESTILOS DEL CHAT INTEGRADO === */
    .chat-box {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 8px;
      min-height: 400px;
      max-height: 400px;
      overflow-y: auto;
      background: #f9fafb;
      margin-top: 8px;
      font-size: 14px;
    }

    .chat-form {
      display: flex;
      gap: 6px;
      margin-top: 8px;
      justify-content: flex-end;
    }

    .chat-form input {
      flex: 1;
      border-radius: 999px;
      border: 1px solid #d1d5db;
      padding: 8px 10px;
      font-size: 14px;
      width: 100%;
    }

    .chat-form button {
      border-radius: 999px;
      border: none;
      padding: 8px 14px;
      background: #2563eb;
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
    }

    .chat-message {
      margin-bottom: 8px;
      line-height: 1.6;

      white-space: pre-wrap;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .chat-message {
      padding: 6px 10px;
      border-radius: 10px;
      max-width: 92%;
    }

    .chat-message.user {
      background: #e0ecff;
      margin-left: auto;
      text-align: left;
    }

    .chat-message.ia {
      background: #ffffff;
      border: 1px solid #e5e7eb;
    }


    .chat-message strong {
      font-weight: 600;
    }

    .chat-message.user strong {
      color: #2563eb;
    }

    .chat-message.ia strong {
      color: #111827;
    }

  .top-bar__icons {
    display: flex;
    align-items: center;
  }

  .top-bar__icons span,
  .top-bar__icons a {
    margin-left: 8px;
    font-size: 18px;
    line-height: 1;
    color: #ffffff;
    text-decoration: none;
    cursor: pointer;
    opacity: 0.9;
  }

  .top-bar__icons span:hover,
  .top-bar__icons a:hover {
    opacity: 1;
  }

  .top-bar__icons a {
  padding: 4px;
  }

  .hidden {
    display: none !important;
  }

  .profile-grid {
    display: grid;
    grid-template-columns: 70px 1fr; /* etiqueta | valor */
  }

  .profile-label {
    font-weight: 600;
    text-align: left;
  }

  .profile-value {
    opacity: 0.9;
    word-break: break-word;
  }

 .profile-form input {
      flex: 1;
      border-radius: 999px;
      border: 1px solid #d1d5db;
      padding: 4px 10px;
      font-size: 14px;
  }

  .profile-form select {
      flex: 1;
      border-radius: 999px;
      border: 1px solid #d1d5db;
      padding: 4px 10px;
      font-size: 14px;
  }

  .margin-left350 {
      margin-left:350px;
  }


  /* ====== TREE VIEW ====== */

.rojo {
    color: #FF0000;
    }

.verde {
      color:#32CD32;
    }

  
.treeview {
  overflow-x: auto;
}

.treeview ol {
  list-style: none;
  padding-left: 24px;
  position: relative;
}

.treeview ol::before {
  content: '';
  position: absolute;
  top: 0;
  left: 10px;
  border-left: 1px solid #cbd5e1;
  height: 100%;
}

.treeview li {
  margin: 8px 0;
  position: relative;
}

.treeview li::before {
  content: '';
  position: absolute;
  top: 18px;
  left: -14px;
  width: 14px;
  border-top: 1px solid #cbd5e1;
}

.treeview .dd-handle {
  display: inline-block;
  padding: 8px 12px;
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
}

.treeview .dd-handle:hover {
  background: #f1f5f9;
}

/* estado oculto */
.treeview .collapsed > ol {
  display: none;
}

/* icono */
.treeview .dd-handle::before {
  content: "▼";
  display: inline-block;
  margin-right: 6px;
  font-size: 12px;
}

.treeview .collapsed > .dd-handle::before {
  content: "▶";
}


.chat-message.ia table {
  width: 100%;
  border-collapse: collapse;
  margin: 15px 0;
}

.chat-message.ia th,
.chat-message.ia td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}

.chat-message.ia th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.chat-message.ia tr:nth-child(even) {
  background-color: #f9f9f9;
}


/* Capa inicial para "engañar" al navegador y permitir sonido */
    #splash {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        /* Fondo oscuro inicial */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 100;
        cursor: pointer;
        color: white;
        transition: opacity 0.5s ease, background 0.5s ease;
    }

    .play-button {
        width: 80px;
        height: 80px;
        background: rgba(59, 130, 246, 0.8);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        backdrop-filter: blur(4px);
    }

    .play-button svg {
        width: 40px;
        fill: white;
        margin-left: 5px;
    }

    .player-container {
        position: relative;
        width: auto;
        height: 200px;
        /* Altura fija solicitada */
        background: #000;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 8px;
    }

    /* Capa inicial ajustada al tamaño del video */
    #splash {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        /* Fondo oscuro inicial */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 100;
        cursor: pointer;
        color: white;
        transition: opacity 0.5s ease, background 0.5s ease;
    }

    .play-button {
        width: 60px;
        height: 60px;
        background: rgba(59, 130, 246, 0.8);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        backdrop-filter: blur(4px);
    }

    .play-button svg {
        width: 30px;
        fill: white;
        margin-left: 5px;
    }

    video {
        height: 200px;
        /* Altura fija */
        width: auto;
        max-width: 100%;
        display: block;
        visibility: hidden;
        cursor: pointer;
    }


/* Reproductor de voz */
.container {
        background: #1e293b;
        padding: 6px;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        text-align: center;
    }

  textarea {
      width: 100%;
      height: 120px;
      background: #334155;
      border: 1px solid #475569;
      border-radius: 12px;
      color: #fff;
      padding: 15px;
      font-size: 16px;
      margin-bottom: 20px;
      box-sizing: border-box;
      resize: none;
  }

  .controls {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      justify-content: center;
      flex-wrap: wrap;
  }

  select {
      width: 100%;
      background: #334155;
      color: white;
      border: 1px solid #475569;
      padding: 10px;
      border-radius: 12px;
      margin-bottom: 15px;
      cursor: pointer;
      font-family: inherit;
  }

  button {
      border: none;
      padding: 12px;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-family: inherit;
      font-weight: 600;
  }

  .btn-main {
      background: #3b82f6;
      flex: 2;
      gap: 8px;
  }

  .btn-main:hover {
      background: #2563eb;
  }

  .btn-secondary {
      background: #475569;
      flex: 1;
      display: none;
      /* Ocultos hasta que empiece */
  }

  .btn-secondary:hover {
      background: #334155;
  }

  .btn-stop {
      background: #ef4444;
  }

  .btn-stop:hover {
      background: #dc2626;
  }

  svg {
      width: 20px;
      height: 20px;
      fill: currentColor;
  }

</style>
  <?php
}


?>



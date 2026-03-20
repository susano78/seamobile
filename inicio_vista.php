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
<script src="js/marked.min.js"></script>
<link rel="stylesheet" href="css/katex.min.css">
<script defer src="js/katex.min.js"></script>
<script defer src="js/auto-render.min.js"></script> 
 <title>Sea Mobile-IA </title>
  <?php estilos(); ?>
</head>
<body>
  <div class="app">
    <!-- Barra superior -->
    <header class="top-bar">
      <div class="top-bar__url">Sea Mobile-IA by IAS SOLUCIONES</div>
      <div class="top-bar__icons">
        <span>🔔</span>
        <span>⚙️</span>
        <a href="index.php?p=cerrar_sesion" title="Cerrar sesión">🔒</a>
      </div>
    </header>

    <!-- Cabecera -->
    <section class="header-card">
      <div class="header-card__row">
        <div>
          <div class="header-card__title"><?=$nombre_usuario;?></div>
          <div style="font-size: 12px; opacity: .85;">ID: <?=$id_usuario;?></div>
        </div>
        <!-- <?php //if(!$es_solo_cliente){?>
           <div class="tag-vip">Activo: <?//=$estadoSINO;?> ; Calificado: <?//=$calificadoSINO;?></div>
        <?//php }else{?> 
           <div class="tag-vip">Activo: <?//=$estadoSINO;?></div>
        <?php // }?>  -->
      </div>
      <div class="header-card__row">
        <div>
          <div class="header-card__balance-label">Token disponibles: </div>
          <!-- <div class="header-card__balance-label"> 4 M de Entrada ; 0.5M de Salida</div> -->
          <!-- <div class="header-card__balance-label"> ? M de Entrada ; ? M de Salida</div> -->
           <div class="header-card__balance-label">?</div>
        </div>
      </div>
    </section>

    <!-- PANTALLAS -->
    <main>
      <!-- Home -->
    <?php if(!$es_solo_cliente){?> 
      <section id="screen-home" class="screen active">
        <div class="card">

          <div class="player-container">
              <div id="splash" onclick="startApp()">
                  <div class="play-button">
                      <svg viewBox="0 0 24 24">
                          <path d="M8 5v14l11-7z" />
                      </svg>
                  </div>
                  <p id="splashText" style="font-size: 14px; margin: 0;">Toca para empezar</p>
              </div>

              <video id="miVideo" playsinline>
                  <source src=".\soy_felizia_tu_IA.mp4" type="video/mp4">
                  Tu navegador no soporta video.
              </video>
          </div>

          <div class="card-title">¿Qué es una IA Temática?</div>
          <div class="row">
            <span>Unas IAs temáticas o personalizadas permiten crear un sistema que piensa,
            responde y actúa según las instrucciones sobre su temática en especial.
            Sirve para generar contenido específico, concreto y especializado mejor que el de una IA Genérica.</span>
          </div>
          <div class="row">
            <span> Estás en una plataforma de usos de inteligencias artificiales personalizadas por temas.</span>
          </div>
          <!-- <div class="row">
            <span> Estás en una plataforma de usos de inteligencias artificiales personalizadas por temas. Y además tiene un 
            desarrollo y / ó distribución de marketing increible: Se suman un Unilevel de 4 niveles o generaciones más una Matriz forzada de 8x4.</span>
          </div> -->
          <a id="enlace_alta_afiliado_registro" style="display:none" href="https://ia.seamobile.es/<?=getCarpetaRoot();?>registro/altaSeaMobile-IA.php?idafiliado=<?=$id_md5_afiliado;?>" target="_blank"></a>
          <a href="#" class="btn-primary" id="boton_copiar_enlace_alta_afiliado_registro">Copiar enlace de invitación a registro</a>

          <!-- <a id="enlace_alta_afiliado" style="display:none" href="https://ia.seamobile.es/<?//=getCarpetaRoot();?>inicio/index.php?idafiliado=<?//=$id_md5_afiliado;?>" target="_blank"></a>
          <a href="#" class="btn-primary" id="boton_copiar_enlace_alta_afiliado">Copiar enlace de web de invitación</a> -->

          <p class="text-muted" style="margin-top:8px;">
          </p>
			 <!-- <div class="row">
            <span> Para cualquier incidencia: soporte@feliz-ia.com</span>
          </div> -->
        </div>
      </section>
    <?php }?> 

      <!-- Equipo -->
     <?php if(!$es_solo_cliente){?>    
      <section id="screen-stats" class="screen">
        <div class="card">
          <div class="card-title">Datos de equipo (En desarrollo)</div>
          <div class="row">
            <span>Total Clientes Directos</span>
            <strong> Activos <?=$n_afiliados_directos_solo_cliente_activos;?> de un total de <?=$n_afiliados_directos_solo_cliente_total;?></strong>
          </div>
          <div class="row">
            <span>Total Afiliados Directos</span>
            <strong> Activos <?=$n_afiliados_directos_activos;?> de un total de <?=$n_afiliados_directos_total;?></strong>
          </div>
          <div class="row">
            <span>Total Unilevel</span>
            <strong> Activos <?=$n_total_unilevel_activos;?> de un total de <?=$n_total_unilevel_total;?></strong>
          </div>
          <div class="row">
            <span>Total Matriz</span>
            <strong> Activos <?=$n_total_8x4_activos;?> de un total de <?=$n_total_8x4_total;?></strong>
          </div>

          <a href="#" class="btn-primary toggle-unilevel">Ver Unilevel</a>
          <div class="hidden"><?php //show_tree_unilevel($rs_afiliados_tree_unilevel, $id_usuario); ?></div>

          <a href="#" class="btn-primary toggle-matriz8x4"  style="margin-top:6px;background:#10b981;">Ver Matriz</a>
          <div class="hidden"><?php //show_table_matriz8x4($rs_afiliados_treeImportes_8x4, $id_usuario); ?></div>
        </div>
        <div class="card">
          <div class="card-title">Activos</div>
          <p class="text-muted">
            Aquí se pueden listar productos (ciclos 7, 15 días, etc.) con su tasa diaria y botón “Ir a comprar”.
          </p>
        </div>
      </section>
    <?php }?> 

      <!-- IA s -->
     <section id="screen-chat" class="screen <?=((!$es_solo_cliente) ? '':'active');?>">
      <div class="card">

      <?php if($es_solo_cliente){?>
            <div class="player-container">
              <div id="splash" onclick="startApp()">
                  <div class="play-button">
                      <svg viewBox="0 0 24 24">
                          <path d="M8 5v14l11-7z" />
                      </svg>
                  </div>
                  <p id="splashText" style="font-size: 14px; margin: 0;">Toca para empezar</p>
              </div>

              <video id="miVideo" playsinline>
                  <source src=".\soy_felizia_tu_IA.mp4" type="video/mp4">
                  Tu navegador no soporta video.
              </video>
          </div>
      <?php }?> 

        <div class="card-title">Centro de IAs</div>
        <p class="text-muted">Selecciona una temática o escribe tu consulta directamente en el chat.</p>

        <!-- Botón cambiar temática -->
        <div id="change-theme-wrapper" class="hidden" style="margin-bottom:10px;">
          <a href="#" id="change-theme-btn" class="btn-primary" style="background:#6b7280;">
            Cambiar temática
          </a>
        </div>

        <!-- CONTENEDOR DE TEMÁTICAS -->
        <div id="themes-container">

        <?php 
          $n=count($rs_iatematicas);
          for($i=0;$i<$n;$i++){ 
              $rw=$rs_iatematicas[$i];
          ?>   
          <p class="text-muted">
            <a href="#" class="btn-primary theme-link" 
              data-theme-id="<?=$rw['id'];?>"><?=ecd($rw['nombre']);?>
            </a>
          </p>
          <?php }  ?>
         </div>

         <hr style="margin: 12px 0; border: none; border-top: 1px solid #e5e7eb;">

        <!-- CHAT -->
        <div id="chat-box" class="chat-box hidden"></div>

        <!-- Reproductor de voz -->
       <div id="voz-box" class="container hidden">
  
        <textarea class="hidden"
            id="textoInput">Esta es la versión profesional. Ahora tienes controles de Play, Pausa y Stop con iconos. Ideal para gestionar lecturas largas en cualquier hosting compartido sin instalar nada.</textarea>
  
        <div class="controls">
            <!-- Botón Principal: Play / Reanudar -->
            <button id="btnPlay" class="btn-main" onclick="reproducirVoz()">
                <svg viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <span id="textPlay">Reproducir</span>
            </button>

            <!-- Botón Pausa -->
            <button id="btnPause" class="btn-secondary" onclick="pausarVoz()">
                <svg viewBox="0 0 24 24">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                </svg>
            </button>

            <!-- Botón Stop -->
            <button id="btnStop" class="btn-secondary btn-stop" onclick="detenerVoz()">
                <svg viewBox="0 0 24 24">
                    <path d="M6 6h12v12H6z" />
                </svg>
            </button>
        </div>
     </div>
        <!-- Fin reproductor de voz -->

        <form id="chat-form" class="chat-form hidden">
          <input
            id="chat-mensaje"
            type="text"
            placeholder="Escribe tu mensaje..."
            autocomplete="off"
          />
          <button type="submit">Enviar</button>
        </form>
      </div>
     </section>

      <!-- Cartera -->
    <?php if(!$es_solo_cliente){?>   
      <section id="screen-wallet" class="screen">
        <div class="card">
          <div class="card-title">Cartera (En desarrollo)</div>
          <div class="row">
            <span>Clientes Directos:</span>
            <strong>(Activos <?=$n_afiliados_directos_solo_cliente_activos;?> de un total de <?=$n_afiliados_directos_solo_cliente_total;?>)?€</strong>
          </div>
          <div class="row">
            <span>Afiliados Directos:</span>
            <strong>(Activos <?=$n_afiliados_directos_activos;?> de un total de <?=$n_afiliados_directos_total;?>)?€</strong>
          </div>
          <div class="row">
            <span>Total Unilevel</span>
            <strong>(Activos <?=$n_total_unilevel_activos;?> de un total de <?=$n_total_unilevel_total;?>)?€</strong>
          </div>
          <div class="row">
            <span>Total Matriz:</span>
            <strong>(Activos <?=$n_total_8x4_activos;?> de un total de <?=$n_total_8x4_total;?>)?€</strong>
          </div>
          <div class="row">
            <span>Total € acumulados este mes:</span>
            <strong>?€</strong>
          </div>

          <a href="#" class="btn-primary toggle-unilevel">Ver Unilevel</a>
          <div class="hidden"><?php //show_tree_unilevel($rs_afiliados_tree_unilevel, $id_usuario); ?></div>

          <a href="#" class="btn-primary toggle-matriz8x4"  style="margin-top:6px;background:#10b981;">Ver Matriz</a>
          <div class="hidden"><?php //show_table_matriz8x4($rs_afiliados_treeImportes_8x4, $id_usuario); ?></div>
        </div>
      </section>
    <?php }?>

      <!-- Perfil -->
      <section id="screen-profile" class="screen">
        <div class="card">
          <div class="card-title">
            Configura tu Perfil, para que los resultados sean mas personalizados.
          </div>

          <form id="profile-form" class="profile-grid profile-form">
            <div class="profile-label">Nombre:</div>
            <div class="profile-value">
              <input type="text" name="nombre" value="<?=$nombre_usuario;?>" required>
            </div>

            <div class="profile-label">Sexo:</div>
            <div class="profile-value">
              <select name="sexo">
                <option value="">Selecciona</option>
                <option value="M" <?=($sexo=="M"?"selected":"")?>>Masculino</option>
                <option value="F" <?=($sexo=="F"?"selected":"")?>>Femenino</option>
              </select>
            </div>

            <div class="profile-label">Edad:</div>
            <div class="profile-value">
              <input type="number" name="edad" value="<?=$edad;?>" min="1" max="120">
            </div>

            <div class="profile-label">País:</div>
            <div class="profile-value">
              <input type="text" name="pais" value="<?=ecd($pais);?>">
            </div>

            <div style="grid-column: 1 / -1; margin-top:10px;">
              <button type="submit" class="btn-primary">Guardar perfil</button>
            </div>

            <div id="profile-msg" class="text-muted" style="grid-column: 1 / -1; margin-top:8px; display:none;"></div>
          </form>
        </div>
      </section>

  </main>
  </div>

  <!-- MENÚ INFERIOR -->
  <div class="bottom-nav-wrapper">
    <nav class="bottom-nav">
      <!-- izquierda -->
      <?php if(!$es_solo_cliente){?>  
      <button class="nav-item nav-item--edge active" data-target="screen-home">
        <div class="nav-item__icon">
          <!-- icono CASA/BUZÓN -->
          <svg viewBox="0 0 24 24">
            <path d="M4 10.5L12 4l8 6.5v7a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 17.5v-7z" />
            <path d="M9 16s1.5 1.5 3 1.5S15 16 15 16" />
          </svg>
        </div>
        Inicio
      </button>
      <?php }?>

      <?php //if(!$es_solo_cliente){?> 
      <!-- <button class="nav-item nav-item--edge" data-target="screen-stats">
        <div class="nav-item__icon">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="14" rx="2"></rect>
            <path d="M7 14v-3.5L11 9l4 3 2-1.5" />
          </svg>
        </div>
        Equipo
      </button> -->
      <?php //}?>

      <!-- botón central flotante (chat) -->
      <button class="nav-item nav-fab <?=((!$es_solo_cliente) ? '':'active');?>" data-target="screen-chat">
        <div class="nav-item__icon">
          <!-- icono CHAT DOBLE -->
          <svg viewBox="0 0 24 24">
            <path d="M6 18.5 6.8 16A6.5 6.5 0 1 1 12 18h-2.2z" />
            <path d="M13.5 11.5h3.5A3 3 0 0 1 20 14.5v2.5l1.5 2" />
          </svg>
        </div>
      </button>

      <?php //if(!$es_solo_cliente){?> 
      <!-- <button class="nav-item nav-item--edge" data-target="screen-wallet">
        <div class="nav-item__icon">
          <svg viewBox="0 0 24 24">
            <rect x="3" y="7" width="18" height="11" rx="2"></rect>
            <path d="M7 7V5a2 2 0 0 1 2-2h6" />
            <path d="M16 13h2.5a1.5 1.5 0 0 0 0-3H16" />
          </svg>
        </div>
        Cartera
      </button> -->
      <?php //}?>

      <button class="nav-item nav-item--edge <?=((!$es_solo_cliente) ? '':'margin-left350');?>" data-target="screen-profile">
        <div class="nav-item__icon">
          <!-- icono USUARIO -->
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="3.2"></circle>
            <path d="M5.5 19.5a6.5 6.5 0 0 1 13 0" />
          </svg>
        </div>
        Perfil
      </button>
    </nav>
  </div>

  <script>
    /* Navegación entre pantallas */
    const navItems = document.querySelectorAll(".nav-item");
    const edgeItems = document.querySelectorAll(".nav-item--edge");
    const screens = document.querySelectorAll(".screen");

    navItems.forEach((item) => {
      item.addEventListener("click", () => {
        const targetId = item.getAttribute("data-target");

        // marca activos: los de los lados
        edgeItems.forEach((i) => i.classList.remove("active"));
        if (item.classList.contains("nav-item--edge")) {
          item.classList.add("active");
        }

        // cambia pantalla
        screens.forEach((screen) => {
          screen.classList.toggle("active", screen.id === targetId);
        });
      });
    });
  </script>

  <script>   
    var boton_enlace_registro=document.getElementById('boton_copiar_enlace_alta_afiliado_registro');
    if(boton_enlace_registro!=null){
            boton_enlace_registro.addEventListener('click', function(event) {
                event.preventDefault();
                var enlace = document.getElementById('enlace_alta_afiliado_registro');   
                var input = document.createElement('input');
                input.setAttribute('value', enlace.href);   
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);    
                alert('Enlace de invitación copiado al portapapeles: ' + enlace.href);
          })
    };
    var boton_enlace=document.getElementById('boton_copiar_enlace_alta_afiliado');
    if(boton_enlace!=null){
            boton_enlace.addEventListener('click', function(event) {
                event.preventDefault();
                var enlace = document.getElementById('enlace_alta_afiliado');   
                var input = document.createElement('input');
                input.setAttribute('value', enlace.href);   
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);    
                alert('Enlace de invitación copiado al portapapeles: ' + enlace.href);
          })
    };
  </script>

  <script>
    let chatHistory = [];
  </script>

  <script>
    /* Lógica del chat con la API (chat.php) */
    const chatForm = document.getElementById("chat-form");
    const chatInput = document.getElementById("chat-mensaje");
    const chatBox = document.getElementById("chat-box");
    const vozBox = document.getElementById("voz-box");

    if (chatForm && chatInput && chatBox) {
      chatForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const texto = chatInput.value.trim();
        if (!texto) return;
        chatHistory.push({
          role: "user",
          content: texto
        });
        if (chatHistory.length > 20) {
            chatHistory = chatHistory.slice(-20);
        }


        // Muestra el mensaje del usuario
        const userMsg = document.createElement("div");
        userMsg.className = "chat-message user";
        userMsg.innerHTML = `<strong>Tú:</strong> ${texto}`;
        chatBox.appendChild(userMsg);
        chatBox.scrollTop = chatBox.scrollHeight;

        chatInput.value = "";

        try {
          const res = await fetch("chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              mensaje: texto,
              historial: chatHistory,
              origen: window.location.href,
              theme_id: currentThemeId,
              perfil: {
                nombre: document.querySelector('[name="nombre"]')?.value || "",
                sexo: document.querySelector('[name="sexo"]')?.value || "",
                edad: document.querySelector('[name="edad"]')?.value || "",
                pais: document.querySelector('[name="pais"]')?.value || ""
              }
            }),         
          });

          const data = await res.json();

          const iaMsg = document.createElement("div");
          iaMsg.className = "chat-message ia";

          if (data.respuesta) {
            //const respuestaFormateada = data.respuesta.replace(/\n/g, "<br>");
            const respuestaMarked = marked.parse(data.respuesta);
            document.getElementById('textoInput').value=stripHTML(respuestaMarked);
            iaMsg.innerHTML = `<strong>IA:</strong><br>${respuestaMarked}`;
            renderMathInElement(iaMsg, {
              delimiters: [
                { left: "$$", right: "$$", display: true },
                { left: "$", right: "$", display: false },
                { left: "\\(", right: "\\)", display: false }   // ✅ NUEVO
              ]
            });
            

            chatHistory.push({
              role: "assistant",
              content: data.respuesta
            });
            if (chatHistory.length > 20) {
              chatHistory = chatHistory.slice(-20);
            }
          } else {
            iaMsg.innerHTML = `<strong>Error:</strong> ${data.error || "Error desconocido"}`;
          }

          chatBox.appendChild(iaMsg);
          chatBox.scrollTop = chatBox.scrollHeight;
        } catch (err) {
          const errMsg = document.createElement("div");
          errMsg.className = "chat-message ia";
          errMsg.innerHTML = `<strong>Error:</strong> ${err.message}`;
          chatBox.appendChild(errMsg);
          chatBox.scrollTop = chatBox.scrollHeight;
        }
      });
    }

  const screenChat = document.getElementById("screen-chat");
  const themeLinks = document.querySelectorAll(".theme-link");
  const changeThemeWrapper = document.getElementById("change-theme-wrapper");
  const changeThemeBtn = document.getElementById("change-theme-btn");

  let currentThemeLink = null; // temática actualmente seleccionada
  let currentThemeId = null;


   // Limpia el chat
  function clearChat() {
    chatBox.innerHTML = "";
    chatHistory = [];
  }

  document.addEventListener("DOMContentLoaded", resetThemes);

  // Click en temática
  themeLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();

       // Si la temática seleccionada es diferente al link actual → limpiar chat
      if (currentThemeLink !== link) {
        clearChat();
        currentThemeLink = link; // actualizar temática actual
        currentThemeId = link.dataset.themeId || null;
      }
      themeLinks.forEach(l => {
        if (l !== link) {
          l.closest("p").classList.add("hidden");
        }
      });

      chatBox.classList.remove("hidden");
      vozBox.classList.remove("hidden");
      chatForm.classList.remove("hidden");
      changeThemeWrapper.classList.remove("hidden");
    });
  });

  // Cambiar temática
  changeThemeBtn.addEventListener("click", e => {
    e.preventDefault();
    resetThemes();
  });

  function resetThemes() {
    themeLinks.forEach(link => {
      link.closest("p").classList.remove("hidden");
    });
    chatBox.classList.add("hidden");
    vozBox.classList.add("hidden");
    chatForm.classList.add("hidden");
    changeThemeWrapper.classList.add("hidden");
  }

  //Guardar perfil
  const profileForm = document.getElementById("profile-form");
  const profileMsg  = document.getElementById("profile-msg");

  if (profileForm) {
    profileForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      profileMsg.style.display = "none";
      profileMsg.textContent = "";

      const formData = {
        nombre: profileForm.nombre.value,
        sexo: profileForm.sexo.value,
        edad: profileForm.edad.value,
        pais: profileForm.pais.value
      };

      try {
        const res = await fetch("guardar_perfil.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(formData)
        });

        const data = await res.json();

        profileMsg.style.display = "block";
        profileMsg.textContent = data.mensaje || "Perfil guardado correctamente";
      } catch (err) {
        profileMsg.style.display = "block";
        profileMsg.textContent = "Error al guardar el perfil";
      }
    });
  }
  </script>

<script>
document.querySelectorAll(".toggle-unilevel").forEach(btn => {
  btn.addEventListener("click", e => {
    e.preventDefault();

    const box = btn.nextElementSibling;

    box.classList.toggle("hidden");

    btn.textContent = box.classList.contains("hidden")
      ? "Ver Unilevel"
      : "Ocultar Unilevel";
  });
});
document.querySelectorAll(".toggle-matriz8x4").forEach(btn => {
  btn.addEventListener("click", e => {
    e.preventDefault();

    const box = btn.nextElementSibling;

    box.classList.toggle("hidden");

    btn.textContent = box.classList.contains("hidden")
      ? "Ver Matriz"
      : "Ocultar Matriz";
  });
});

document.addEventListener("DOMContentLoaded", function () {

  document.querySelectorAll("#treeAfiliados .dd-item").forEach(function (item) {
    const children = item.querySelector("ol");

    if (!children) return;

    const handle = item.querySelector(".dd-handle");

    handle.addEventListener("click", function (e) {
      e.stopPropagation();
      item.classList.toggle("collapsed");
    });
  });

});
</script>

<script>
    const video = document.getElementById('miVideo');
    const splash = document.getElementById('splash');
    const splashText = document.getElementById('splashText');

    function startApp() {
        // 1. Ocultar el splash
        splash.style.opacity = '0';
        setTimeout(() => {
            if (splash.style.opacity === '0') {
                splash.style.display = 'none';
            }
        }, 500);

        // 2. Mostrar y reproducir el video con SONIDO
        video.style.visibility = 'visible';
        video.muted = false;
        video.currentTime = 0; // Reiniciar por si acaso
        video.play();
    }

    // Control de pausa/play al hacer clic directamente en el video
    video.addEventListener('click', () => {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    });

    // Aseguramos reproducción única
    video.loop = false;

    // Al finalizar el video, volvemos a mostrar el botón de PLAY
    video.onended = () => {
        console.log("Video finalizado. Mostrando botón de reintento.");
        // Al terminar, hacemos que el fondo del splash sea transparente
        // para que se vea la última imagen del video debajo.
        splash.style.background = 'rgba(0, 0, 0, 0.2)';
        splashText.innerText = 'Volver a reproducir';
        splash.style.display = 'flex';
        setTimeout(() => splash.style.opacity = '1', 10);
    };
  </script>

   <script>
  function stripHTML(html) {
      const temp = document.createElement("div");
      temp.innerHTML = html;
      return temp.textContent || temp.innerText || "";
   }
  </script>

  <script>
    let utterance = null;
    let isPlaying = false;
    let isPaused = false;
    let vocesDisponibles = [];
    let vozSeleccionada = null;
    let frases = [];
    let indiceFraseActual = 0;

    const btnPlay = document.getElementById('btnPlay');
    const textPlay = document.getElementById('textPlay');
    const btnPause = document.getElementById('btnPause');
    const btnStop = document.getElementById('btnStop');

    function esMovil() {
      return /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    }
    
   function cargarVoces() {
      vocesDisponibles = window.speechSynthesis.getVoices();
        if (vocesDisponibles.length > 0) {
            // Buscar voz en español
            vozSeleccionada = vocesDisponibles.find(v => v.lang.startsWith('es'));
            
            // Si no hay español, usar la primera disponible
            if (!vozSeleccionada) {
                vozSeleccionada = vocesDisponibles[0];
            }
        }
    }

    function esAPKAndroid() {
      const ua = navigator.userAgent || "";
       return ua.includes("wv") && ua.includes("Android") && window.AndroidTTS;
    }

    function reproducirVoz() {
        const texto = document.getElementById('textoInput').value;
        if (!texto) return;

        // APK → TTS nativo
        if (esAPKAndroid()) {

            if (isPaused) {
                // Si tu TTS nativo soporta resume, llama aquí
                // window.AndroidTTS.resume(); //ojo revisar
                isPaused = false;
                isPlaying = true;
                updateUI(true);
                textPlay.innerText = "Reproduciendo...";
                return;
            }

            if (isPlaying) {
                // Cancelar reproducción anterior
                window.AndroidTTS.stop();
            }

            isPlaying = true;
            isPaused = false;
            updateUI(true);
            textPlay.innerText = "Reproduciendo...";

            // Llamada al TTS nativo
            window.AndroidTTS.speak(texto);

            return;
        }

        if (isPaused) {
                isPaused = false;
                isPlaying = true;
                updateUI(true);
                textPlay.innerText = "Reproduciendo...";
                reproducirBloque();
                return;
            }

        window.speechSynthesis.cancel();

        prepararTexto(texto);

        isPlaying = true;
        isPaused = false;

        updateUI(true);
        textPlay.innerText = "Reproduciendo...";

        reproducirBloque();
        
    }

    function prepararTexto(texto) {
      // División inteligente: frases + pausas naturales
      frases = texto
          .replace(/\n+/g, ' ')
          .match(/[^\.!\?]+[\.!\?]+|[^\.!\?]+$/g) || [texto];

      indiceFraseActual = 0;
    }

    function reproducirBloque() {

      if (!isPlaying || indiceFraseActual >= frases.length) {
          finalizarReproduccion();
          return;
      }

      const voiceSelectDefault = 'es-ES_slow';
      const lang = voiceSelectDefault.replace('_slow', '');

      utterance = new SpeechSynthesisUtterance(frases[indiceFraseActual]);

      utterance.lang = lang;
      utterance.rate = voiceSelectDefault.endsWith('_slow') ? 0.9 : 1.1;

      if (vozSeleccionada) {
          utterance.voice = vozSeleccionada;
      }

      utterance.onend = () => {

          if (!isPlaying) return;

          indiceFraseActual++;

          // Micro-delay invisible para evitar bug Android
          setTimeout(() => {
              reproducirBloque();
          }, esMovil() ? 60 : 0);
      };

      window.speechSynthesis.speak(utterance);
     }   

    function pausarVoz() {
      if (isPlaying) {
        if (esAPKAndroid()) {
            // Si tu TTS nativo soporta pausa, llama aquí
            window.AndroidTTS.stop();
            isPlaying = false;
            isPaused = true;
            updateUI(false);
            textPlay.innerText = "Reanudar";
            return;
        }
        
         window.speechSynthesis.cancel();
         isPlaying = false;
         isPaused = true;
         updateUI(false);
         textPlay.innerText = "Reanudar";
      }
    }

    function detenerVoz() {
      if (esAPKAndroid()) {
        window.AndroidTTS.stop();
        isPlaying = false;
        isPaused = false;
        updateUI(false);
        textPlay.innerText = "Reproducir";
        return;
      }

        window.speechSynthesis.cancel();
        isPlaying = false;
        isPaused = false;
        indiceFraseActual = 0;
        updateUI(false);
        textPlay.innerText = "Reproducir";
    }

    function finalizarReproduccion() {
        isPlaying = false;
        isPaused = false;
        indiceFraseActual = 0;

        updateUI(false);
        textPlay.innerText = "Reproducir";
   }

    function updateUI(active) {
        btnPause.style.display = active || isPaused ? 'flex' : 'none';
        btnStop.style.display = active || isPaused ? 'flex' : 'none';
    }

    if(!(esAPKAndroid())) {
      window.speechSynthesis.onvoiceschanged = cargarVoces;
      cargarVoces();
    }
</script>


</body>
</html>

<?php
/**
 * Template Name: Inicio Interactiva
 */

get_header();
?>

<style>
  body.home .entry-header,
  body.home .page-title,
  body.home h1.entry-title,
  body.home .kadence-page-header {
    display: none !important;
  }

  body.home {
    margin: 0;
    padding: 0;
    overflow: hidden;
    background: #000;
  }

  #los-ojos-canvas {
    display: block;
    position: fixed;
    inset: 0;
    z-index: 1;
    width: 100vw;
    height: 100vh;
    /* === CORRECCIÓN 1: Forzar capa GPU para renderizado instantáneo === */
    will-change: transform;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
  }

  .los-ojos-logo-link {
    position: fixed;
    z-index: 2;
    top: 50%;
    left: 50%;
    transform: translate3d(-50%, -50%, 0);
    width: min(25vw, 300px);
    height: auto;
    max-height: 300px;
    display: block;
    cursor: pointer;
  }

  #los-ojos-logo {
    display: block;
    width: 100%;
    height: auto;
    pointer-events: none;
    user-select: none;
    image-rendering: -webkit-optimize-contrast;
    shape-rendering: geometricPrecision;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    transition: opacity 0.6s ease;
  }

  .screen-reader-text {
    clip: rect(1px, 1px, 1px, 1px);
    position: absolute !important;
    height: 1px;
    width: 1px;
    overflow: hidden;
    word-wrap: normal !important;
  }

  body.home .site-header {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    position: fixed;
    width: 100%;
    z-index: 10;
  }

  body.home .site-header .site-branding a,
  body.home .site-header .site-title a {
    color: #fff;
  }

  body.home .site-header .header-navigation .menu-item a {
    color: rgba(255, 255, 255, 0.8);
  }

  body.home .site-header .header-navigation .menu-item a:hover {
    color: #fff;
  }
</style>

<!-- === CORRECCIÓN 2: Atributos críticos para LCP, CLS y exclusión de LiteSpeed === -->
<a
  href="<?php echo esc_url(home_url('/servicios/')); ?>"
  class="los-ojos-logo-link"
  aria-label="Servicios"
>
  <img
    id="los-ojos-logo"
    src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/losojos_logo.svg'); ?>"
    alt="Los Ojos"
    width="300"
    height="300"
    fetchpriority="high"
    loading="eager"
    decoding="async"
    data-no-lazy="1"
  />
</a>

<main id="primary" class="site-main los-ojos-hero" style="visibility: hidden;">
  <div class="screen-reader-text">
    <h1>Los Ojos</h1>
    <h2>
      Diseñamos y desarrollamos objetos y experiencias que combinan
      tecnología, espacio, audiovisual e interacción.
    </h2>
    <p>
      Los Ojos nace de una convicción sencilla: la tecnología como
      herramienta, lenguaje, medio creativo y forma de pensamiento capaz
      de configurar nuevas maneras de relacionarnos con el mundo. Un campo
      de convergencia entre código, imagen, espacio, sonido, movimiento,
      humanos y más que humanos.
    </p>
  </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.19.0/matter.min.js"></script>

<script>
// === CORRECCIÓN 3: Wrapper robusto que se ejecuta incluso si LiteSpeed retrasó el DOMContentLoaded ===
function initMatterAnimation() {
  const { Engine, Bodies, Body, Composite } = Matter;

  const engine = Engine.create({
    gravity: { x: 0, y: 0 }
  });

  engine.positionIterations = 50;
  engine.velocityIterations = 25;

  const logo = document.getElementById("los-ojos-logo");
  const canvas = document.createElement("canvas");
  canvas.id = "los-ojos-canvas";
  const ctx = canvas.getContext("2d");
  document.body.appendChild(canvas);

  const eyePath = new Path2D(
    'M0,17.935 C-1.564,20.822 -3.709,23.112 -6.43,24.811 C-9.153,26.511 -12.216,27.359 -15.611,27.359 C-19.12,27.359 -22.273,26.511 -25.076,24.811 C-27.88,23.112 -30.104,20.822 -31.75,17.935 C-33.393,15.052 -34.216,11.857 -34.216,8.352 C-34.216,4.739 -33.409,1.488 -31.788,-1.398 C-30.17,-4.282 -27.987,-6.575 -25.236,-8.271 C-22.487,-9.97 -19.387,-10.822 -15.935,-10.822 C-12.483,-10.822 -9.382,-9.97 -6.631,-8.271 C-3.882,-6.575 -1.699,-4.282 -0.082,-1.398 C1.536,1.488 2.347,4.739 2.347,8.352 C2.347,11.857 1.564,15.052 0,17.935 M33.83,7.092 C28.314,0.395 8.123,-21.932 -15.938,-21.932 C-39.986,-21.932 -60.165,0.367 -65.697,7.08 C-66.629,8.213 -66.629,9.725 -65.697,10.858 C-60.165,17.568 -39.986,39.867 -15.938,39.867 C8.123,39.867 28.314,17.542 33.83,10.846 C34.756,9.721 34.756,8.217 33.83,7.092'
  );

  const isMobile = window.innerWidth < 768;
  const N = isMobile ? 56 : 156;
  const eyes = [];

  const W = () => canvas.width;
  const H = () => canvas.height;

  const mouse = { x: -9999, y: -9999 };

  function resize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }

  resize();
  window.addEventListener("resize", resize);

  window.addEventListener("mousemove", (e) => {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
  });

  window.addEventListener("mouseleave", () => {
    mouse.x = -9999;
    mouse.y = -9999;
  });

  window.addEventListener("touchmove", (e) => {
    const t = e.touches[0];
    mouse.x = t.clientX;
    mouse.y = t.clientY;
  }, { passive: true });

  window.addEventListener("touchend", () => {
    mouse.x = -9999;
    mouse.y = -9999;
  });

  const wallOpts = { isStatic: true, restitution: 0.4 };
  let walls = [];

  function makeWalls() {
    walls.forEach((w) => Composite.remove(engine.world, w));
    const t = 60;
    walls = [
      Bodies.rectangle(W() / 2, -t / 2, W() + t * 2, t, wallOpts),
      Bodies.rectangle(W() / 2, H() + t / 2, W() + t * 2, t, wallOpts),
      Bodies.rectangle(-t / 2, H() / 2, t, H() + t * 2, wallOpts),
      Bodies.rectangle(W() + t / 2, H() / 2, t, H() + t * 2, wallOpts)
    ];
    Composite.add(engine.world, walls);
  }

  makeWalls();

  window.addEventListener("resize", () => {
    makeWalls();
    updateLogoBody();
  });

  let logoBody = null;

  // === CORRECCIÓN 4: Mecanismo de reintento si el navegador aún no pintó el SVG ===
  function updateLogoBody() {
    if (logoBody) {
      Composite.remove(engine.world, logoBody);
    }

    const r = logo.getBoundingClientRect();

    // Si el ancho es 0, el navegador no ha terminado de renderizar. Reintentamos en 50ms.
    if (!r.width || !r.height) {
      setTimeout(updateLogoBody, 50);
      return;
    }

    logoBody = Bodies.rectangle(
      r.left + r.width / 2,
      r.top + r.height / 2,
      r.width + 40,
      r.height + 40,
      {
        isStatic: true,
        restitution: 0.8,
        render: { visible: false }
      }
    );

    Composite.add(engine.world, logoBody);
  }

  function insideLogo(x, y) {
    if (!logoBody) return false;
    const r = logo.getBoundingClientRect();
    return x >= r.left && x <= r.right && y >= r.top && y <= r.bottom;
  }

  const minDist = (r1, r2) => (r1 + r2) * 4;

  function tooClose(x, y, r) {
    for (const e of eyes) {
      const dx = e.position.x - x;
      const dy = e.position.y - y;
      if (Math.sqrt(dx * dx + dy * dy) < minDist(r, e.circleRadius)) {
        return true;
      }
    }
    return false;
  }

  for (let i = 0; i < N; i++) {
    const r = 6 + Math.random() * 21;
    let x, y, tries = 0;

    do {
      x = Math.random() * W();
      y = Math.random() * H();
    } while ((insideLogo(x, y) || tooClose(x, y, r)) && tries++ < 200);

    const b = Bodies.circle(x, y, r, {
      restitution: 0.15,
      friction: 0.15,
      frictionAir: 0.01
    });

    Body.setVelocity(b, { x: (Math.random() - 0.5) * 0.6, y: (Math.random() - 0.5) * 0.6 });
    Body.setAngle(b, Math.random() * Math.PI * 2);
    Body.setAngularVelocity(b, (Math.random() - 0.5) * 0.002);

    eyes.push(b);
    Composite.add(engine.world, b);
  }

  // Llamada directa en lugar de requestAnimationFrame anidado para garantizar ejecución
  updateLogoBody();

  function loop() {
    Engine.update(engine, 1000 / 60);

    ctx.fillStyle = "#000";
    ctx.fillRect(0, 0, W(), H());

    eyes.forEach((c) => {
      const dx = c.position.x - mouse.x;
      const dy = c.position.y - mouse.y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      const rad = c.circleRadius;
      const maxDist = 120 + rad;

      if (dist < maxDist && dist > 0.1) {
        const force = ((maxDist - dist) / maxDist) * 0.004;
        Body.applyForce(c, c.position, { x: (dx / dist) * force, y: (dy / dist) * force });
      }

      for (let j = 0; j < eyes.length; j++) {
        const o = eyes[j];
        if (c === o) continue;

        const dx = c.position.x - o.position.x;
        const dy = c.position.y - o.position.y;
        const d = Math.sqrt(dx * dx + dy * dy);
        const minD = (c.circleRadius + o.circleRadius) * 1.1;

        if (d < minD && d > 0.1) {
          Body.applyForce(c, c.position, {
            x: (dx / d) * ((minD - d) / minD) * 0.003,
            y: (dy / d) * ((minD - d) / minD) * 0.003
          });
        }
      }

      const s = rad / 28;
      ctx.save();
      ctx.translate(c.position.x, c.position.y);
      ctx.rotate(c.angle);
      ctx.scale(s, s);
      ctx.fillStyle = "#fff";
      ctx.fill(eyePath, "evenodd");
      ctx.restore();
    });

    requestAnimationFrame(loop);
  }

  loop();
}

// === CORRECCIÓN 5: Ejecutar inmediatamente si el DOM ya está listo, o esperar si no lo está ===
if (document.readyState === 'loading') {
  document.addEventListener("DOMContentLoaded", initMatterAnimation);
} else {
  initMatterAnimation();
}
</script>

<?php
get_footer();
?>
(function() {
  'use strict';

  function initMatterAnimation() {
    if (typeof Matter === 'undefined') {
      setTimeout(initMatterAnimation, 50);
      return;
    }

    // 1. Buscamos el canvas que YA existe en el HTML (generado por el shortcode)
    const canvas = document.getElementById("los-ojos-canvas");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    const { Engine, Bodies, Body, Composite } = Matter;
    
    const engine = Engine.create({ gravity: { x: 0, y: 0 } });
    engine.positionIterations = 50;
    engine.velocityIterations = 25;

    const W = () => canvas.width;
    const H = () => canvas.height;
    const mouse = { x: -9999, y: -9999 };

    // 2. Función de resize adaptada al contenedor, no a la ventana completa
    function resize() {
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;
    }
    resize();
    window.addEventListener("resize", resize);

    // Eventos del mouse/touch relativos al canvas
    canvas.addEventListener("mousemove", (e) => {
      const rect = canvas.getBoundingClientRect();
      mouse.x = e.clientX - rect.left;
      mouse.y = e.clientY - rect.top;
    });

    canvas.addEventListener("mouseleave", () => {
      mouse.x = -9999;
      mouse.y = -9999;
    });

    canvas.addEventListener("touchmove", (e) => {
      const t = e.touches[0];
      const rect = canvas.getBoundingClientRect();
      mouse.x = t.clientX - rect.left;
      mouse.y = t.clientY - rect.top;
    }, { passive: true });

    canvas.addEventListener("touchend", () => {
      mouse.x = -9999;
      mouse.y = -9999;
    });

    const wallOpts = { isStatic: true, restitution: 0.4 };
    let walls = [];

    function makeWalls() {
      walls.forEach((w) => Composite.remove(engine.world, w));
      const t = 60;
      walls = [
        Bodies.rectangle(W()/2, -t/2, W()+t*2, t, wallOpts),
        Bodies.rectangle(W()/2, H()+t/2, W()+t*2, t, wallOpts),
        Bodies.rectangle(-t/2, H()/2, t, H()+t*2, wallOpts),
        Bodies.rectangle(W()+t/2, H()/2, t, H()+t*2, wallOpts)
      ];
      Composite.add(engine.world, walls);
    }
    makeWalls();
    window.addEventListener("resize", makeWalls);

    const eyePath = new Path2D('M0,17.935 C-1.564,20.822 -3.709,23.112 -6.43,24.811 C-9.153,26.511 -12.216,27.359 -15.611,27.359 C-19.12,27.359 -22.273,26.511 -25.076,24.811 C-27.88,23.112 -30.104,20.822 -31.75,17.935 C-33.393,15.052 -34.216,11.857 -34.216,8.352 C-34.216,4.739 -33.409,1.488 -31.788,-1.398 C-30.17,-4.282 -27.987,-6.575 -25.236,-8.271 C-22.487,-9.97 -19.387,-10.822 -15.935,-10.822 C-12.483,-10.822 -9.382,-9.97 -6.631,-8.271 C-3.882,-6.575 -1.699,-4.282 -0.082,-1.398 C1.536,1.488 2.347,4.739 2.347,8.352 C2.347,11.857 1.564,15.052 0,17.935 M33.83,7.092 C28.314,0.395 8.123,-21.932 -15.938,-21.932 C-39.986,-21.932 -60.165,0.367 -65.697,7.08 C-66.629,8.213 -66.629,9.725 -65.697,10.858 C-60.165,17.568 -39.986,39.867 -15.938,39.867 C8.123,39.867 28.314,17.542 33.83,10.846 C34.756,9.721 34.756,8.217 33.83,7.092');

    const isMobile = window.innerWidth < 768;
    const N = isMobile ? 56 : 156;
    const eyes = [];

    for (let i = 0; i < N; i++) {
      const r = 6 + Math.random() * 21;
      let x, y, tries = 0;
      do {
        x = Math.random() * W();
        y = Math.random() * H();
      } while (tries++ < 200);

      const b = Bodies.circle(x, y, r, { restitution: 0.15, friction: 0.15, frictionAir: 0.01 });
      Body.setVelocity(b, { x: (Math.random()-0.5)*0.6, y: (Math.random()-0.5)*0.6 });
      Body.setAngle(b, Math.random() * Math.PI * 2);
      Body.setAngularVelocity(b, (Math.random()-0.5) * 0.002);
      eyes.push(b);
      Composite.add(engine.world, b);
    }

    let lastTime = 0;
    function loop(timestamp) {
      if (!lastTime) lastTime = timestamp;
      const delta = timestamp - lastTime;
      lastTime = timestamp;
      Engine.update(engine, delta > 0 ? delta : 1000/60);

      ctx.fillStyle = "#000";
      ctx.fillRect(0, 0, W(), H());

      eyes.forEach((c) => {
        const dx = c.position.x - mouse.x;
        const dy = c.position.y - mouse.y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        const rad = c.circleRadius;
        const maxDist = 120 + rad;

        if (dist < maxDist && dist > 0.1) {
          const force = ((maxDist - dist) / maxDist) * 0.004;
          Body.applyForce(c, c.position, { x: (dx/dist)*force, y: (dy/dist)*force });
        }

        for (let j = 0; j < eyes.length; j++) {
          const o = eyes[j];
          if (c === o) continue;
          const dx = c.position.x - o.position.x;
          const dy = c.position.y - o.position.y;
          const d = Math.sqrt(dx*dx + dy*dy);
          const minD = (c.circleRadius + o.circleRadius) * 1.1;
          if (d < minD && d > 0.1) {
            Body.applyForce(c, c.position, {
              x: (dx/d) * ((minD-d)/minD) * 0.003,
              y: (dy/d) * ((minD-d)/minD) * 0.003
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

    requestAnimationFrame(loop);
    setTimeout(() => { if (!lastTime) loop(performance.now()); }, 100);
  }

  // Ejecutar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", initMatterAnimation);
  } else {
    initMatterAnimation();
  }
})();
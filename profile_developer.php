<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Meet the developer team behind Smart IoT Solutions — Kelompok 2" />
    <title>Developer Team — Kelompok 2</title>
    <link rel="icon" href="/assets/img/polines.png"> 
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/developer.css" />
    <style>
        /* Fitur fungsional tersisa khusus untuk Modal Popup Image bawaan sistem */
        .photo-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background-color: rgba(18, 23, 33, 0.9);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .photo-modal__image {
            max-width: min(520px, 90vw);
            max-height: 80vh;
            border-radius: 20px;
            box-shadow: -10px -10px 25px #2d3a4f, 10px 10px 25px #0a0d14;
        }
        .photo-modal__caption {
            margin-top: 16px;
            color: #00f0ff;
            font-weight: 700;
            text-align: center;
            font-family: "Manrope", sans-serif;
        }
        .photo-modal__content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .photo-modal__close {
            position: absolute;
            top: 20px;
            right: 24px;
            font-size: 36px;
            color: #fff;
            cursor: pointer;
            background: none;
            border: none;
        }
    </style>
</head>
<body class="dev-page">

    <canvas id="networkCanvas"></canvas>

    <div class="floating-icons" aria-hidden="true">
        <span class="floating-icon"><i class="fas fa-wifi"></i></span>
        <span class="floating-icon"><i class="fas fa-microchip"></i></span>
        <span class="floating-icon"><i class="fas fa-cloud"></i></span>
        <span class="floating-icon"><i class="fas fa-satellite-dish"></i></span>
        <span class="floating-icon"><i class="fas fa-server"></i></span>
        <span class="floating-icon"><i class="fas fa-network-wired"></i></span>
    </div>

    <div class="dev-wrapper">

        <div class="dev-topbar">
            <div class="dev-header__brand">
                <a href="index.php" class="btn-back" id="btnBackTop">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
                <img src="assets/img/polines.png" alt="Logo Polines" class="dev-header__logo" />
                <div class="dev-header__institution">
                    <span class="dev-header__inst-line">JURUSAN TEKNIK ELEKTRO</span>
                    <span class="dev-header__inst-line">PROGRAM STUDI D4 TEKNIK TELEKOMUNIKASI</span>
                    <span class="dev-header__inst-line dev-header__inst-line--campus">POLITEKNIK NEGERI SEMARANG</span>
                </div>
            </div>
            <div class="dev-header__institution dev-topbar-right">
                <span class="dev-header__inst-line">Mata Kuliah : Pemrograman Web &amp; Database</span>
                <span class="dev-header__inst-line">Dosen Pengampu : Helmy , S.T., M.Eng.</span>
                <span class="dev-header__inst-line dev-header__inst-line--campus">Profil Kelompok 2</span>
            </div>
        </div>

        <section class="dev-grid" id="devCards">

            <article class="dev-card reveal reveal-delay-1">
                <div class="dev-card__top">
                    <div class="dev-card__avatar-wrap">
                        <img src="assets/img/dev1.png" alt="Foto Developer 1" class="dev-card__avatar" onclick="openPhotoModal(this)" />
                    </div>
                    <div class="dev-card__identity">
                        <h2 class="dev-card__name">Muhammad Irfan</h2>
                        <p class="dev-card__role">Telecommunication Engineer</p>
                        <div class="dev-card__meta">
                            <div class="dev-card__skills">
                                <span class="skill-tag"><i class="fas fa-tools"></i> Hardware</span>
                                <span class="skill-tag"><i class="fas fa-robot"></i> Robotic</span>
                                <span class="skill-tag"><i class="fas fa-microchip"></i> IoT</span>
                            </div>
                            <span class="dev-card__meta-divider" aria-hidden="true"></span>
                            <div class="dev-card__social" aria-label="Sosial media Muhammad Irfan">
                                <a class="social-link" href="https://instagram.com/0x_irfan" target="_blank" rel="noopener noreferrer" aria-label="Instagram Muhammad Irfan">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="social-link" href="https://github.com/Irfan-LX25" target="_blank" rel="noopener noreferrer" aria-label="GitHub Muhammad Irfan">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a class="social-link" href="https://wa.me/6281291005452" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Muhammad Irfan">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="dev-card__divider" />
                <div class="dev-card__body">
                    <ul class="dev-card__info">
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-id-card"></i></span>
                            <span class="dev-card__info-label">NIM</span>
                            <span class="dev-card__info-value">4.31.24.4.12</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-school"></i></span>
                            <span class="dev-card__info-label">Kelas</span>
                            <span class="dev-card__info-value">TE-2E</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-mobile-screen-button"></i></span>
                            <span class="dev-card__info-label">No HP</span>
                            <span class="dev-card__info-value">0812-9100-5452</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-location-dot"></i></span>
                            <span class="dev-card__info-label">Asal</span>
                            <span class="dev-card__info-value">MAS Muharrikun Najaah</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-laptop-code"></i></span>
                            <span class="dev-card__info-label">Jurusan</span>
                            <span class="dev-card__info-value">MIPA (Matematika dan Ilmu Pengetahuan Alam)</span>
                        </li>
                    </ul>
                </div>
                <blockquote class="dev-card__quote">
                    <span>❝ Mengakui kekalahan adalah sebuah keberanian.</span><br />
                    <span class="dev-card__quote-line" id="quoteTypeTarget" data-text="Sláinte! 🍀" aria-label="Sláinte! 🍀"></span>
                    <span class="typewriter-text typewriter-text--block" id="quoteTypeExtra" aria-label="terminal style lines"></span>
                </blockquote>
            </article>

            <article class="dev-card reveal reveal-delay-2">
                <div class="dev-card__top">
                    <div class="dev-card__avatar-wrap">
                        <img src="assets/img/dev2.png" alt="Foto Developer 2" class="dev-card__avatar" onclick="openPhotoModal(this)" />
                    </div>
                    <div class="dev-card__identity">
                        <h2 class="dev-card__name">Rafa Ferris Bachtiar</h2>
                        <p class="dev-card__role">Telecommunication Engineer</p>
                        <div class="dev-card__meta">
                            <div class="dev-card__skills">
                                <span class="skill-tag"><i class="fas fa-microchip"></i> Embed</span>
                                <span class="skill-tag"><i class="fas fa-wifi"></i> IoT</span>
                                <span class="skill-tag"><i class="fas fa-server"></i> Net</span>
                            </div>
                            <span class="dev-card__meta-divider" aria-hidden="true"></span>
                            <div class="dev-card__social" aria-label="Sosial media Rafa Ferris Bachtiar">
                                <a class="social-link" href="https://instagram.com/fqiihrz" target="_blank" rel="noopener noreferrer" aria-label="Instagram Rafa Ferris Bachtiar">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="social-link" href="https://github.com/Ferris-cmd" target="_blank" rel="noopener noreferrer" aria-label="GitHub Rafa Ferris Bachtiar">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a class="social-link" href="https://wa.me/6281329928588" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Rizky Pratama">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="dev-card__divider" />
                <div class="dev-card__body">
                    <ul class="dev-card__info">
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-id-card"></i></span>
                            <span class="dev-card__info-label">NIM</span>
                            <span class="dev-card__info-value">4.31.24.4.16</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-school"></i></span>
                            <span class="dev-card__info-label">Kelas</span>
                            <span class="dev-card__info-value">TE-2E</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-mobile-screen-button"></i></span>
                            <span class="dev-card__info-label">No HP</span>
                            <span class="dev-card__info-value">0813-2992-8588</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-location-dot"></i></span>
                            <span class="dev-card__info-label">Asal</span>
                            <span class="dev-card__info-value">SMA Negeri 5 Semarang</span>
                        </li>
                        <li>
                            <span class="dev-card__info-icon"><i class="fas fa-laptop-code"></i></span>
                            <span class="dev-card__info-label">Jurusan</span>
                            <span class="dev-card__info-value">MIPA (Matematika dan Ilmu Pengetahuan Alam)</span>
                        </li>
                    </ul>
                </div>
                <blockquote class="dev-card__quote">
                    <span>❝ Ambilah apa yang bisa kamu ambil.</span><br />
                    <span class="dev-card__quote-line" id="quoteTypeTarget2" data-text=" Dont be greedy" aria-label="Build. Break. Repeat. ⚡"></span>
                    <span class="typewriter-text typewriter-text--block" id="quoteTypeExtra2" aria-label="terminal style lines"></span>
                </blockquote>
            </article>

        </section>

        <div class="dev-topbar__copy">Kelompok 2 &mdash; TE24E &copy; 2026</div>

    </div>

    <div id="photoModal" class="photo-modal" aria-hidden="true" onclick="closePhotoModal()">
        <button type="button" class="photo-modal__close" id="photoModalClose" aria-label="Tutup" onclick="closePhotoModal()">&times;</button>
        <div class="photo-modal__content" onclick="event.stopPropagation()">
            <img id="photoModalImage" class="photo-modal__image" alt="" />
            <div id="photoModalCaption" class="photo-modal__caption">cieee diklik suka yaa</div>
        </div>
    </div>

    <script>
    function openPhotoModal(img) {
        const modal = document.getElementById('photoModal');
        const modalImg = document.getElementById('photoModalImage');
        const modalCaption = document.getElementById('photoModalCaption');
        if (!modal || !modalImg || !img) return;
        modalImg.src = img.src;
        modalImg.alt = img.alt || 'Foto';
        if (modalCaption) {
            modalCaption.textContent = 'cieee diklik suka yaa';
        }
        modal.style.display = 'flex';
    }

    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        const modalImg = document.getElementById('photoModalImage');
        if (!modal || !modalImg) return;
        modal.style.display = 'none';
        modalImg.src = '';
    }
    
    // Background Particles Effect Logic Preserved
    (function () {
        const canvas = document.getElementById('networkCanvas');
        const ctx = canvas.getContext('2d');
        let w, h, particles = [];
        const COUNT = 45, MAX_DIST = 130;
        function resize() { w = canvas.width = window.innerWidth; h = canvas.height = window.innerHeight; }
        window.addEventListener('resize', resize); resize();
        for (let i = 0; i < COUNT; i++) particles.push({ x: Math.random()*w, y: Math.random()*h, vx: (Math.random()-0.5)*0.45, vy: (Math.random()-0.5)*0.45, r: Math.random()*2+1 });
        function draw() {
            ctx.clearRect(0,0,w,h);
            particles.forEach(p => { p.x+=p.vx; p.y+=p.vy; if(p.x<0||p.x>w)p.vx*=-1; if(p.y<0||p.y>h)p.vy*=-1; });
            for (let i=0;i<particles.length;i++) for (let j=i+1;j<particles.length;j++) { const dx=particles[i].x-particles[j].x,dy=particles[i].y-particles[j].y,d=Math.sqrt(dx*dx+dy*dy); if(d<MAX_DIST){ctx.strokeStyle=`rgba(0,240,255,${(1-d/MAX_DIST)*0.15})`;ctx.lineWidth=0.7;ctx.beginPath();ctx.moveTo(particles[i].x,particles[i].y);ctx.lineTo(particles[j].x,particles[j].y);ctx.stroke();}}
            particles.forEach(p => { ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2); ctx.fillStyle='rgba(0,240,255,0.2)'; ctx.fill(); });
            requestAnimationFrame(draw);
        }
        draw();
    })();

    // Reveal Trigger Preserved
    (function () {
        document.querySelectorAll('.reveal').forEach((el, i) => { setTimeout(() => el.classList.add('visible'), 120*i); });
    })();

    // Terminal Style Typewriter Execution Preserved Exactly
    (function () {
        function typeTerminalLines(target, lines, speed) {
            let lineIndex = 0;
            function typeLine() {
                if (lineIndex >= lines.length) return;
                const line = lines[lineIndex];
                const lineNode = document.createElement('span');
                lineNode.className = 'terminal-line ' + (line.className || '');
                target.appendChild(lineNode);
                let charIndex = 0;
                function typeChar() {
                    if (charIndex > line.text.length) {
                        lineIndex += 1;
                        typeLine();
                        return;
                    }
                    lineNode.textContent = line.text.slice(0, charIndex);
                    charIndex += 1;
                    setTimeout(typeChar, speed);
                }
                typeChar();
            }
            typeLine();
        }

        function runQuoteTypewriter(primaryId, extraId, lines, delay) {
            const primaryTarget = document.getElementById(primaryId);
            const extraTarget = document.getElementById(extraId);
            if (!primaryTarget) return;
            primaryTarget.textContent = primaryTarget.getAttribute('data-text') || '';
            setTimeout(() => {
                if (extraTarget) {
                    typeTerminalLines(extraTarget, lines, 34);
                }
            }, delay);
        }

        runQuoteTypewriter('quoteTypeTarget', 'quoteTypeExtra', [
            { text: '/mfspayload hati/iman/DiriSendiri LHOST=1.m.4.N LPORT=007 X > ChargerIman.exe', className: 'terminal-line--warn' },
            { text: 'rm -rf MasaLalu', className: 'terminal-line--danger' },
            { text: 'wget MasaDepan', className: 'terminal-line--ok' },
            { text: 'chmod +x MasaDepan', className: 'terminal-line--muted' },
            { text: './MasaDepan [+] Trying Exploit The Future [+]', className: 'terminal-line--success' }
        ], 700);

        runQuoteTypewriter('quoteTypeTarget2', 'quoteTypeExtra2', [
            { text: 'ssh kelompok2@tee24emyid -p 2244', className: 'terminal-line--warn' },
            { text: 'sudo systemctl restart iot-core', className: 'terminal-line--ok' },
            { text: 'git pull origin main', className: 'terminal-line--muted' },
            { text: 'npm run deploy', className: 'terminal-line--success' },
            { text: '[+] Device online. Sync complete.', className: 'terminal-line--ok' }
        ], 1100);
    })();
    </script>
</body>
</html>

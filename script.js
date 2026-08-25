// --- Core Setup & Interactions --- //
document.getElementById('year').textContent = new Date().getFullYear();


const backToTop = document.getElementById("backToTop");
if (backToTop) {
    const updateBackToTop = () => {
        if (window.scrollY > 300) {
            backToTop.classList.add("show");
        } else {
            backToTop.classList.remove("show");
        }
    };
    window.addEventListener("scroll", updateBackToTop, { passive: true });
    backToTop.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
    updateBackToTop();
}

// Theme Toggle
const themeBtn = document.getElementById('themeBtn');
const html = document.documentElement;
const storedTheme = localStorage.getItem('theme');
const moonSvg = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`;
const sunSvg = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`;
if (storedTheme) {
  html.setAttribute('data-theme', storedTheme);
  themeBtn.innerHTML = storedTheme === 'dark' ? moonSvg : sunSvg;
} else {
  localStorage.setItem('theme', 'dark');
}
themeBtn.addEventListener('click', () => {
  const current = html.getAttribute('data-theme');
  const newTheme = current === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', newTheme);
  themeBtn.innerHTML = newTheme === 'dark' ? moonSvg : sunSvg;
  localStorage.setItem('theme', newTheme);
});

// Mobile Nav
const hamburger = document.getElementById('hamburger');
const mobileNav = document.getElementById('mobileNav');

function closeMobile() {
  if(mobileNav) {
    mobileNav.classList.remove('open');
    if(hamburger) {
      hamburger.setAttribute('aria-expanded', 'false');
      hamburger.setAttribute('aria-label', 'Open menu');
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  if (hamburger && mobileNav) {
    hamburger.setAttribute('aria-expanded', 'false');
    hamburger.setAttribute('aria-label', 'Open menu');
    
    hamburger.addEventListener('click', () => {
      const isOpen = mobileNav.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', isOpen);
      hamburger.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
    });
    
    window.addEventListener('resize', () => { if(window.innerWidth > 900) closeMobile(); });
    document.addEventListener('keydown', (e) => { if(e.key === 'Escape' && mobileNav.classList.contains('open')) closeMobile(); });
  }
});

// Scroll Progress & Navbar
window.addEventListener('scroll', () => {
  const nav = document.getElementById('navbar');
  if(window.scrollY > 50) nav.classList.add('scrolled');
  else nav.classList.remove('scrolled');
  
  const scrollTotal = document.documentElement.scrollTop;
  const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const scrollPercent = (scrollTotal / height) * 100;
  document.getElementById('scroll-progress').style.width = scrollPercent + "%";
  const pr = document.getElementById('scroll-progress-right');
  if (pr) pr.style.height = scrollPercent + "%";
  
  // Timeline parallax line
  const timeline = document.querySelector('.timeline');
  if(timeline) {
    const rect = timeline.getBoundingClientRect();
    if(rect.top < window.innerHeight && rect.bottom > 0) {
      let progress = ((window.innerHeight - rect.top) / rect.height) * 100;
      progress = Math.max(0, Math.min(100, progress));
      document.getElementById('timelineProgress').style.height = progress + '%';
    }
  }
});

// Premium Cinematic Loader
window.addEventListener('load', () => {
  const loader = document.getElementById('loader');
  
  // FAILSAFE: Always remove loader after 7000ms if something crashes
  const failsafeTimeout = setTimeout(() => {
    if (loader.style.display !== 'none') {
      loader.classList.add('hide');
      setTimeout(() => {
        loader.style.display = 'none';
        loader.style.pointerEvents = 'none';
        document.documentElement.classList.remove('loading-active');
        document.body.classList.remove('loading-active');
        document.body.style.overflow = '';
        typewriterEffect();
      }, 500);
    }
  }, 7000);
  
  // Elements
  const loaderCenter = document.getElementById('loaderCenter');
  const loaderTitle = document.getElementById('loaderTitle');
  const loaderSubtitle = document.getElementById('loaderSubtitle');
  const loaderProgressWrap = document.getElementById('loaderProgressWrap');
  const loaderPctText = document.getElementById('loaderPctText');
  const loaderPctCircle = document.getElementById('loaderPctCircle');
  const loaderBarFg = document.getElementById('loaderBarFg');
  const loaderBarGlow = document.getElementById('loaderBarGlow');
  const loaderStatusText = document.getElementById('loaderStatusText');
  const loaderReadyText = document.getElementById('loaderReadyText');
  const fragments = document.querySelectorAll('.loader-code-fragment');
  
  // Particles (lightweight)
  const lCanvas = document.getElementById('loader-particles');
  const lCtx = lCanvas.getContext('2d');
  lCanvas.width = window.innerWidth;
  lCanvas.height = window.innerHeight;
  let lParticles = [];
  for(let i=0; i<30; i++) {
    lParticles.push({
      x: Math.random() * lCanvas.width, y: Math.random() * lCanvas.height,
      vx: (Math.random()-0.5)*0.5, vy: (Math.random()-0.5)*0.5,
      size: Math.random()*2+0.5, alpha: Math.random()*0.5+0.1
    });
  }
  let lAnim;
  function drawLParticles() {
    lCtx.clearRect(0,0,lCanvas.width,lCanvas.height);
    lParticles.forEach(p => {
      p.x += p.vx; p.y += p.vy;
      if(p.x < 0) p.x = lCanvas.width; if(p.x > lCanvas.width) p.x = 0;
      if(p.y < 0) p.y = lCanvas.height; if(p.y > lCanvas.height) p.y = 0;
      lCtx.globalAlpha = p.alpha;
      lCtx.fillStyle = '#00f0ff';
      lCtx.beginPath(); lCtx.arc(p.x, p.y, p.size, 0, Math.PI*2); lCtx.fill();
    });
    lAnim = requestAnimationFrame(drawLParticles);
  }
  if(!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    drawLParticles();
  }

  // Timeline Sequence
  const statuses = [
    "INITIALIZING PORTFOLIO...",
    "LOADING EXPERIENCE...",
    "PREPARING PROJECTS...",
    "LOADING SKILLS...",
    "CONNECTING COMPONENTS...",
    "OPTIMIZING INTERFACE..."
  ];
  
  let startTime = Date.now();
  let progress = 0;
  
  // Phase 1: 0.0 - 0.8s -> Logo appears
  requestAnimationFrame(() => {
    loaderCenter.classList.add('active');
  });
  
  // Phase 2: 0.8 - 1.8s -> Title/Subtitle appear
  setTimeout(() => {
    loaderTitle.classList.add('active');
    loaderSubtitle.classList.add('active');
  }, 800);
  
  // Phase 3: 1.8s -> Progress Starts
  setTimeout(() => {
    loaderProgressWrap.classList.add('active');
    let progressInterval = setInterval(() => {
      let elapsed = Date.now() - (startTime + 1800);
      let duration = 2400; // 1.8s to 4.2s (2.4s total for progress)
      progress = Math.min(100, (elapsed / duration) * 100);
      
      // Update DOM
      loaderPctText.innerText = Math.floor(progress) + '%';
      let offset = 176 - (176 * progress / 100);
      loaderPctCircle.style.strokeDashoffset = offset;
      loaderBarFg.style.width = progress + '%';
      loaderBarGlow.style.left = progress + '%';
      
      // Update text
      let statusIdx = Math.floor((progress / 100) * statuses.length);
      if(statusIdx >= statuses.length) statusIdx = statuses.length - 1;
      loaderStatusText.innerText = statuses[statusIdx];
      
      // 3.2s -> show floating code
      if(progress > 50) {
        fragments.forEach(f => f.classList.add('active'));
      }
      
      // 4.2s -> 100%
      if(progress >= 100) {
        clearInterval(progressInterval);
        
        // Phase 4: 4.2 - 4.5s -> Ready (0.3s)
        setTimeout(() => {
          loaderCenter.style.opacity = 0;
          loaderCenter.style.transform = 'scale(0.9)';
          setTimeout(() => {
            loaderReadyText.classList.add('active');
            
            // Phase 5: 4.5 - 5.0s -> Cinematic Reveal (0.5s)
            setTimeout(() => {
              document.querySelector('.loader-bg-aurora').style.background = 'radial-gradient(circle at 50% 50%, rgba(6,182,212, 0.3), transparent 80%)';
              setTimeout(() => {
                loader.classList.add('hide');
                setTimeout(() => {
                  loader.style.display = 'none';
                  document.documentElement.classList.remove('loading-active');
                  document.body.classList.remove('loading-active');
                  cancelAnimationFrame(lAnim);
                  clearTimeout(failsafeTimeout);
                  typewriterEffect();
                }, 500); // 0.5s fade out
              }, 200); // 0.2s glow before fade
            }, 300); // 0.3s showing READY
            
          }, 300); // Wait for center to fade out
        }, 100); // Slight delay after hitting 100%
      }
    }, 30);
  }, 1800);
});

// Particles Background with Connections & Repulsion
const canvas = document.getElementById('particles-canvas');
const ctx = canvas.getContext('2d');
let particles = [];
let mouse = { x: -1000, y: -1000 };

function resizeCanvas() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
window.addEventListener('resize', resizeCanvas);
resizeCanvas();
window.addEventListener('mousemove', (e) => { mouse.x = e.clientX; mouse.y = e.clientY; });

class Particle {
  constructor() {
    this.x = Math.random() * canvas.width;
    this.y = Math.random() * canvas.height;
    this.size = Math.random() * 2 + 1;
    this.baseX = this.x; this.baseY = this.y;
    this.vx = (Math.random() - 0.5) * 0.5;
    this.vy = (Math.random() - 0.5) * 0.5;
  }
  update() {
    this.x += this.vx; this.y += this.vy;
    if (this.x > canvas.width) this.x = 0; if (this.x < 0) this.x = canvas.width;
    if (this.y > canvas.height) this.y = 0; if (this.y < 0) this.y = canvas.height;
    
    // Repulsion
    let dx = mouse.x - this.x; let dy = mouse.y - this.y;
    let dist = Math.sqrt(dx*dx + dy*dy);
    if(dist < 100) {
      let force = (100 - dist) / 100;
      this.x -= (dx/dist) * force * 5;
      this.y -= (dy/dist) * force * 5;
    }
  }
  draw() {
    ctx.fillStyle = 'rgba(6,182,212, 0.3)';
    ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill();
  }
}

function initParticles() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  for (let i = 0; i < 80; i++) particles.push(new Particle());
}
function animateParticles() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  
  for (let i = 0; i < particles.length; i++) {
    particles[i].update();
    particles[i].draw();
    
    for(let j=i; j<particles.length; j++) {
      let dx = particles[i].x - particles[j].x;
      let dy = particles[i].y - particles[j].y;
      let dist = Math.sqrt(dx*dx + dy*dy);
      
      // Connection lines
      if(dist < 120) {
        ctx.beginPath();
        ctx.strokeStyle = `rgba(139,92,246, ${0.1 - dist/1200})`;
        ctx.lineWidth = 1;
        ctx.moveTo(particles[i].x, particles[i].y);
        ctx.lineTo(particles[j].x, particles[j].y);
        ctx.stroke();
      }
    }
  }
  requestAnimationFrame(animateParticles);
}
initParticles();
animateParticles();

// Custom Cursor Trail
const cursorTrail = document.getElementById('cursor-trail');
const cursorDot = document.getElementById('cursor-dot');
let ringX = window.innerWidth/2, ringY = window.innerHeight/2;

function animateRing() {
  ringX += (mouse.x - ringX) * 0.15;
  ringY += (mouse.y - ringY) * 0.15;
  cursorTrail.style.transform = `translate(${ringX}px, ${ringY}px) translate(-50%, -50%)`;
  cursorDot.style.transform = `translate(${mouse.x}px, ${mouse.y}px) translate(-50%, -50%)`;
  requestAnimationFrame(animateRing);
}
if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) { animateRing(); }

document.querySelectorAll('a, button, input, textarea, .project-card, .skill-card').forEach(el => {
  el.addEventListener('mouseenter', () => cursorTrail.classList.add('hovered'));
  el.addEventListener('mouseleave', () => cursorTrail.classList.remove('hovered'));
});

// Magnetic Buttons & Ripple
document.querySelectorAll('.magnetic-btn').forEach(btn => {
  btn.addEventListener('mousemove', function(e) {
    const position = btn.getBoundingClientRect();
    const x = e.pageX - position.left - position.width / 2;
    const y = e.pageY - position.top - position.height / 2;
    btn.style.transform = `translate(${x * 0.3}px, ${y * 0.5}px)`;
  });
  btn.addEventListener('mouseout', function() { btn.style.transform = 'translate(0px, 0px)'; });
});

document.querySelectorAll('.btn-primary').forEach(btn => {
  btn.addEventListener('click', function(e) {
    let x = e.clientX - e.target.getBoundingClientRect().left;
    let y = e.clientY - e.target.getBoundingClientRect().top;
    let ripple = document.createElement('span');
    ripple.style.left = `${x}px`; ripple.style.top = `${y}px`;
    ripple.classList.add('ripple');
    this.appendChild(ripple);
    setTimeout(() => { ripple.remove(); }, 600);
  });
});

// 3D Tilt Effect on Cards
document.querySelectorAll('.project-card, .service-card, .about-card, .cert-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    const rotateX = ((y - centerY) / centerY) * -10; 
    const rotateY = ((x - centerX) / centerX) * 10;
    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
  });
});

// Web3Forms Setup + Confetti
const form = document.getElementById('contactForm');
const formMsg = document.getElementById('formMsg');
const submitBtn = document.getElementById('submitBtn');

form.addEventListener('submit', async function(e) {
  e.preventDefault();
  submitBtn.innerHTML = 'Sending... ⏳';
  submitBtn.disabled = true;

  const formData = new FormData(form);
  const json = JSON.stringify(Object.fromEntries(formData));

  try {
    const response = await fetch('https://api.web3forms.com/submit', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: json
    });
    const result = await response.json();
    
    if (response.status == 200) {
      formMsg.textContent = "Message sent successfully! I'll get back to you soon.";
      formMsg.className = 'form-msg success';
      form.reset();
      
      // Confetti burst
      for(let i=0; i<30; i++){
        let c = document.createElement('div');
        c.className = 'confetti';
        c.style.left = Math.random() * 100 + 'vw';
        c.style.top = -10 + 'px';
        c.style.background = ['#00f0ff', '#7b2ff7', '#ff2d75', '#00e676'][Math.floor(Math.random()*4)];
        c.style.animationDuration = Math.random() * 2 + 2 + 's';
        document.body.appendChild(c);
        setTimeout(()=>c.remove(), 4000);
      }
    } else {
      formMsg.textContent = result.message || "Failed to send message.";
      formMsg.className = 'form-msg error';
    }
  } catch (error) {
    formMsg.textContent = "Something went wrong! Please try again later.";
    formMsg.className = 'form-msg error';
  } finally {
    submitBtn.innerHTML = 'Send Message <span>🚀</span>';
    submitBtn.disabled = false;
  }
});

// Project Filtering
function filterProjects(cat, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.project-card').forEach(card => {
    if(cat === 'all' || card.getAttribute('data-cat') === cat) {
      card.style.display = 'block';
      setTimeout(() => { card.style.opacity = '1'; card.style.transform = 'scale3d(1,1,1)'; }, 10);
    } else {
      card.style.opacity = '0'; card.style.transform = 'scale3d(0.8,0.8,0.8)';
      setTimeout(() => { card.style.display = 'none'; }, 300);
    }
  });
}

// Reveal on Scroll & Counters
const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      if(entry.target.classList.contains('stats-bar')) {
        document.querySelectorAll('.stat-num[data-count]').forEach(el => {
          const target = parseInt(el.getAttribute('data-count'));
          animateValue(el, 0, target, 2000);
          el.removeAttribute('data-count');
        });
      }
    }
  });
}, observerOptions);

document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .stats-bar').forEach(el => observer.observe(el));

// Spring Counter Animation
function animateValue(obj, start, end, duration) {
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 3); // easeOutCubic
    obj.innerHTML = Math.floor(easeProgress * (end - start) + start) + (end === 3 || end === 15 ? "+" : "");
    if (progress < 1) window.requestAnimationFrame(step);
    else obj.innerHTML = end + (end === 3 || end === 15 ? "+" : ""); 
  };
  window.requestAnimationFrame(step);
}

// Typewriter
const roles = ["Full Stack Developer", "Problem Solver", "Tech Enthusiast"];
let roleIndex = 0; let charIndex = 0; let isDeleting = false;
const typewriterEl = document.getElementById('typewriter');

function typewriterEffect() {
  const currentRole = roles[roleIndex];
  if (isDeleting) { typewriterEl.textContent = currentRole.substring(0, charIndex - 1); charIndex--; }
  else { typewriterEl.textContent = currentRole.substring(0, charIndex + 1); charIndex++; }
  let typeSpeed = isDeleting ? 30 : 80;
  if (!isDeleting && charIndex === currentRole.length) { typeSpeed = 2000; isDeleting = true; }
  else if (isDeleting && charIndex === 0) { isDeleting = false; roleIndex = (roleIndex + 1) % roles.length; typeSpeed = 500; }
  setTimeout(typewriterEffect, typeSpeed);
}

// --- Chatbot Logic (NLP Local Knowledge System) --- //
const portfolioKnowledge = {
  identity: "Vivek Kariya is an early-career BCA graduate and Full Stack Developer based in Gujarat. His portfolio focuses on hands-on project experience across web applications, desktop software, databases and AI-enabled solutions.",
  education: "Vivek is currently pursuing an MCA (2026). He holds a BCA degree (79.82%) from Kamani Science College and achieved a stellar 96.34 percentile in his HSC.",
  skills: "Vivek's core technical stack includes:<br>• <b>Frontend:</b> HTML/CSS, JS, React, Next.js<br>• <b>Backend:</b> PHP, Python, Node.js<br>• <b>Databases:</b> MySQL, SQLite, Supabase<br>• <b>Tools:</b> Git, VS Code, Figma<br>He is comfortable working across the entire stack.",
  projects: "Vivek has hands-on project experience building complete applications. Notable projects include:<br>• <b>Craftzon:</b> A multi-vendor e-commerce marketplace (PHP/MySQL)<br>• <b>Shakti POS:</b> Offline-first desktop inventory software with cloud sync (Python/SQLite)<br>• <b>Teacher Learning Platform:</b> An educational platform (Next.js)",
  services: "Vivek provides practical development services including:<br>• Full-stack web applications<br>• E-commerce platforms<br>• Admin dashboards<br>• POS & inventory systems<br>• Educational platforms<br>• AI integrations",
  hiring: "Vivek is an early-career developer actively looking for opportunities where he can contribute and grow. He is available for freelance projects, internships, and full-time roles.",
  contact: "You can reach Vivek directly:<br>• <b>Email:</b> vivekkariya22@gmail.com<br>• <b>Phone/WhatsApp:</b> +91 9428531640<br>Or connect with him on LinkedIn and GitHub via the links in the Contact section."
};

const synonymMaps = {
  identity: ["who", "about", "profile", "summary", "introduce", "developer", "fresher", "vivek"],
  education: ["study", "qualification", "bca", "mca", "college", "degree", "percentage", "education"],
  skills: ["skill", "skills", "technologies", "stack", "know", "react", "php", "python", "mysql", "frontend", "backend", "database", "tools", "expertise"],
  projects: ["project", "projects", "build", "built", "craftzon", "shakti", "application", "portfolio", "created", "developed"],
  services: ["service", "services", "offer", "ecommerce", "website", "dashboard"],
  hiring: ["hire", "hiring", "job", "work", "available", "internship", "full-time", "freelance", "opportunity", "recruitment", "experienced", "senior", "role"],
  contact: ["contact", "email", "phone", "whatsapp", "linkedin", "reach", "connect"]
};

// State
const chatWindow = document.getElementById('chatWindow');
const chatBadge = document.getElementById('chatBadge');
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
let chatOpen = false;

function toggleChat() {
  chatOpen = !chatOpen;
  if(chatOpen) { 
    chatWindow.classList.remove('hidden'); 
    chatBadge.style.display = 'none'; 
    chatInput.focus(); 
  } else {
    chatWindow.classList.add('hidden');
  }
}

// Close on escape
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && chatOpen) toggleChat();
});

function askQuick(q) { chatInput.value = q; sendChat(); }

function sendChat() {
  const rawText = chatInput.value.trim();
  if(!rawText) return;
  
  const uMsg = document.createElement('div'); uMsg.className = 'chat-msg user';
  uMsg.innerHTML = `<div class="chat-msg-avatar">👤</div><div class="chat-bubble">${escapeHTML(rawText)}</div>`;
  chatMessages.appendChild(uMsg); chatInput.value = ''; scrollToBottom();

  const typing = document.createElement('div'); typing.className = 'chat-msg bot'; typing.id = 'typingIndicator';
  typing.innerHTML = `<div class="chat-msg-avatar">🤖</div><div class="chat-typing"><span></span><span></span><span></span></div>`;
  chatMessages.appendChild(typing); scrollToBottom();

  setTimeout(() => {
    const indicator = document.getElementById('typingIndicator');
    if(indicator) indicator.remove();
    
    const reply = generateNLPReply(rawText);
    const bMsg = document.createElement('div'); bMsg.className = 'chat-msg bot';
    bMsg.innerHTML = `<div class="chat-msg-avatar">🤖</div><div class="chat-bubble">${reply}</div>`;
    chatMessages.appendChild(bMsg); scrollToBottom();
  }, 700 + Math.random() * 400); // Human-like typing delay
}

function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }

function escapeHTML(str) { 
  return str.replace(/[&<>'"]/g, tag => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[tag]||tag)); 
}

function generateNLPReply(rawQuery) {
  // 1. Text Normalization
  const q = rawQuery.toLowerCase().replace(/[^\w\s-]/gi, '');
  const tokens = q.split(/\s+/);
  
  // 2. Intent Scoring
  let scores = { identity: 0, education: 0, skills: 0, projects: 0, services: 0, hiring: 0, contact: 0 };
  
  for (const token of tokens) {
    if(token.length < 2) continue; // skip small words
    for (const [intent, keywords] of Object.entries(synonymMaps)) {
      if (keywords.includes(token)) {
        scores[intent] += 1.0;
      }
      // Partial match weight
      else if (token.length > 3 && keywords.some(k => k.includes(token) || token.includes(k))) {
        scores[intent] += 0.5;
      }
    }
  }

  // 3. Special tricky question overrides
  if(q.includes('senior') || q.includes('years experience') || q.includes('how many years')) {
    return "Vivek is positioned as an early-career developer rather than a senior engineer. His strength is hands-on project development and a willingness to learn. For entry-level/junior roles, his portfolio demonstrates practical, production-oriented ability.";
  }

  // 4. Find highest intent
  let maxIntent = '';
  let maxScore = 0;
  for (const [intent, score] of Object.entries(scores)) {
    if (score > maxScore) {
      maxScore = score;
      maxIntent = intent;
    }
  }

  // 5. Respond or Fallback
  if (maxScore >= 0.5 && portfolioKnowledge[maxIntent]) {
    return portfolioKnowledge[maxIntent];
  } else {
    return "I don't have that specific information in Vivek's portfolio, so I don't want to guess. You can <a href='#contact' onclick='toggleChat()' style='color:var(--primary);font-weight:600;'>contact Vivek directly</a> for the most accurate answer.";
  }
}

// Three.js WebGL Hero Integration
if (typeof THREE !== 'undefined') {
  const tCanvas = document.getElementById('three-canvas');
  if (tCanvas) {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas: tCanvas, alpha: true, antialias: true });
    
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    
    const particlesGeometry = new THREE.BufferGeometry();
    const particlesCount = 700;
    const posArray = new Float32Array(particlesCount * 3);
    for(let i=0; i < particlesCount * 3; i++) {
      posArray[i] = (Math.random() - 0.5) * 10;
    }
    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const material = new THREE.PointsMaterial({ size: 0.02, color: 0x06b6d4, transparent: true, opacity: 0.8 });
    const particlesMesh = new THREE.Points(particlesGeometry, material);
    scene.add(particlesMesh);
    
    const geometry = new THREE.IcosahedronGeometry(1.5, 1);
    const wireMaterial = new THREE.MeshBasicMaterial({ color: 0x8b5cf6, wireframe: true, transparent: true, opacity: 0.15 });
    const wireSphere = new THREE.Mesh(geometry, wireMaterial);
    scene.add(wireSphere);
    
    camera.position.z = 3;
    
    let mouseX = 0; let mouseY = 0;
    document.addEventListener('mousemove', (event) => {
      mouseX = (event.clientX / window.innerWidth) * 2 - 1;
      mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
    });

    const clock = new THREE.Clock();
    const animateThree = function () {
      requestAnimationFrame(animateThree);
      const elapsedTime = clock.getElapsedTime();
      
      particlesMesh.rotation.y = mouseX * 0.5 + elapsedTime * 0.05;
      particlesMesh.rotation.x = mouseY * 0.5 + elapsedTime * 0.02;
      
      wireSphere.rotation.y = elapsedTime * 0.1;
      wireSphere.rotation.x = elapsedTime * 0.05;
      
      renderer.render(scene, camera);
    };
    animateThree();
    
    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
  }
}

// --- CAROUSEL LOGIC ---
const certificateData = [
    {
        "title": "Business Etiquette",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/BusinessEtiquette_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Learned professional conduct, workplace norms, and effective interpersonal behaviors for business environments.",
        "link": "#"
    },
    {
        "title": "Are your Career Ready.",
        "issuer": "StackDot / StackCode Training Institute",
        "image": "./assets/certificates/certificate-Kariya-Vivek_page-0001.jpg",
        "category": "Completed",
        "description": "Successfully completed a technology career quiz assessing industry trends and in-demand skills.",
        "link": "#"
    },
    {
        "title": "Career Essentials in Generative AI",
        "issuer": "Microsoft and LinkedIn",
        "image": "./assets/certificates/CertificateOfCompletion_Career Essentials in Generative AI by Microsoft and LinkedIn_page-0001.jpg",
        "category": "Completed",
        "description": "Mastered foundational generative AI concepts, ethical considerations, and practical applications in the workplace.",
        "link": "#"
    },
    {
        "title": "Ethics in the Age of Generative AI",
        "issuer": "LinkedIn",
        "image": "./assets/certificates/CertificateOfCompletion_Ethics in the Age of Generative AI_page-0001.jpg",
        "category": "Completed",
        "description": "Explored the ethical implications, biases, and responsible usage guidelines for deploying generative AI.",
        "link": "#"
    },
    {
        "title": "Everyday AI Concepts",
        "issuer": "LinkedIn",
        "image": "./assets/certificates/CertificateOfCompletion_Everyday AI Concepts_page-0001.jpg",
        "category": "Completed",
        "description": "Gained a solid understanding of how artificial intelligence is integrated into daily technologies and its societal impact.",
        "link": "#"
    },
    {
        "title": "Learning Microsoft 365 Copilot for Work",
        "issuer": "LinkedIn",
        "image": "./assets/certificates/CertificateOfCompletion_Learning Microsoft 365 Copilot for Work_page-0001.jpg",
        "category": "Completed",
        "description": "Learned to leverage Microsoft 365 Copilot to streamline workflows, enhance productivity, and automate tasks.",
        "link": "#"
    },
    {
        "title": "What Is Generative AI?",
        "issuer": "LinkedIn",
        "image": "./assets/certificates/CertificateOfCompletion_What Is Generative AI (1)_page-0001.jpg",
        "category": "Completed",
        "description": "Developed a comprehensive understanding of generative AI mechanics, models, and its transformative potential.",
        "link": "#"
    },
    {
        "title": "AI Literacy for Everyone",
        "issuer": "LinkedIn",
        "image": "./assets/certificates/CertificateOfCompletion_Your Top AI Questions Answered AI Literacy for Everyone_page-0001.jpg",
        "category": "Completed",
        "description": "Acquired essential AI literacy by addressing common questions and misconceptions about artificial intelligence.",
        "link": "#"
    },
    {
        "title": "Communication Skills",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/CommunicationSkills_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Enhanced verbal and non-verbal communication techniques essential for professional workplace interactions.",
        "link": "#"
    },
    {
        "title": "Cybersecurity Job Simulation",
        "issuer": "Forage (Mastercard)",
        "image": "./assets/certificates/cyber security_page-0001.jpg",
        "category": "Completed",
        "description": "Engaged in simulated cybersecurity scenarios, focusing on phishing email design and interpreting simulation results.",
        "link": "#"
    },
    {
        "title": "Email Etiquette",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/EmailEtiquette_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Mastered professional email communication, focusing on clarity, tone, and appropriate structuring of business correspondence.",
        "link": "#"
    },
    {
        "title": "GenAI Job Simulation",
        "issuer": "Forage (BCG X)",
        "image": "./assets/certificates/gabev3vXhuACr48eb_SKZxezskWgmFjRvj9_dcQpxNRJ76iwP29Cm_1735999627044_completion_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Performed data extraction, initial analysis, and developed an AI-powered financial chatbot.",
        "link": "#"
    },
    {
        "title": "Gemini Certified Student",
        "issuer": "Google for Education",
        "image": "./assets/certificates/geminicertifcate.jpg",
        "category": "Completed",
        "description": "Demonstrated knowledge, skills, and basic competencies needed to use Google AI technologies effectively.",
        "link": "#"
    },
    {
        "title": "Cybersecurity Analyst Job Simulation",
        "issuer": "Forage (TATA)",
        "image": "./assets/certificates/gmf3ypEXBj2wvfQWC_ifobHAoMjQs9s6bKS_dcQpxNRJ76iwP29Cm_1738762072152_completion_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Completed practical tasks in Identity and Access Management (IAM) fundamentals and custom IAM solutions.",
        "link": "#"
    },
    {
        "title": "Group Discussion",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/groupdiscussion certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Learned effective group discussion formats, do's and don'ts, and participated in mock group discussions.",
        "link": "#"
    },
    {
        "title": "Interview Skills",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/Interviewskills_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Developed effective techniques for presenting professional experience, handling questions, and succeeding in job interviews.",
        "link": "#"
    },
    {
        "title": "Introduction to Soft Skills",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/IntroductiontoSoftSkills_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Learned fundamental soft skills including teamwork, adaptability, and problem-solving required in corporate environments.",
        "link": "#"
    },
    {
        "title": "Climate & Sustainability Job Simulation",
        "issuer": "Forage (BCG)",
        "image": "./assets/certificates/Pbk5QSgfrKRrsTtYw_SKZxezskWgmFjRvj9_dcQpxNRJ76iwP29Cm_1735997337014_completion_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Completed tasks in climate benchmarking, emissions baselining, and sustainability strategy development.",
        "link": "#"
    },
    {
        "title": "Presentation Skills",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/Presentationskills_certificates_page-0001.jpg",
        "category": "Completed",
        "description": "Improved abilities in creating engaging content and delivering effective professional presentations.",
        "link": "#"
    },
    {
        "title": "Introduction to Prompt Engineering",
        "issuer": "Simplilearn",
        "image": "./assets/certificates/Simplilearn Certificate (1)_page-0001.jpg",
        "category": "Completed",
        "description": "Learned fundamental techniques for crafting effective prompts to interact optimally with AI models.",
        "link": "#"
    },
    {
        "title": "Generative AI for Beginners",
        "issuer": "Simplilearn",
        "image": "./assets/certificates/Simplilearn Certificate (2)_page-0001.jpg",
        "category": "Completed",
        "description": "Explored the basics of Generative AI, prompting techniques, and practical applications of AI tools.",
        "link": "#"
    },
    {
        "title": "Gemini for Google Workspace",
        "issuer": "Google Cloud / Simplilearn",
        "image": "./assets/certificates/Simplilearn Certificate (3)_page-0001.jpg",
        "category": "Completed",
        "description": "Gained expertise in utilizing Google's Gemini AI to enhance productivity across Google Workspace applications.",
        "link": "#"
    },
    {
        "title": "Introduction to Generative AI Studio",
        "issuer": "Google Cloud / Simplilearn",
        "image": "./assets/certificates/Simplilearn Certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Learned to prototype and customize generative AI models using Google Cloud's Generative AI Studio.",
        "link": "#"
    },
    {
        "title": "Data Science Job Simulation",
        "issuer": "Forage (BCG X)",
        "image": "./assets/certificates/Tcz8gTtprzAS4xSoK_SKZxezskWgmFjRvj9_dcQpxNRJ76iwP29Cm_1735828255249_completion_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Completed practical tasks in data analysis, modeling, and client communication simulated from real BCG X projects.",
        "link": "#"
    },
    {
        "title": "Telephone Etiquette",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/TelephoneEtiquette_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Developed professional telephone communication skills, focusing on active listening and clear articulation.",
        "link": "#"
    },
    {
        "title": "Strategy Consulting Job Simulation",
        "issuer": "Forage (BCG)",
        "image": "./assets/certificates/vk_page-0001.jpg",
        "category": "Completed",
        "description": "Completed tasks in market research, data analysis modeling, and formulating client recommendations.",
        "link": "#"
    },
    {
        "title": "Write Effective Resume and Cover letter",
        "issuer": "TCS iON / Tata Consultancy Services",
        "image": "./assets/certificates/WriteEffectiveResumeandCoverletter_certificate_page-0001.jpg",
        "category": "Completed",
        "description": "Mastered the art of creating impactful resumes and persuasive cover letters for job applications.",
        "link": "#"
    }
];

const projectData = [
    {
        "id": "craftzon",
        "category": "web",
        "title": "CraftZon - Multi-Vendor Handicraft Marketplace",
        "desc": "A robust full-stack, multi-vendor e-commerce platform bridging artisans and consumers. Features include dynamic shopping carts, custom seller storefronts with Chart.js analytics, automated order/return processing, and an interactive NLP chatbot. Built from scratch emphasizing secure session handling, role-based access control, and MVC-inspired backend architecture.",
        "image": "./assets/projects/craftzonlogo.jpeg",
        "tech": [
            "PHP",
            "MySQL",
            "JavaScript",
            "HTML5",
            "CSS3",
            "AJAX",
            "Chart.js",
            "SweetAlert"
        ],
        "liveUrl": "http://craftzon.infinityfree.io",
        "githubUrl": null,
        "reportUrl": null
    },
    {
        "id": "shaktipos",
        "category": "desktop",
        "title": "Shakti POS & Inventory",
        "desc": "Desktop Point of Sale and Inventory Management Software with real-time cloud sync. Features offline capability via SQLite and cloud backup through Supabase.",
        "image": "./assets/projects/shakti_logo.png",
        "tech": [
            "Python",
            "Kivy",
            "SQLite",
            "Supabase"
        ],
        "liveUrl": null,
        "githubUrl": null,
        "reportUrl": "https://drive.google.com/file/d/1tb9yIRSuUpRTutgPDv6rd2baLuQdTWxq/view?usp=drivesdk"
    },
    {
        "id": "teacher-platform",
        "category": "web",
        "title": "Teacher Learning Platform",
        "desc": "A full-featured educational platform built with Next.js + Supabase. Teachers upload PDFs, manage courses, and share video links. Students track progress.",
        "image": "./assets/projects/mhakalaccadmylogo.jpeg",
        "tech": [
            "Next.js",
            "Supabase",
            "Google Drive API"
        ],
        "liveUrl": null,
        "githubUrl": null,
        "reportUrl": null
    },
    {
        "id": "ai-pdf",
        "category": "future",
        "title": "AI PDF Translator & Editor",
        "desc": "An AI-powered PDF tool that translates, summarizes, and edits PDFs while preserving layout. Multi-language translation, OCR, and smart AI summaries.",
        "image": "./assets/projects/ai-pdf.jpg",
        "tech": [
            "Next.js",
            "AI APIs",
            "Python"
        ],
        "liveUrl": null,
        "githubUrl": null,
        "reportUrl": null
    }
];

class PremiumCarousel {
  constructor(containerId, data, renderFn) {
    this.container = document.getElementById(containerId);
    if(!this.container) return;
    this.data = data;
    this.renderFn = renderFn;
    this.currentIndex = 0;
    
    this.track = this.container.querySelector('.carousel-track');
    this.dotsWrap = this.container.querySelector('.carousel-dots');
    this.prevBtn = this.container.querySelector('.carousel-prev');
    this.nextBtn = this.container.querySelector('.carousel-next');
    
    this.prevBtn.addEventListener('click', () => this.prev());
    this.nextBtn.addEventListener('click', () => this.next());
    
    this.initTouch();
    this.render();
  }
  
  updateData(newData) {
    this.data = newData;
    this.currentIndex = 0;
    this.render();
  }
  
  render() {
    this.track.innerHTML = '';
    this.dotsWrap.innerHTML = '';
    
    if(this.data.length === 0) {
      this.track.innerHTML = '<div style="padding:40px;text-align:center;">No items found.</div>';
      return;
    }
    
    this.data.forEach((item, index) => {
      // Slide
      const slide = document.createElement('div');
      slide.className = `carousel-slide ${index === this.currentIndex ? 'active' : ''}`;
      slide.innerHTML = this.renderFn(item, index);
      this.track.appendChild(slide);
      
      // Dot
      const dot = document.createElement('div');
      dot.className = `carousel-dot ${index === this.currentIndex ? 'active' : ''}`;
      dot.addEventListener('click', () => this.goTo(index));
      this.dotsWrap.appendChild(dot);
    });
    
    this.updateTransform();
  }
  
  goTo(index) {
    if(index < 0) index = this.data.length - 1;
    if(index >= this.data.length) index = 0;
    this.currentIndex = index;
    
    Array.from(this.track.children).forEach((slide, i) => {
      slide.classList.toggle('active', i === this.currentIndex);
    });
    Array.from(this.dotsWrap.children).forEach((dot, i) => {
      dot.classList.toggle('active', i === this.currentIndex);
    });
    
    this.updateTransform();
  }
  
  next() { this.goTo(this.currentIndex + 1); }
  prev() { this.goTo(this.currentIndex - 1); }
  
  updateTransform() {
    this.track.style.transform = `translateX(-${this.currentIndex * 100}%)`;
  }
  
  initTouch() {
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    
    this.track.addEventListener('touchstart', e => {
      startX = e.touches[0].clientX;
      isDragging = true;
      this.track.style.transition = 'none';
    }, {passive: true});
    
    this.track.addEventListener('touchmove', e => {
      if(!isDragging) return;
      currentX = e.touches[0].clientX;
      const diff = currentX - startX;
      // Allow slight visual dragging
      const base = -this.currentIndex * this.track.offsetWidth;
      this.track.style.transform = `translateX(${base + diff}px)`;
    }, {passive: true});
    
    this.track.addEventListener('touchend', e => {
      isDragging = false;
      this.track.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
      const diff = currentX - startX;
      if(currentX !== 0) {
        if(diff > 50) this.prev();
        else if(diff < -50) this.next();
        else this.updateTransform();
      } else {
        this.updateTransform();
      }
      currentX = 0;
    });
  }
}

// Lightbox
const lightbox = document.createElement('div');
lightbox.id = 'lightbox';
lightbox.innerHTML = `
  <button id="lightbox-close" aria-label="Close">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
  </button>
  <img id="lightbox-img" src="" alt="Certificate Full View">
`;
document.body.appendChild(lightbox);
const lightboxImg = document.getElementById('lightbox-img');
const closeLightbox = () => lightbox.classList.remove('open');
document.getElementById('lightbox-close').addEventListener('click', closeLightbox);
lightbox.addEventListener('click', (e) => { if(e.target === lightbox) closeLightbox(); });
document.addEventListener('keydown', (e) => {
  if(e.key === 'Escape' && lightbox.classList.contains('open')) closeLightbox();
  // Keyboard nav
  if(e.key === 'ArrowLeft' && !lightbox.classList.contains('open')) {
    if(window.certCarousel) window.certCarousel.prev();
    if(window.projCarousel) window.projCarousel.prev(); // Or base it on scroll position
  }
  if(e.key === 'ArrowRight' && !lightbox.classList.contains('open')) {
    if(window.certCarousel) window.certCarousel.next();
    if(window.projCarousel) window.projCarousel.next();
  }
});

function openLightbox(src) {
  lightboxImg.src = src;
  lightbox.classList.add('open');
}

// Initializers
document.addEventListener('DOMContentLoaded', () => {
  // Certs
  window.certCarousel = new PremiumCarousel('certCarouselWrap', certificateData, (item, i) => `
    <div class="premium-card">
      <img src="${encodeURI(item.image)}" alt="${item.title}" class="premium-card-image" loading="${i===0?'eager':'lazy'}" decoding="async" onerror="console.error('Missing image:', this.src);">
      <div class="premium-card-body">
        <div class="premium-card-title">${item.title}</div>
        <div class="premium-card-subtitle">${item.issuer}</div>
        <button class="premium-btn" onclick="openLightbox('${item.image}')">
          View Details
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </button>
      </div>
    </div>
  `);

  // Projects
  window.projCarousel = new PremiumCarousel('projCarouselWrap', projectData, (item, i) => `
    <div class="premium-card">
      <img src="${encodeURI(item.image)}" alt="${item.title}" class="premium-card-image" loading="${i===0?'eager':'lazy'}" decoding="async" onerror="console.error('Missing image:', this.src);">
      <div class="premium-card-body">
        <div class="premium-card-title">${item.title}</div>
        <div class="premium-card-subtitle">${item.desc}</div>
        <div class="premium-card-tech">
          ${item.tech.map(t => `<span class="tech-pill">${t}</span>`).join('')}
        </div>
        <div style="display:flex; gap: 12px; flex-wrap:wrap;">
          ${item.liveUrl ? `<a href="${item.liveUrl}" target="_blank" rel="noopener" class="premium-btn">Live Demo <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a>` : ''}
          ${item.githubUrl ? `<a href="${item.githubUrl}" target="_blank" rel="noopener" class="premium-btn">GitHub</a>` : ''}
          ${item.reportUrl ? `<a href="${item.reportUrl}" target="_blank" rel="noopener" class="premium-btn">Project Report</a>` : ''}
          ${(!item.liveUrl && !item.githubUrl && !item.reportUrl) ? `<span style="font-size:14px;color:var(--primary);font-weight:600;">In Development</span>` : ''}
        </div>
      </div>
    </div>
  `);
  
  // Override filterProjects globally
  window.filterProjects = function(category, btn) {
    document.querySelectorAll('.projects-filter .filter-btn').forEach(b => b.classList.remove('active'));
    if(btn) btn.classList.add('active');
    
    if(category === 'all') {
      window.projCarousel.updateData(projectData);
    } else {
      window.projCarousel.updateData(projectData.filter(p => p.category === category));
    }
  };
});

// Register Service Worker for image caching
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('./sw.js')
      .then(reg => console.log('SW registered'))
      .catch(err => console.log('SW registration failed'));
  });
}


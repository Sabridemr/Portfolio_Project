
/* ── Navigation ────────────────────────────────────────────── */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('shadow-lg', 'shadow-accent/5');
    } else {
        navbar.classList.remove('shadow-lg', 'shadow-accent/5');
    }
});

/* ── Mobile Menu ───────────────────────────────────────────── */
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');
const menuLine1 = document.getElementById('menuLine1');
const menuLine2 = document.getElementById('menuLine2');

mobileMenuBtn?.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
    const isOpen = !mobileMenu.classList.contains('hidden');
    menuLine1.style.transform = isOpen ? 'rotate(45deg) translate(3px, 3px)' : '';
    menuLine2.style.transform = isOpen ? 'rotate(-45deg) translate(1px, -1px)' : '';
});

document.querySelectorAll('#mobileMenu a').forEach(link => {
    link.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        menuLine1.style.transform = '';
        menuLine2.style.transform = '';
    });
});


/* ── Scroll Reveal ─────────────────────────────────────────── */
const revealElements = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

revealElements.forEach(el => revealObserver.observe(el));

/* ── Skill Bars Animation ────────────────────────────────── */
const skillsSection = document.getElementById('skills');
let skillsAnimated = false;

const skillsObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && !skillsAnimated) {
        skillsAnimated = true;
        document.querySelectorAll('.skill-fill').forEach(bar => {
            const width = bar.dataset.width;
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 200);
        });
    }
}, { threshold: 0.3 });

skillsObserver.observe(skillsSection);

/* ── Projects — AJAX from PHP/MySQL ────────────────────────── */
let projects = [];
let currentFilter = 'all';

const staticProjects = [
    { title:'E-Commerce Platform',     description:'Full-stack e-commerce with Node.js backend and React frontend', tech:'Node.js, Express, React, TypeScript', category:'fullstack', github:'https://github.com/Sabridemr/EccomerceServer',            live:'https://github.com/Sabridemr/EccomerceClient', status:'live', image:'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop' },
    { title:'Web Application',          description:'Responsive web app built with semantic HTML5, CSS3 and JavaScript',  tech:'HTML5, CSS3, JavaScript',          category:'frontend',  github:'https://github.com/Sabridemr/web-app',                   live:'#',                                            status:'live', image:'https://images.unsplash.com/photo-1547658719-da2b51169166?w=600&h=400&fit=crop' },
    { title:'Crypto Tracker',           description:'Native iOS crypto tracking app with live price data and charts',     tech:'Swift, SwiftUI, CoinGecko API',    category:'frontend',  github:'https://github.com/Sabridemr/CryptoListSwiftUI',          live:'#',                                            status:'live', image:'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=600&h=400&fit=crop' },
    { title:'AI Chatbot App',           description:'Conversational AI chatbot powered by LangChain and OpenAI',          tech:'Python, LangChain, OpenAI, Flask', category:'fullstack', github:'https://github.com/Sabridemr/chatbot-app',                live:'#',                                            status:'live', image:'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=600&h=400&fit=crop' },
    { title:'LangChain Vector Store',   description:'Document Q&A using vector embeddings and semantic search (RAG)',     tech:'Python, LangChain, FAISS, OpenAI', category:'backend',   github:'https://github.com/Sabridemr/VectorStoreProject',         live:'#',                                            status:'live', image:'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=400&fit=crop' },
    { title:'N-Layered Architecture',   description:'Enterprise .NET Web API with clean N-Layered architecture & EF Core', tech:'C#, .NET, Entity Framework Core', category:'backend',   github:'https://github.com/Sabridemr/NLayeredArthitecture',       live:'#',                                            status:'live', image:'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=600&h=400&fit=crop' },
    { title:'Kipas ChatBot',            description:'AI customer support chatbot with NLP and a web-based chat widget',   tech:'Python, NLP, JavaScript',          category:'fullstack', github:'https://github.com/Sabridemr/KipasChatBotProject',        live:'#',                                            status:'live', image:'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=600&h=400&fit=crop' },
    { title:'SwiftUI Network Layer',    description:'Protocol-oriented, interchangeable network layer for SwiftUI apps',  tech:'Swift, SwiftUI, Combine',          category:'frontend',  github:'https://github.com/Sabridemr/NetworkInterChangableSwiftUI', live:'#',                                           status:'live', image:'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=400&fit=crop' },
];

async function loadProjects() {
    try {
        const res  = await fetch('php/get_projects.php');
        const data = await res.json();
        if (data.success && data.projects.length > 0) {
            projects = data.projects.map(p => ({
                title:       p.title,
                description: p.description,
                tech:        p.tech_stack,
                category:    p.category,
                github:      p.github_url  || '#',
                live:        p.live_url    || '#',
                status:      p.status      || 'live',
                image:       p.image_url   || ''
            }));
        } else {
            projects = staticProjects;
        }
    } catch {
        projects = staticProjects;
    }
    renderProjects();
    renderTable();
}

function renderProjects() {
    const grid = document.getElementById('projectsGrid');
    const filtered = currentFilter === 'all'
        ? projects
        : projects.filter(p => p.category === currentFilter);

    grid.innerHTML = filtered.map((project, index) => `
        <div class="glass-card rounded-xl overflow-hidden group reveal" style="transition-delay: ${index * 0.1}s">
            <div class="relative h-48 overflow-hidden">
                <img src="${project.image}" alt="${project.title}" 
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(42,37,32,0.25) 0%, transparent 55%)"></div>
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full text-xs font-mono ${project.status === 'live' ? 'bg-green-500/20 text-green-400' : 'bg-accent/20 text-accent'} border border-current">
                        ${project.status}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-mono text-accent uppercase">${project.category}</span>
                    <span class="text-xs text-dark-muted font-mono">0${index + 1}</span>
                </div>
                <h3 class="text-lg font-bold mb-2 group-hover:text-accent transition-colors">${project.title}</h3>
                <p class="text-sm text-dark-muted mb-4">${project.tech}</p>
                <div class="flex gap-3">
                    ${project.github !== '#' ? `
                        <a href="${project.github}" target="_blank" rel="noopener" 
                            class="text-sm text-dark-muted hover:text-accent transition-colors inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            Code
                        </a>
                    ` : ''}
                    ${project.live !== '#' ? `
                        <a href="${project.live}" target="_blank" rel="noopener" 
                            class="text-sm text-dark-muted hover:text-accent transition-colors inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Live
                        </a>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');

    // Re-observe new elements
    grid.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
}

function renderTable() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = projects.map((project, index) => `
        <tr class="hover:bg-accent/5 transition-colors">
            <td class="py-4 px-4 font-mono text-dark-muted">${index + 1}</td>
            <td class="py-4 px-4 font-medium">${project.title}</td>
            <td class="py-4 px-4 text-dark-muted">${project.tech}</td>
            <td class="py-4 px-4">
                <span class="px-2 py-1 rounded-full text-xs border border-dark-border text-dark-muted">${project.category}</span>
            </td>
            <td class="py-4 px-4">
                <span class="px-2 py-1 rounded-full text-xs ${project.status === 'live' ? 'text-green-400 border border-green-400/30' : 'text-accent border border-accent/30'}">${project.status}</span>
            </td>
            <td class="py-4 px-4">
                <div class="flex gap-3">
                    ${project.github !== '#' ? `<a href="${project.github}" target="_blank" rel="noopener" class="text-dark-muted hover:text-accent transition-colors">GitHub</a>` : '—'}
                    ${project.live !== '#' ? `<a href="${project.live}" target="_blank" rel="noopener" class="text-dark-muted hover:text-accent transition-colors">Live</a>` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

// Filter buttons
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('active', 'bg-accent/10', 'text-accent', 'border-accent');
            b.classList.add('border-dark-border', 'text-dark-muted');
        });
        btn.classList.add('active', 'bg-accent/10', 'text-accent', 'border-accent');
        btn.classList.remove('border-dark-border', 'text-dark-muted');
        currentFilter = btn.dataset.filter;
        renderProjects();
    });
});

// Table view toggle
const toggleTableBtn = document.getElementById('toggleTableView');
const tableView = document.getElementById('tableView');
let tableVisible = false;

toggleTableBtn?.addEventListener('click', () => {
    tableVisible = !tableVisible;
    tableView.classList.toggle('hidden', !tableVisible);
    toggleTableBtn.innerHTML = tableVisible
        ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg> View as Grid`
        : `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg> View as Table`;
});

loadProjects();

/* ── Contact Form ──────────────────────────────────────────── */
const contactForm = document.getElementById('contactForm');
const submitBtn = document.getElementById('submitBtn');
const formFeedback = document.getElementById('formFeedback');

function validateForm() {
    let isValid = true;
    const fields = [
        { id: 'formName', errorId: 'nameError', validate: (v) => v.length >= 2, message: 'Min 2 characters' },
        { id: 'formEmail', errorId: 'emailError', validate: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), message: 'Valid email required' },
        { id: 'formSubject', errorId: 'subjectError', validate: (v) => v.length >= 3, message: 'Min 3 characters' },
        { id: 'formMessage', errorId: 'messageError', validate: (v) => v.length >= 20, message: 'Min 20 characters' }
    ];

    fields.forEach(field => {
        const input = document.getElementById(field.id);
        const error = document.getElementById(field.errorId);
        const value = input.value.trim();

        if (!field.validate(value)) {
            error.classList.remove('hidden');
            input.classList.add('border-red-400');
            isValid = false;
        } else {
            error.classList.add('hidden');
            input.classList.remove('border-red-400');
        }
    });

    const privacy = document.getElementById('formPrivacy');
    const privacyError = document.getElementById('privacyError');
    if (!privacy.checked) {
        privacyError.classList.remove('hidden');
        isValid = false;
    } else {
        privacyError.classList.add('hidden');
    }

    return isValid;
}

contactForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!validateForm()) return;

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...`;

    try {
        const response = await fetch('php/contact.php', {
            method: 'POST',
            body: new FormData(contactForm)
        });
        const data = await response.json();

        formFeedback.className = `p-4 rounded-lg text-sm ${data.success ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'}`;
        formFeedback.textContent = data.success ? '✓ Message sent! I\'ll reply within 24 hours.' : (data.message || 'Something went wrong.');
        formFeedback.classList.remove('hidden');

        if (data.success) {
            contactForm.reset();
        }
    } catch {
        formFeedback.className = 'p-4 rounded-lg text-sm bg-green-500/10 text-green-400 border border-green-500/20';
        formFeedback.textContent = '✓ Message received! (Demo mode)';
        formFeedback.classList.remove('hidden');
        contactForm.reset();
    }

    submitBtn.disabled = false;
    submitBtn.innerHTML = `<span>Send Message</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>`;

    setTimeout(() => formFeedback.classList.add('hidden'), 6000);
});

// Real-time validation
['formName', 'formEmail', 'formSubject', 'formMessage'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', () => {
        const field = document.getElementById(id);
        field.classList.remove('border-red-400');
        document.getElementById(id.replace('form', '').toLowerCase() + 'Error')?.classList.add('hidden');
    });
});

/* ── Back to Top ───────────────────────────────────────────── */
document.getElementById('backToTop')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

/* ── Cookie Banner ────────────────────────────────────────── */
const cookieBanner = document.getElementById('cookieBanner');
const cookieAccept = document.getElementById('cookieAccept');
const cookieDecline = document.getElementById('cookieDecline');

function getCookie(name) {
    return document.cookie.match(new RegExp(`${name}=([^;]+)`))?.[1] || '';
}

function setCookie(name, value) {
    document.cookie = `${name}=${value}; path=/; max-age=${60 * 60 * 24 * 365}`;
}

if (!getCookie('cookieConsent')) {
    setTimeout(() => cookieBanner.classList.remove('hidden'), 2000);
}

cookieAccept?.addEventListener('click', () => {
    setCookie('cookieConsent', 'yes');
    cookieBanner.classList.add('hidden');
});

cookieDecline?.addEventListener('click', () => {
    setCookie('cookieConsent', 'no');
    cookieBanner.classList.add('hidden');
});

/* ── Smooth Scroll for Anchor Links ───────────────────────── */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

/* ── Typing Effect for Hero ──────────────────────────────── */
const typingElement = document.querySelector('.typing');
if (typingElement) {
    const text = typingElement.dataset.text || 'Full-Stack Developer';
    let i = 0;
    typingElement.textContent = '';

    function typeWriter() {
        if (i < text.length) {
            typingElement.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, 100);
        }
    }
    setTimeout(typeWriter, 1000);
}
// Theme Toggle
const themeToggle = document.querySelector('.theme-toggle');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

function setTheme(isDark) {
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
}

themeToggle.addEventListener('click', () => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    setTheme(!isDark);
    localStorage.setItem('theme', !isDark ? 'dark' : 'light');
});

// Code Tabs
const tabButtons = document.querySelectorAll('.tab-btn');
tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        document.querySelector('.tab-btn.active').classList.remove('active');
        button.classList.add('active');
        // Switch tab content
        const tabId = button.dataset.tab;
        document.querySelector('.tab-content.active').classList.remove('active');
        document.querySelector(`#${tabId}`).classList.add('active');
    });
});

// Search functionality
const searchData = {
    'routes': ['Routing', 'URL patterns', 'HTTP methods', 'Controllers'],
    'controllers': ['Request handling', 'Response', 'Views', 'Middleware'],
    'models': ['Database', 'Relationships', 'Queries', 'Validation'],
    // Add more search data
};

function toggleSearch() {
    const overlay = document.getElementById('searchOverlay');
    overlay.classList.toggle('active');
    if (overlay.classList.contains('active')) {
        document.getElementById('searchInput').focus();
    }
}

document.getElementById('searchInput').addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase();
    const results = Object.entries(searchData)
        .flatMap(([category, items]) => 
            items
                .filter(item => item.toLowerCase().includes(query))
                .map(item => `<a href="#${category}">${item}</a>`)
        );
    
    document.getElementById('searchResults').innerHTML = 
        results.length ? results.join('') : '<p>No results found</p>';
});

// Add smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href'))
            .scrollIntoView({ behavior: 'smooth' });
    });
});

// Add copy button to code blocks
document.querySelectorAll('.code-window').forEach(block => {
    const copyBtn = document.createElement('button');
    copyBtn.className = 'copy-btn';
    copyBtn.innerHTML = '<i class="far fa-copy"></i>';
    copyBtn.onclick = () => {
        const code = block.querySelector('code').textContent;
        navigator.clipboard.writeText(code);
        copyBtn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            copyBtn.innerHTML = '<i class="far fa-copy"></i>';
        }, 2000);
    };
    block.querySelector('.code-header').appendChild(copyBtn);
});

// Enhanced Tutorial Manager
class TutorialManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = document.querySelectorAll('.tutorial .step').length;
        this.steps = document.querySelectorAll('.tutorial .step');
        this.preview = document.querySelector('.browser-content');
        this.console = document.querySelector('.console-content');
        this.progressBar = document.querySelector('.progress-fill');
        this.progressText = document.querySelector('.progress-text');
        
        this.setupEventListeners();
        this.updateProgress();
    }

    setupEventListeners() {
        // Setup code validation
        document.querySelectorAll('.check-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const playground = btn.closest('.code-playground');
                const code = playground.querySelector('code').textContent;
                this.validateCode(code, playground.dataset.validate);
            });
        });

        // Setup hints
        document.querySelectorAll('.hint-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const hintContent = btn.nextElementSibling;
                hintContent.classList.toggle('active');
                btn.textContent = hintContent.classList.contains('active') ? 'Hide Hint' : 'Need a hint?';
            });
        });

        // Setup reset buttons
        document.querySelectorAll('.reset-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const playground = btn.closest('.code-playground');
                this.resetCode(playground);
            });
        });
    }

    validateCode(code, type) {
        const validations = {
            route: (code) => {
                const hasRoute = code.includes('$router->get');
                const hasFunction = code.includes('function');
                const returnsHello = code.includes('return') && code.includes('Hello World');
                return hasRoute && hasFunction && returnsHello;
            },
            controller: (code) => {
                const extendsController = code.includes('extends Controller');
                const hasIndex = code.includes('public function index');
                return extendsController && hasIndex;
            }
        };

        try {
            if (validations[type](code)) {
                this.showSuccess();
                this.updatePreview(code);
                this.logToConsole('✓ Code validated successfully');
                this.nextStep();
            } else {
                this.showError('Code validation failed. Try again!');
                this.logToConsole('✗ Code validation failed');
            }
        } catch (error) {
            this.showError(error.message);
            this.logToConsole(`Error: ${error.message}`);
        }
    }

    updatePreview(code) {
        if (code.includes('Hello World')) {
            this.preview.innerHTML = `
                <div class="preview-content">
                    <h1>Hello World</h1>
                </div>
            `;
        }
    }

    logToConsole(message) {
        const timestamp = new Date().toLocaleTimeString();
        this.console.innerHTML += `<div>[${timestamp}] ${message}</div>`;
        this.console.scrollTop = this.console.scrollHeight;
    }

    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.updateSteps();
            this.updateProgress();
        } else {
            this.showCompletion();
        }
    }

    updateSteps() {
        this.steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) === this.currentStep) {
                step.classList.add('active');
            }
        });
    }

    updateProgress() {
        const progress = (this.currentStep - 1) / this.totalSteps * 100;
        this.progressBar.style.width = `${progress}%`;
        this.progressText.textContent = `Step ${this.currentStep} of ${this.totalSteps}`;
    }

    showCompletion() {
        this.preview.innerHTML = `
            <div class="completion-message">
                <h2>🎉 Congratulations!</h2>
                <p>You've completed the Blazer Framework tutorial.</p>
                <button class="btn btn-primary" onclick="window.location.href='#docs'">
                    Continue to Documentation
                </button>
            </div>
        `;
    }
}

// Initialize the enhanced tutorial
const tutorial = new TutorialManager();

// API Documentation Search
const apiSearch = document.getElementById('apiSearch');
if (apiSearch) {
    apiSearch.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.api-method').forEach(method => {
            const title = method.querySelector('h3').textContent.toLowerCase();
            method.style.display = title.includes(query) ? 'block' : 'none';
        });
    });
}

// Add scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.feature-card, .code-window, .api-method').forEach(el => {
    observer.observe(el);
});

// Mobile Menu
const mobileMenuBtn = document.createElement('button');
mobileMenuBtn.className = 'mobile-menu-btn';
mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
document.querySelector('.navbar .container').prepend(mobileMenuBtn);

mobileMenuBtn.addEventListener('click', () => {
    document.querySelector('.nav-links').classList.toggle('active');
});

// Enhanced Code Playground
class CodePlayground {
    constructor(element) {
        this.element = element;
        this.output = document.querySelector('.browser-content');
        this.setupEditor();
    }

    setupEditor() {
        this.editor = this.element.querySelector('code');
        this.editor.setAttribute('contenteditable', 'true');
        this.editor.addEventListener('input', () => this.updatePreview());
        this.editor.addEventListener('keydown', (e) => this.handleTab(e));
    }

    handleTab(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            document.execCommand('insertText', false, '    ');
        }
    }

    updatePreview() {
        try {
            // Simulate code execution
            const code = this.editor.textContent;
            this.output.innerHTML = `<div class="preview-result">Output: ${this.simulateExecution(code)}</div>`;
        } catch (error) {
            this.output.innerHTML = `<div class="preview-error">${error.message}</div>`;
        }
    }

    simulateExecution(code) {
        // Simple simulation for demo
        if (code.includes('Hello World')) {
            return 'Hello World!';
        }
        return 'Waiting for valid code...';
    }
}

// Initialize playgrounds
document.querySelectorAll('.code-playground').forEach(playground => {
    new CodePlayground(playground);
});

// Enhanced animations
const animateOnScroll = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            if (entry.target.classList.contains('feature-card')) {
                entry.target.style.animationDelay = `${Math.random() * 0.5}s`;
            }
            observer.unobserve(entry.target);
        }
    });
};

const observer = new IntersectionObserver(animateOnScroll, {
    threshold: 0.1,
    rootMargin: '50px'
});

document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el)); 
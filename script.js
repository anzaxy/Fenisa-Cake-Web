document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const keywordInput = document.getElementById('keyword');
    const kategoriSelect = document.getElementById('kategori');
    const searchBtn = document.getElementById('searchBtn');
    const errorMessage = document.getElementById('errorMessage');
    
    searchForm.addEventListener('submit', function(e) {
        const keyword = keywordInput.value.trim();
        const kategori = kategoriSelect.value;
        
        errorMessage.textContent = '';
        errorMessage.style.display = 'none';
        
        if (keyword === '' && kategori === '') {
            e.preventDefault(); 
            showError('Harap isi kata kunci pencarian atau pilih kategori!');
            return false;
        }
        
        if (keyword !== '' && keyword.length < 2) {
            e.preventDefault();
            showError('Kata kunci minimal 2 karakter!');
            return false;
        }
        
        showLoading();
        return true;
    });
    
    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        errorMessage.style.color = '#e74c3c';
        
        keywordInput.style.animation = 'shake 0.5s';
        setTimeout(() => {
            keywordInput.style.animation = '';
        }, 500);
    }
    
    function showLoading() {
        searchBtn.textContent = '🔄 Mencari...';
        searchBtn.disabled = true;
    }
    
    keywordInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            errorMessage.style.display = 'none';
        }
    });
    
    kategoriSelect.addEventListener('change', function() {
        if (this.value !== '') {
            errorMessage.style.display = 'none';
        }
    });
    
    searchBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 5px 15px rgb(158, 128, 228)';
    });
    
    searchBtn.addEventListener('mouseleave', function() {
        if (!this.disabled) {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        }
    });
    
    const cakeCards = document.querySelectorAll('.cake-card');
    cakeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
        
        card.addEventListener('click', function() {
            this.style.transform = 'translateY(-3px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            }, 150);
        });
    });
    
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(255,255,255,0.2)';
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
            this.style.transform = 'translateY(0)';
        });
    });
    
    keywordInput.focus();
    
    const originalPlaceholder = keywordInput.placeholder;
    keywordInput.addEventListener('focus', function() {
        this.placeholder = '';
    });
    
    keywordInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.placeholder = originalPlaceholder;
        }
    });
    
    const prices = document.querySelectorAll('.price');
    prices.forEach(price => {
        price.addEventListener('mouseenter', function() {
            this.style.color = 'rgb(158, 128, 228)';
            this.style.fontSize = '1.3rem';
            this.style.fontWeight = '700';
        });
        
        price.addEventListener('mouseleave', function() {
            this.style.color = 'rgb(158, 128, 228)';
            this.style.fontSize = '1.2rem';
            this.style.fontWeight = '600';
        });
    });
});

const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
`;
document.head.appendChild(style);

window.addEventListener('scroll', function() {
    const elements = document.querySelectorAll('.cake-card, .search-section');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('fade-in');
        }
    });
});
 document.getElementById('orderForm')?.addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const kontak = document.getElementById('kontak').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            
            if (!nama || !kontak || !alamat) {
                e.preventDefault();
                alert('Semua field harus diisi!');
                return;
            }
            
            if (!/^\d{10,}$/.test(kontak)) {
                e.preventDefault();
                alert('Nomor kontak harus berupa angka minimal 10 digit!');
                return;
            }
            
            if (confirm('Apakah Anda yakin ingin memesan cake ini?')) {
                return true;
            } else {
                e.preventDefault();
            }
        });
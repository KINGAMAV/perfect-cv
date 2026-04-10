/**
 * Perfect CV - Application Frontend
 * Gère les interactions utilisateur, les formulaires, l'authentification et les uploads
 */

// État global de l'application
let currentUser = null;
let uploadedPhotoUrl = null;

// Vérifier si l'utilisateur est déjà connecté au chargement
document.addEventListener('DOMContentLoaded', () => {
    console.log('Perfect CV - Frontend chargé');
    
    // Charger les informations de l'utilisateur depuis localStorage
    const savedUser = localStorage.getItem('currentUser');
    if (savedUser) {
        currentUser = JSON.parse(savedUser);
        updateAuthUI();
    } else {
        updateAuthUI();
    }

    // Gestionnaires pour le formulaire CV
    const cvForm = document.getElementById('cvForm');
    if (cvForm) {
        cvForm.addEventListener('submit', handleFormSubmit);
    }

    // Gestionnaire pour ajouter des expériences
    const addExperienceBtn = document.getElementById('addExperience');
    if (addExperienceBtn) {
        addExperienceBtn.addEventListener('click', addExperienceField);
    }

    // Gestionnaire pour ajouter des formations
    const addEducationBtn = document.getElementById('addEducation');
    if (addEducationBtn) {
        addEducationBtn.addEventListener('click', addEducationField);
    }

    // Gestionnaire pour upload photo
    const photoInput = document.getElementById('profilePhoto');
    if (photoInput) {
        photoInput.addEventListener('change', handlePhotoUpload);
    }
});

// Mettre à jour l'interface d'authentification
function updateAuthUI() {
    const authSection = document.getElementById('authSection');
    const userSection = document.getElementById('userSection');
    const saveCvBtn = document.getElementById('saveCvBtn');
    const downloadPdfBtn = document.getElementById('downloadPdfBtn');

    if (currentUser) {
        authSection.classList.add('hidden');
        userSection.classList.remove('hidden');
        document.getElementById('userEmail').textContent = currentUser.email;
        saveCvBtn.classList.remove('hidden');
        downloadPdfBtn.classList.remove('hidden');
    } else {
        authSection.classList.remove('hidden');
        userSection.classList.add('hidden');
        saveCvBtn.classList.add('hidden');
        downloadPdfBtn.classList.add('hidden');
    }
}

// Modales d'authentification
function showLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
}

function hideLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.getElementById('loginError').classList.add('hidden');
}

function showRegisterModal() {
    document.getElementById('registerModal').classList.remove('hidden');
}

function hideRegisterModal() {
    document.getElementById('registerModal').classList.add('hidden');
    document.getElementById('registerError').classList.add('hidden');
}

function switchToRegister() {
    hideLoginModal();
    showRegisterModal();
}

function switchToLogin() {
    hideRegisterModal();
    showLoginModal();
}

// Handler pour le login
async function handleLogin() {
    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    const errorDiv = document.getElementById('loginError');

    if (!email || !password) {
        errorDiv.textContent = 'Email et mot de passe requis';
        errorDiv.classList.remove('hidden');
        return;
    }

    try {
        const response = await fetch('../../backend/api/auth/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (result.ok) {
            currentUser = {
                userId: result.userId,
                email: result.email,
                token: result.token
            };
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            updateAuthUI();
            hideLoginModal();
            document.getElementById('loginEmail').value = '';
            document.getElementById('loginPassword').value = '';
            alert('Bienvenue ' + result.email + '!');
        } else {
            errorDiv.textContent = result.error || 'Erreur de connexion';
            errorDiv.classList.remove('hidden');
        }
    } catch (error) {
        errorDiv.textContent = 'Erreur réseau: ' + error.message;
        errorDiv.classList.remove('hidden');
    }
}

// Handler pour le register
async function handleRegister() {
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('regPassword').value;
    const passwordConfirm = document.getElementById('regPasswordConfirm').value;
    const errorDiv = document.getElementById('registerError');

    if (!email || !password || !passwordConfirm) {
        errorDiv.textContent = 'Tous les champs sont requis';
        errorDiv.classList.remove('hidden');
        return;
    }

    if (password !== passwordConfirm) {
        errorDiv.textContent = 'Les mots de passe ne correspondent pas';
        errorDiv.classList.remove('hidden');
        return;
    }

    if (password.length < 6) {
        errorDiv.textContent = 'Le mot de passe doit contenir au moins 6 caractères';
        errorDiv.classList.remove('hidden');
        return;
    }

    try {
        const response = await fetch('../../backend/api/auth/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (result.ok) {
            currentUser = {
                userId: result.userId,
                email: result.email,
                token: result.token
            };
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            updateAuthUI();
            hideRegisterModal();
            document.getElementById('regEmail').value = '';
            document.getElementById('regPassword').value = '';
            document.getElementById('regPasswordConfirm').value = '';
            alert('Compte créé avec succès! Bienvenue ' + result.email + '!');
        } else {
            errorDiv.textContent = result.error || 'Erreur lors de l\'inscription';
            errorDiv.classList.remove('hidden');
        }
    } catch (error) {
        errorDiv.textContent = 'Erreur réseau: ' + error.message;
        errorDiv.classList.remove('hidden');
    }
}

function logout() {
    currentUser = null;
    localStorage.removeItem('currentUser');
    uploadedPhotoUrl = null;
    updateAuthUI();
    alert('Déconnecté avec succès');
}

// Handler pour upload photo
async function handlePhotoUpload(event) {
    const file = event.target.files[0];
    const statusDiv = document.getElementById('photoStatus');
    const previewImg = document.getElementById('photoPreview');

    if (!file) {
        statusDiv.textContent = '';
        previewImg.classList.add('hidden');
        return;
    }

    // Validation client
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!allowedTypes.includes(file.type)) {
        statusDiv.textContent = '❌ Type de fichier non autorisé';
        statusDiv.classList.remove('hidden');
        event.target.value = '';
        previewImg.classList.add('hidden');
        return;
    }

    if (file.size > maxSize) {
        statusDiv.textContent = '❌ Le fichier est trop volumineux (max 5MB)';
        statusDiv.classList.remove('hidden');
        event.target.value = '';
        previewImg.classList.add('hidden');
        return;
    }

    statusDiv.textContent = 'Chargement...';

    // Afficher aperçu local
    const reader = new FileReader();
    reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewImg.classList.remove('hidden');
    };
    reader.readAsDataURL(file);

    // Upload vers le serveur
    try {
        const formData = new FormData();
        formData.append('photo', file);

        const response = await fetch('../../backend/api/upload/photo.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.ok) {
            uploadedPhotoUrl = result.photoUrl;
            statusDiv.textContent = '✓ Photo uploadée avec succès';
            statusDiv.classList.remove('hidden');
        } else {
            statusDiv.textContent = '❌ Erreur: ' + result.error;
            statusDiv.classList.remove('hidden');
            previewImg.classList.add('hidden');
            event.target.value = '';
        }
    } catch (error) {
        statusDiv.textContent = '❌ Erreur réseau: ' + error.message;
        statusDiv.classList.remove('hidden');
        previewImg.classList.add('hidden');
        event.target.value = '';
    }
}

/**
 * Gère la soumission du formulaire CV.
 */
async function handleFormSubmit(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const cvData = {
        fullName: formData.get('fullName'),
        title: formData.get('title'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        summary: formData.get('summary'),
        skills: formData.get('skills') ? formData.get('skills').split(',').map(s => s.trim()) : [],
        experience: [],
        education: [],
        photoUrl: uploadedPhotoUrl,
        template: localStorage.getItem('selectedTemplate') || 'modern'
    };

    // Collecter les expériences
    const experienceItems = document.querySelectorAll('.experience-item');
    experienceItems.forEach((item, index) => {
        const role = formData.get(`experience[${index}][role]`);
        const company = formData.get(`experience[${index}][company]`);
        const start = formData.get(`experience[${index}][start]`);
        const end = formData.get(`experience[${index}][end]`);
        const details = formData.get(`experience[${index}][details]`);
        if (role || company) {
            cvData.experience.push({ role, company, start, end, details });
        }
    });

    // Collecter les formations
    const educationItems = document.querySelectorAll('.education-item');
    educationItems.forEach((item, index) => {
        const degree = formData.get(`education[${index}][degree]`);
        const school = formData.get(`education[${index}][school]`);
        const start = formData.get(`education[${index}][start]`);
        const end = formData.get(`education[${index}][end]`);
        if (degree || school) {
            cvData.education.push({ degree, school, start, end });
        }
    });

    // Générer l'aperçu du CV
    await generateCv(cvData);
}

/**
 * Génère l'aperçu du CV
 */
async function generateCv(cvData) {
    try {
        const response = await fetch('../../backend/api/cv/generate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cvData)
        });

        const result = await response.json();
        if (result.ok) {
            document.getElementById('cvResult').innerHTML = result.html;
            window.generatedCvData = cvData; // Stocker les données pour le téléchargement
        } else {
            alert('Erreur: ' + result.error);
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
        alert('Erreur: ' + error.message);
    }
}

/**
 * Sauvegarder le CV dans la base de données
 */
async function saveCv() {
    if (!currentUser) {
        alert('Veuillez vous connecter pour sauvegarder votre CV');
        showLoginModal();
        return;
    }

    if (!window.generatedCvData) {
        alert('Veuillez d\'abord créer un aperçu du CV');
        return;
    }

    const cvName = prompt('Nom de votre CV:', 'Mon CV');
    if (!cvName) return;

    const saveData = {
        ...window.generatedCvData,
        userId: currentUser.userId,
        token: currentUser.token,
        cvName: cvName
    };

    try {
        const response = await fetch('../../backend/api/cv/save.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(saveData)
        });

        const result = await response.json();
        if (result.ok) {
            alert('CV sauvegardé avec succès! ID: ' + result.cvId);
        } else {
            alert('Erreur: ' + result.error);
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur: ' + error.message);
    }
}

/**
 * Télécharger le CV en PDF
 */
async function downloadPdf() {
    if (!currentUser) {
        alert('Veuillez vous connecter pour télécharger votre CV');
        showLoginModal();
        return;
    }

    if (!window.generatedCvData) {
        alert('Veuillez d\'abord créer un aperçu du CV');
        return;
    }

    try {
        // Charger html2pdf.js depuis CDN si pas déjà présent
        if (typeof html2pdf === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
            document.head.appendChild(script);
            
            script.onload = () => {
                generateAndDownloadPdf();
            };
        } else {
            generateAndDownloadPdf();
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur: ' + error.message);
    }
}

function generateAndDownloadPdf() {
    const element = document.getElementById('cvResult');
    if (!element) {
        alert('Aucun CV à télécharger');
        return;
    }

    const cvName = (window.generatedCvData.fullName || 'CV').replace(/[^a-z0-9]/gi, '_').toLowerCase();
    
    const options = {
        margin: [10, 10, 10, 10],
        filename: `${cvName}_${new Date().getTime()}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
    };

    // Cloner l'élément pour le traitement PDF
    const clonedElement = element.cloneNode(true);
    
    // Télécharger
    html2pdf().set(options).from(clonedElement).save();
}

/**
 * Ajoute un champ d'expérience supplémentaire.
 */
function addExperienceField() {
    const container = document.getElementById('experienceContainer');
    const index = container.children.length;
    const newItem = document.createElement('div');
    newItem.className = 'experience-item grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded';
    newItem.innerHTML = `
        <input type="text" name="experience[${index}][role]" placeholder="Poste" class="px-3 py-2 border border-gray-300 rounded-md">
        <input type="text" name="experience[${index}][company]" placeholder="Entreprise" class="px-3 py-2 border border-gray-300 rounded-md">
        <input type="text" name="experience[${index}][start]" placeholder="Date début (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
        <input type="text" name="experience[${index}][end]" placeholder="Date fin (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
        <textarea name="experience[${index}][details]" placeholder="Description" rows="2" class="md:col-span-2 px-3 py-2 border border-gray-300 rounded-md"></textarea>
    `;
    container.appendChild(newItem);
}

/**
 * Ajoute un champ de formation supplémentaire.
 */
function addEducationField() {
    const container = document.getElementById('educationContainer');
    const index = container.children.length;
    const newItem = document.createElement('div');
    newItem.className = 'education-item grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-200 rounded';
    newItem.innerHTML = `
        <input type="text" name="education[${index}][degree]" placeholder="Diplôme" class="px-3 py-2 border border-gray-300 rounded-md">
        <input type="text" name="education[${index}][school]" placeholder="Établissement" class="px-3 py-2 border border-gray-300 rounded-md">
        <input type="text" name="education[${index}][start]" placeholder="Date début (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
        <input type="text" name="education[${index}][end]" placeholder="Date fin (MM/YYYY)" class="px-3 py-2 border border-gray-300 rounded-md">
    `;
    container.appendChild(newItem);
}

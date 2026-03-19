document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('devis-form-element');
    if (!form) return;

    const steps = document.querySelectorAll('.devis-step');
    const prevBtn = document.getElementById('prev-step');
    const nextBtn = document.getElementById('next-step');
    const submitBtn = document.getElementById('submit-devis');
    const progressBar = document.getElementById('progress-bar');
    const stepLabel = document.getElementById('step-label');
    const stepName = document.getElementById('step-name');
    const resultDiv = document.getElementById('devis-result');
    const navDiv = document.getElementById('devis-nav');
    const modifyBtn = document.getElementById('modify-answers');

    const stepNames = ['Qui êtes-vous ?', 'Votre projet', 'Vos besoins', 'Budget et planning'];
    let currentStep = 1;
    const totalSteps = 4;

    function showStep(step) {
        steps.forEach(s => s.classList.add('hidden'));
        const target = document.querySelector(`[data-step="${step}"]`);
        if (target) target.classList.remove('hidden');

        prevBtn.classList.toggle('hidden', step === 1);
        nextBtn.classList.toggle('hidden', step === totalSteps);
        submitBtn.classList.toggle('hidden', step !== totalSteps);

        progressBar.style.width = `${(step / totalSteps) * 100}%`;
        stepLabel.textContent = `Étape ${step} sur ${totalSteps}`;
        stepName.textContent = stepNames[step - 1];
    }

    function validateStep(step) {
        const currentStepEl = document.querySelector(`[data-step="${step}"]`);
        const requiredInputs = currentStepEl.querySelectorAll('[required]');
        let valid = true;

        requiredInputs.forEach(input => {
            if (input.type === 'radio') {
                const name = input.name;
                const checked = currentStepEl.querySelector(`input[name="${name}"]:checked`);
                if (!checked) valid = false;
            } else if (!input.value.trim()) {
                valid = false;
            }
        });

        return valid;
    }

    nextBtn.addEventListener('click', () => {
        if (!validateStep(currentStep)) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    if (modifyBtn) {
        modifyBtn.addEventListener('click', () => {
            resultDiv.classList.add('hidden');
            navDiv.classList.remove('hidden');
            currentStep = 1;
            showStep(currentStep);
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateStep(currentStep)) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }

        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            if (key === 'features[]') {
                if (!data.features) data.features = [];
                data.features.push(value);
            } else {
                data[key] = value;
            }
        });

        try {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Envoi en cours...';

            const response = await fetch(form.dataset.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.success) {
                showResult(result, data);
            } else {
                alert(result.message || 'Une erreur est survenue. Veuillez réessayer.');
            }
        } catch (error) {
            alert('Une erreur est survenue. Veuillez réessayer.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Envoyer ma demande';
        }
    });

    function showResult(result, data) {
        steps.forEach(s => s.classList.add('hidden'));
        navDiv.classList.add('hidden');
        resultDiv.classList.remove('hidden');

        const offerInfo = getOfferInfo(result.suggestedOffer);
        document.getElementById('suggested-offer').innerHTML = `
            <div class="flex items-center gap-3 mb-3">
                <div class="w-4 h-4 rounded-full ${offerInfo.dotClass}"></div>
                <h4 class="font-heading text-xl font-bold">${result.suggestedOffer}</h4>
            </div>
            <p class="text-varya-green font-heading font-bold text-2xl mb-2">${offerInfo.price}</p>
            <p class="text-varya-text-secondary">${offerInfo.description}</p>
        `;

        const labels = {
            structureType: 'Type de structure',
            hasExistingSite: 'Site existant',
            mainObjective: 'Objectif',
            estimatedPages: 'Nombre de pages',
            needsAutonomy: 'Autonomie',
            budget: 'Budget',
            timeline: 'Délai',
        };

        let summaryHtml = '<h4 class="font-heading font-semibold mb-4">Récapitulatif de vos réponses</h4><dl class="space-y-2">';
        for (const [key, label] of Object.entries(labels)) {
            if (data[key]) {
                let value = data[key];
                if (key === 'hasExistingSite') value = value === '1' ? 'Oui' : 'Non';
                summaryHtml += `<div class="flex justify-between"><dt class="text-varya-text-secondary">${label}</dt><dd class="text-white font-medium">${value}</dd></div>`;
            }
        }
        if (data.features && data.features.length > 0) {
            summaryHtml += `<div class="flex justify-between"><dt class="text-varya-text-secondary">Fonctionnalités</dt><dd class="text-white font-medium">${data.features.join(', ')}</dd></div>`;
        }
        summaryHtml += '</dl>';
        document.getElementById('devis-summary').innerHTML = summaryHtml;

        progressBar.style.width = '100%';
        stepLabel.textContent = 'Terminé';
        stepName.textContent = 'Merci !';
    }

    function getOfferInfo(offer) {
        const offers = {
            'Landing Page Express': { dotClass: 'bg-varya-green', price: '150 €', description: '1 page professionnelle, livrée en 3-5 jours.' },
            'Site Vitrine Essentiel': { dotClass: 'bg-varya-blue-sky', price: 'à partir de 600 €', description: '3-5 pages + CMS, vous gérez vos contenus en autonomie.' },
            'Site Association / Structuré': { dotClass: 'bg-purple-500', price: 'à partir de 1 200 €', description: 'Jusqu\'à 10 pages, actualités, adhésions. Formation incluse.' },
            'Application Web Symfony': { dotClass: 'bg-varya-blue', price: 'sur devis (à partir de 2 500 €)', description: 'Sur mesure, pour les besoins qui dépassent la simple vitrine.' },
        };
        return offers[offer] || { dotClass: 'bg-white', price: 'sur devis', description: 'Je vous recontacte pour affiner.' };
    }
});

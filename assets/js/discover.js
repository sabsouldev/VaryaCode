document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('discover-form');
    if (!form) return;

    const steps = form.querySelectorAll('.discover-step');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const btnSubmit = document.getElementById('btn-submit');
    const progressBar = document.getElementById('progress-bar');
    const currentStepEl = document.getElementById('current-step');
    const progressPct = document.getElementById('progress-pct');

    let current = 1;
    const total = steps.length;

    function updateUI() {
        steps.forEach((step) => {
            const stepNum = parseInt(step.dataset.step, 10);
            step.classList.toggle('hidden', stepNum !== current);
        });

        btnPrev.classList.toggle('hidden', current === 1);
        btnNext.classList.toggle('hidden', current === total);
        btnSubmit.classList.toggle('hidden', current !== total);

        const pct = Math.round((current / total) * 100);
        progressBar.style.width = pct + '%';
        currentStepEl.textContent = current;
        progressPct.textContent = pct + '%';
    }

    // Auto-advance on radio selection (steps 1, 2, 4)
    form.querySelectorAll('input[type="radio"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            if (current < total) {
                setTimeout(() => {
                    current++;
                    updateUI();
                }, 200);
            }
        });
    });

    // Checkbox visual toggle (step 3)
    form.querySelectorAll('input[type="checkbox"]').forEach((cb) => {
        cb.addEventListener('change', () => {
            const card = cb.closest('.discover-option').querySelector('.card-v2');
            const checkIcon = card.querySelector('svg');
            const checkBox = card.querySelector('.w-5.h-5');
            if (cb.checked) {
                card.classList.add('border-vc-blue', 'bg-blue-50');
                if (checkBox) {
                    checkBox.classList.add('bg-vc-blue', 'border-vc-blue');
                    checkBox.classList.remove('border-slate-300');
                }
                if (checkIcon) checkIcon.classList.remove('hidden');
            } else {
                card.classList.remove('border-vc-blue', 'bg-blue-50');
                if (checkBox) {
                    checkBox.classList.remove('bg-vc-blue', 'border-vc-blue');
                    checkBox.classList.add('border-slate-300');
                }
                if (checkIcon) checkIcon.classList.add('hidden');
            }
        });
    });

    btnNext.addEventListener('click', () => {
        if (current < total) {
            current++;
            updateUI();
        }
    });

    btnPrev.addEventListener('click', () => {
        if (current > 1) {
            current--;
            updateUI();
        }
    });

    updateUI();
});

import './bootstrap';

// Optimize exam page performance
document.addEventListener('livewire:load', function () {
    // Debounce function to limit how often a function can be called
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Handle question changes
    Livewire.on('question-changed', () => {
        // Smooth scroll to top of question
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // Preload next question image if exists
        const nextQuestionImage = document.querySelector('img[loading="lazy"]');
        if (nextQuestionImage) {
            nextQuestionImage.loading = 'eager';
        }
    });

    // Optimize form inputs
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', debounce(function() {
            // Auto-resize textarea
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }, 100));
    });

    // Save answers to localStorage as backup
    const saveAnswers = debounce(() => {
        const answers = {};
        document.querySelectorAll('input[type="radio"]:checked, textarea').forEach(input => {
            answers[input.name] = input.value;
        });
        localStorage.setItem('exam_answers', JSON.stringify(answers));
    }, 1000);

    // Add event listeners for answer changes
    document.addEventListener('change', saveAnswers);
    document.addEventListener('input', saveAnswers);

    // Restore answers from localStorage on page load
    const savedAnswers = localStorage.getItem('exam_answers');
    if (savedAnswers) {
        const answers = JSON.parse(savedAnswers);
        Object.entries(answers).forEach(([name, value]) => {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) {
                input.value = value;
                if (input.type === 'radio') {
                    input.checked = true;
                }
            }
        });
    }
});

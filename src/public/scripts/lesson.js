let currentSlide = 0;
let totalSlides = 0;
let courseId = null;

document.addEventListener('DOMContentLoaded', function() 
{
        const urlParams = new URLSearchParams(window.location.search);
        courseId = urlParams.get('id');

        const container = document.querySelector('.lesson-container');
        if (container) {
                totalSlides = parseInt(container.dataset.totalSlides, 10);
        }

        loadProgress();

        updateUI();

        const btn = document.getElementById('btn-action');
        if (btn) {
                btn.addEventListener('click', handleAction);
        }
});

function loadProgress() {
        if (!courseId) return;

        const savedSlide = sessionStorage.getItem(`lesson_progress_${courseId}`);
        if (savedSlide !== null)
        {
                const index = parseInt(savedSlide, 10);
                if (index >= 0 && index < totalSlides)
                {
                        for (let i = 0; i < index; i++)
                        {
                                const dot = document.getElementById(`dot-${i}`);
                                if (dot) dot.classList.add('completed');
                        }
                        currentSlide = index;
                }
        }
}

function saveProgress(index)
{
        if (!courseId) return;
        sessionStorage.setItem(`lesson_progress_${courseId}`, index);
}

function updateUI() 
{
        document.querySelectorAll('.sublesson-slide').forEach(slide => {
                slide.classList.remove('active');
        });

        const slide = document.getElementById(`slide-${currentSlide}`);
        if (!slide) return;

        slide.classList.add('active');

        document.querySelectorAll('.progress-dot').forEach((dot, index) => {
                dot.classList.remove('active');
                if (index === currentSlide) dot.classList.add('active');
        });

        const type = slide.dataset.type;
        const btn = document.getElementById('btn-action');
        const counter = document.getElementById('current-step');

        if (counter)
        {
                counter.innerText = currentSlide + 1;
        }

        if (type === 'info') 
        {
                btn.innerText = (currentSlide === totalSlides - 1) ? "Finish Lesson" : "Continue";
                btn.classList.remove('btn-check'); 
        } 
        else 
        {
                btn.innerText = "Check Answer";
                btn.classList.add('btn-check');
        }
}

function handleAction() 
{
        const slide = document.getElementById(`slide-${currentSlide}`);
        const type = slide.dataset.type;
        const btn = document.getElementById('btn-action');

        if (type !== 'info' && btn.innerText === "Check Answer") 
        {
                checkQuiz(slide, currentSlide);
                return;
        }

        if (currentSlide < totalSlides - 1) 
        {
                goToNextSlide();
        } 
        else 
        {
                if(courseId) sessionStorage.removeItem(`lesson_progress_${courseId}`);
                document.getElementById('complete-form').submit();
        }
}

function goToNextSlide() 
{
        const currentDot = document.getElementById(`dot-${currentSlide}`);
        if(currentDot)
        {
                currentDot.classList.remove('active');
                currentDot.classList.add('completed');
        }

        currentSlide++;
        saveProgress(currentSlide);

        updateUI();
}

function checkQuiz(slideElement, index) 
{
        const type = slideElement.dataset.type;
        const correctAnswer = slideElement.dataset.answer;
        const feedback = document.getElementById(`feedback-${index}`);
        const btn = document.getElementById('btn-action');

        let userAnswer = "";

        if (type === 'input') 
        {
                const inputEl = document.getElementById(`input-${index}`);
                if (inputEl) 
                {
                        userAnswer = inputEl.value.trim();
                }
        } 
        else 
        {
                const checked = document.querySelector(`input[name="q-${index}"]:checked`);
                if (checked) 
                {
                        userAnswer = checked.value;
                }
        }

        if (!userAnswer) 
        {
                feedback.className = "feedback-area incorrect";
                feedback.innerText = "Please select or type an answer.";
                feedback.style.display = "block";
                return;
        }

        if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) 
        {
                feedback.className = "feedback-area correct";
                feedback.innerText = "Correct! Well done.";
                feedback.style.display = "block";

                btn.innerText = (currentSlide === totalSlides - 1) ? "Finish Lesson" : "Continue";
                btn.classList.remove('btn-check');
        } 
        else 
        {
                feedback.className = "feedback-area incorrect";
                feedback.innerText = "Incorrect. Try again.";
                feedback.style.display = "block";
        }
}

let currentSlide = 0;

document.addEventListener('DOMContentLoaded', function() 
{
        updateUI();

        document.getElementById('btn-action').addEventListener('click', handleAction);
});

function updateUI() 
{
        const slide = document.getElementById(`slide-${currentSlide}`);
        const type = slide.dataset.type;
        const btn = document.getElementById('btn-action');

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
                document.getElementById('complete-form').submit();
        }
}

function goToNextSlide() 
{
        document.getElementById(`slide-${currentSlide}`).classList.remove('active');
        document.getElementById(`dot-${currentSlide}`).classList.remove('active');
        document.getElementById(`dot-${currentSlide}`).classList.add('completed');

        currentSlide++;

        document.getElementById(`slide-${currentSlide}`).classList.add('active');
        document.getElementById(`dot-${currentSlide}`).classList.add('active');

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

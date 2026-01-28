let currentSlide = 0;
let totalSlides = 0;
let courseId = null;

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

function showToast(message, type = 'success')
{
        let container = document.getElementById('toast-container');
        if(!container) {
             container = document.createElement('div');
             container.id = 'toast-container';
             document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerText = message;

        container.appendChild(toast);

        setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-in forwards';
                toast.addEventListener('animationend', () => {
                        toast.remove();
                });
        }, 3000);
}

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
        document.querySelectorAll('.sublesson-slide').forEach((slide, index) => {
                const iframe = slide.querySelector('iframe');
                const video = slide.querySelector('video');
                const media = iframe || video;
        
                if (index === currentSlide)
                {
                        slide.classList.add('active');

                        if (media && media.dataset.src)
                        {
                                if (media.tagName === 'IFRAME')
                                {
                                        let url = media.dataset.src;
                                        const separator = url.includes('?') ? '&' : '?';
                                        const playUrl = `${url}${separator}autoplay=1`;

                                        if (media.getAttribute('src') !== playUrl)
                                        {
                                                media.setAttribute('src', playUrl);
                                                media.onload = function() {
                                                        try
                                                        {
                                                                const innerDoc = media.contentWindow || media.contentDocument;
                                                                const innerVideo = innerDoc.querySelector('video');
                                                                if (innerVideo)
                                                                {
                                                                        innerVideo.volume = 0.3;
                                                                }
                                                        }
                                                        catch (e)
                                                        {
                                                                console.log("Cannot access iframe content due to cross-origin policy.");
                                                        }
                                                };
                                        }
                                }
                                else if (media.tagName === 'VIDEO')
                                {
                                        if (media.getAttribute('src') !== media.dataset.src)
                                        {
                                                media.setAttribute('src', media.dataset.src);

                                                media.volume = 0.3;
                                                media.muted = false;

                                                const playPromise = media.play();

                                                if (playPromise !== undefined)
                                                {
                                                        playPromise.catch(error => {
                                                                console.log("Autoplay with sound blocked. Fallback to muted.");
                                                                media.muted = true;
                                                                media.play();
                                                        });
                                                }
                                        }
                                }
                        }
                }
                else
                {
                        slide.classList.remove('active');

                        if (media)
                        {
                                media.removeAttribute('src');
                                if (media.tagName === 'VIDEO')
                                {
                                        media.load();
                                }
                                else
                                {
                                        media.onload = null;
                                }
                        }
                }
        });

        document.querySelectorAll('.progress-dot').forEach((dot, index) => {
                dot.classList.remove('active');
                if (index === currentSlide) dot.classList.add('active');
        });

        const slide = document.getElementById(`slide-${currentSlide}`);
        if (!slide) return;

        const type = slide.dataset.type;
        const btn = document.getElementById('btn-action');
        const counter = document.getElementById('current-step');

        if (counter)
        {
                counter.innerText = currentSlide + 1;
        }

        if (type === 'info' || type === 'video' || type === 'embed')
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
        if(currentDot) {
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
        const feedback = document.getElementById(`feedback-${index}`);
        const btn = document.getElementById('btn-action');

        let userAnswer = "";

        if (type === 'input') 
        {
                const inputEl = document.getElementById(`input-${index}`);
                if (inputEl) userAnswer = inputEl.value.trim();
        } 
        else 
        {
                const checked = document.querySelector(`input[name="q-${index}"]:checked`);
                if (checked) userAnswer = checked.value;
        }

        if (!userAnswer) 
        {
                showToast("Please select or type an answer.", "error");
                return;
        }

        const csrfToken = document.querySelector('input[name="csrf_token"]').value;

        fetch('/check-answer', {
                method: 'POST',
                headers: {
                        'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                        course_id: courseId,
                        lesson_num: document.querySelector('.lesson-badge').innerText.match(/\d+/)[0],
                        sublesson_index: index,
                        answer: userAnswer,
                        csrf_token: csrfToken
                })
        })
        .then(response => response.json())
        .then(data => {
                if (data.success && data.correct) 
                {
                        feedback.className = "feedback-area correct";
                        feedback.innerText = "Correct! Well done.";
                        feedback.style.display = "block";
                        feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                        btn.innerText = (currentSlide === totalSlides - 1) ? "Finish Lesson" : "Continue";
                        btn.classList.remove('btn-check');
                } 
                else if (data.success && !data.correct)
                {
                        feedback.className = "feedback-area incorrect";
                        feedback.innerText = "Incorrect. Try again. (-1 RAM)";
                        feedback.style.display = "block";
                        feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                        const ramDisplay = document.querySelector('.ram-count');
                        if (ramDisplay)
                        {
                                ramDisplay.innerText = data.ram + ' RAM';
                                ramDisplay.style.color = '#ef4444';
                                setTimeout(() => { ramDisplay.style.color = ''; }, 500);
                        }

                        if (data.ram <= 0)
                        {
                                showToast("System Failure: 0 RAM. Recharge required.", "error");
                                setTimeout(() => {
                                        window.location.href = "/dashboard";
                                }, 2000);
                        }
                }
        })
        .catch(err => {
                console.error("Error verifying answer:", err);
                showToast("Technical error verifying answer.", "error");
        });
}

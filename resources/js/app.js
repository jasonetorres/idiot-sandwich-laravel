import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // --- Elements for Lead Capture ---
    const leadCaptureSection = document.getElementById('lead-capture-section');
    const submitLeadButton = document.getElementById('submit-lead-button');
    const leadError = document.getElementById('lead-error');
    
    // --- Elements for the submission form (`roast.blade.php`) ---
    const roastSection = document.getElementById('roast-section');
    const roastButton = document.getElementById('roastButton');
    const resumeText = document.getElementById('resumeText');
    const resumeFile = document.getElementById('resumeFile');
    const feedbackDiv = document.getElementById('feedback');
    const loadingDiv = document.getElementById('loading');
    const pasteTab = document.getElementById('paste-tab');
    const uploadTab = document.getElementById('upload-tab');
    const pasteContent = document.getElementById('paste-content');
    const uploadContent = document.getElementById('upload-content');
    let activeTab = 'paste';

    // --- Elements for the real-time monitor (`monitor.blade.php`) ---
    const liveRoasts = document.getElementById('live-roasts');

    // --- Helper Functions (GORDON_GIFS, etc.) remain the same ---
    const GORDON_GIFS = {
        terrible: "https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMDZ6dGFiaXRqc3JqMWxwc3FzajV1c2w1a3FtcHl0bDIwaWMxZXE2YSZlcD12MV9naWZzX3NlYXJjaCZjdD1n/3o85g2ttYzgw6o661q/giphy.gif",
        bad: "https://media.giphy.com/media/2kMQiSEW6Wkyh3YltH/giphy.gif?cid=790b7611s7gt5ui8e5zcyfzgqwyb8hib4o7liwbpo9hc1eg4&ep=v1_gifs_search&rid=giphy.gif&ct=g",
        average: "https://media.giphy.com/media/3o6ZtpvPW6fqxkE1xu/giphy.gif?cid=ecf05e47ahkihrnterpdo416b7xmcdteo7flc88kbcfev9uj&ep=v1_gifs_search&rid=giphy.gif&ct=g",
        good: "https://media.giphy.com/media/TIMB8bgHa1mb41tMaS/giphy.gif?cid=790b7611s7gt5ui8e5zcyfzgqwyb8hib4o7liwbpo9hc1eg4&ep=v1_gifs_search&rid=giphy.gif&ct=g",
        excellent: "https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExczdndDV1aThlNXpjeWZ6Z3F3eWI4aGliNG83bGl3YnBvOWhjMWVnNCZlcD12MV9naWZzX3NlYXJjaCZjdD1n/l0MYyoYPvz22wTXkQ/giphy.gif"
    };

    const getGifByRating = (rating) => {
        if (rating <= 1) return GORDON_GIFS.terrible;
        if (rating <= 2) return GORDON_GIFS.bad;
        if (rating <= 3) return GORDON_GIFS.average;
        if (rating <= 4) return GORDON_GIFS.good;
        return GORDON_GIFS.excellent;
    };

    const getRatingText = (rating) => {
        if (rating <= 1) return "YOU'RE AN IDIOT SANDWICH!";
        if (rating <= 2) return "IT'S BLOODY RAW!";
        if (rating <= 3) return "IT'S DECENT, BUT I'VE SEEN BETTER!";
        if (rating <= 4) return "NOW THAT'S MORE LIKE IT!";
        return "ABSOLUTELY BRILLIANT!";
    };
    
    // --- Logic for Lead Capture Submission ---
    if (submitLeadButton) {
        submitLeadButton.addEventListener('click', async () => {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const jobTitleInput = document.getElementById('job_title');
            
            if (!nameInput.value || !emailInput.value) {
                leadError.textContent = "Name and Email are required, you donut!";
                leadError.style.display = 'block';
                return;
            }

            const formData = new FormData();
            formData.append('name', nameInput.value);
            formData.append('email', emailInput.value);
            formData.append('job_title', jobTitleInput.value);
            
            try {
                const response = await fetch('/leads', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    leadCaptureSection.style.display = 'none';
                    roastSection.style.display = 'block';
                } else {
                     leadError.textContent = "Something went wrong. Please try again.";
                     leadError.style.display = 'block';
                }
            } catch (error) {
                console.error("Lead submission error:", error);
                leadError.textContent = "Could not connect to the server. Please check your connection.";
                leadError.style.display = 'block';
            }
        });
    }

    // --- Main function to handle the form submission ---
    const roastResume = async () => {
        let formData = new FormData();
        let headers = {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        };

        if (activeTab === 'paste') {
            if (!resumeText.value.trim()) {
                alert("WHERE'S THE RESUME?! I NEED SOMETHING TO WORK WITH, YOU DONUT!");
                return;
            }
            formData.append('resume_text', resumeText.value);
        } else {
            if (resumeFile.files.length === 0) {
                alert("WHERE'S THE RESUME?! UPLOAD A PDF, YOU DONUT!");
                return;
            }
            formData.append('resume_file', resumeFile.files[0]);
        }

        if (loadingDiv) loadingDiv.style.display = 'block';
        if (roastSection) roastSection.style.display = 'none'; // Hide the form while loading
        if (feedbackDiv) feedbackDiv.style.display = 'none';

        try {
            const response = await fetch('/roast', {
                method: 'POST',
                headers: headers,
                body: formData
            });
            await response.json(); // We don't need the data on this page, but we wait for the response
            
            // Show a success message
            if (feedbackDiv) feedbackDiv.style.display = 'block';

        } catch (error) {
            console.error('An error occurred in the roastResume function:', error);
            alert("The kitchen is in chaos! Something went wrong.");
        } finally {
            if (loadingDiv) loadingDiv.style.display = 'none';
            // Don't re-show the roast section, just the feedback.
        }
    };
    
    // --- Logic for the SUBMISSION FORM (`roast.blade.php`) ---
    if (roastButton) {
        roastButton.addEventListener('click', roastResume);

        pasteTab.addEventListener('click', () => {
            pasteTab.classList.add('active');
            uploadTab.classList.remove('active');
            if (pasteContent) pasteContent.style.display = 'block';
            if (uploadContent) uploadContent.style.display = 'none';
            activeTab = 'paste';
        });

        uploadTab.addEventListener('click', () => {
            uploadTab.classList.add('active');
            pasteTab.classList.remove('active');
            if (uploadContent) uploadContent.style.display = 'block';
            if (pasteContent) pasteContent.style.display = 'none';
            activeTab = 'upload';
        });
    }


    // --- Logic for the REAL-TIME MONITOR (`monitor.blade.php`) ---
    if (liveRoasts) {
        window.Echo.channel('roasts')
            .listen('.new-roast', (e) => {
                const analysis = e.analysis;
                const ratingMatch = analysis.match(/RATING:\s*(\d+)\/5/);
                const extractedRating = ratingMatch ? parseInt(ratingMatch[1]) : 3;
                const sections = analysis.split('\n\n').filter(section => !section.includes('RATING:'));
                const gifUrl = getGifByRating(extractedRating);

                let animationClass = 'fade-in';
                let prizeBannerHtml = ''; 

                if (extractedRating === 1) {
                    animationClass = 'fiery-flash';
                    prizeBannerHtml = `<div class="prize-banner prize-banner-idiot">Idiot Sandwich Award!</div>`;
                } else if (extractedRating === 5) {
                    animationClass = 'golden-glow';
                    prizeBannerHtml = `<div class="prize-banner prize-banner-gold">Golden Resume Winner!</div>`;
                }

                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    const starClass = i <= extractedRating ? 'star-filled' : 'star-empty';
                    starsHtml += `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="${starClass}"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007z" clip-rule="evenodd" /></svg>`;
                }

                const newRoastHtml = `
                    <div class="report-card ${animationClass}">
                        ${prizeBannerHtml}
                        <div class="report-card-content">
                            <div class="report-card-media">
                                <img src="${gifUrl}" alt="Gordon's Reaction">
                                <div class="stars-container">${starsHtml}</div>
                                <p class="rating-text">${getRatingText(extractedRating)}</p>
                            </div>
                            <div class="report-card-roast">
                                <h2 class="roast-title">The Official Verdict</h2>
                                <div class="roast-content-wrapper">
                                    ${sections.map(section => {
                                        const [title, ...content] = section.split('\n');
                                        return `<div class="roast-section">
                                                    <h3>${title.replace(/:/g, '')}</h3>
                                                    <p>${content.join('\n')}</p>
                                                </div>`;
                                    }).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                liveRoasts.innerHTML = newRoastHtml;
            });
    }
});

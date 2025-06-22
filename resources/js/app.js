document.addEventListener('DOMContentLoaded', () => {
    const roastButton = document.getElementById('roastButton');
    const clearButton = document.getElementById('clearButton');
    const resumeText = document.getElementById('resumeText');
    const feedbackDiv = document.getElementById('feedback');
    const feedbackContent = document.getElementById('feedbackContent');
    const ratingDiv = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');
    const ratingGif = document.getElementById('ratingGif');

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

    roastButton.addEventListener('click', async () => {
        if (!resumeText.value.trim()) {
            alert("WHERE'S THE RESUME?! I NEED SOMETHING TO WORK WITH, YOU DONUT!");
            return;
        }

        roastButton.textContent = 'COOKING UP FEEDBACK...';
        roastButton.disabled = true;

        try {
            const response = await fetch('/roast', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ resume_text: resumeText.value })
            });

            const data = await response.json();

            if (data.error) {
                feedbackContent.innerHTML = `<div class="bg-red-900/50 p-4 rounded-lg">${data.error}</div>`;
            } else {
                const analysis = data.analysis;
                const ratingMatch = analysis.match(/RATING:\s*(\d+)\/5/);
                const extractedRating = ratingMatch ? parseInt(ratingMatch[1]) : 3;

                const sections = analysis.split('\n\n').filter(section => !section.includes('RATING:'));
                feedbackContent.innerHTML = sections.map(section => {
                    const [title, ...content] = section.split('\n');
                    return `<div class="bg-red-900/50 p-4 rounded-lg">
                                <h3 class="font-bold mb-2">${title.replace(':', '')}</h3>
                                <p class="italic whitespace-pre-line">${content.join('\n')}</p>
                            </div>`;
                }).join('');

                ratingText.textContent = getRatingText(extractedRating);
                ratingGif.src = getGifByRating(extractedRating);
                ratingDiv.classList.remove('hidden');
            }

            feedbackDiv.classList.remove('hidden');
        } catch (error) {
            feedbackContent.innerHTML = `<div class="bg-red-900/50 p-4 rounded-lg">BLOODY HELL! The kitchen's on fire! Something went wrong with the analysis.</div>`;
            feedbackDiv.classList.remove('hidden');
        } finally {
            roastButton.textContent = 'ROAST IT!';
            roastButton.disabled = false;
        }
    });

    clearButton.addEventListener('click', () => {
        resumeText.value = '';
        feedbackDiv.classList.add('hidden');
        feedbackContent.innerHTML = '';
        ratingDiv.classList.add('hidden');
    });
});
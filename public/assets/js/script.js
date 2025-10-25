let kickBtn = document.getElementById('kickButton');

if (kickBtn) {
    kickBtn.addEventListener('click', () => {
        let kickImg      = document.getElementById('kicking');
        let slipImg      = document.getElementById('slipping');
        let countDisplay = document.getElementById('kickCount');
        let lastKick     = document.getElementById('lastKick');


        fetch('/kick/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                local_time: new Date().toISOString()
            })
        })
        .then(response => response.json())
        .then(data => {

            // Add a quick animation
            slipImg.style.display = 'none';
            kickImg.style.display = 'block';

            firstKick.textContent       = data.session_start;
            kickPeriod.textContent      = data.diff_hr_min;
            lastKick.textContent        = data.session_end;
            countDisplay.textContent    = data.total_kick;
            setTimeout(() => (
                kickImg.style.display = 'none',
                slipImg.style.display = 'block'
            ), 400);
            // console.log('Kick stored:', data.success);
        })
        .catch(
            error => console.error('Error storing kick:', error)
        );

    });
}


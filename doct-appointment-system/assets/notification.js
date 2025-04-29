// notification.js - Floating notification with sound, emoji, and OK button
// showNotification(title, message, emoji, id): plays sound and updates status on OK
function showNotification(title, message, emoji = '🚨', id = null) {
    let notif = document.createElement('div');
    notif.className = 'custom-firebase-toast big-bold-toast';
    notif.innerHTML = `<span class="notif-emoji">${emoji}</span> <strong style="font-size:1.3em;">${title}</strong><br>${message}<br><button class="notif-ok-btn">OK</button>`;
    if (id !== null) notif.setAttribute('data-id', id);
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.classList.add('show');
    }, 10);
    // Play sound
    let audio = document.createElement('audio');
    audio.src = '/assets/notification.mp3';
    audio.autoplay = true;
    notif.appendChild(audio);
    // OK button closes notification
    notif.querySelector('.notif-ok-btn').onclick = () => {
        // update status to 'notified' so won't reappear
        const eid = notif.getAttribute('data-id');
        if (eid) fetch('../api/acknowledge_emergency.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: eid})
        });
        notif.classList.remove('show');
        setTimeout(() => notif.remove(), 350);
    };
}
window.showNotification = showNotification;

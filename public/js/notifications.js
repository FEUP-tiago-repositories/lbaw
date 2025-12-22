document.addEventListener('DOMContentLoaded', function () {
    
    const style = document.createElement('style');
    style.innerHTML = `
        .swal2-icon.no-border {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .swal2-icon.no-border svg {
            margin: 0 !important;
        }
        .swal2-popup.clickable-toast {
            cursor: pointer !important;
        }
        .swal2-popup.clickable-toast:hover {
            filter: brightness(0.96);
        }
    `;
    document.head.appendChild(style);
    
    function checkNotifications() {
        fetch('/notifications/check')
            .then(response => response.json())
            .then(data => {
                if (!data.id) return;

                let storedId = sessionStorage.getItem('last_notification_id');

                if (data.id != storedId) {
                    
                    sessionStorage.setItem('last_notification_id', data.id);
                    
                    showPopup(data);
                }
            })
            .catch(error => console.error('Erro:', error));
    }

    function showPopup(notification) {
        if (typeof Swal === 'undefined') return;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 7000,
            timerProgressBar: false,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
                toast.addEventListener('click', () => {
                    window.location.href = '/notifications';
                });
            }
        });

        Toast.fire({
            title: notification.title,
            text: notification.content,
            iconHtml: notification.icon_html, 
            iconColor: notification.color,
            customClass: {
                icon: 'no-border',
                popup: 'clickable-toast'
    }
        });
    }

    setInterval(checkNotifications, 3000);
});
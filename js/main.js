// Tab switching for login/register
const tabBtns = document.querySelectorAll('.tab-btn');
tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        tabBtns.forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

// Room filter ( DOM filtering)
const filterBtns = document.querySelectorAll('.filter-btn');
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const filter = btn.dataset.filter;
        document.querySelectorAll('.room-card').forEach(card => {
            if (filter === 'all' || card.dataset.type === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    });
});

// Search rooms with AJAX
const searchInput = document.getElementById('search-input');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        const q = e.target.value;
        const container = document.getElementById('rooms-container');
        
        if (!q.trim()) {
            location.reload();
            return;
        }
        

        fetch('back/search.php?q=' + encodeURIComponent(q))
            .then(response => response.json())
            .then(rooms => {
                container.innerHTML = '';
                if (rooms.length === 0) {
                    container.innerHTML = '<p>Aucun résultat trouvé.</p>';
                    return;
                }
                rooms.forEach(room => {
                    const html = `
                        <article class="room-card" data-type="${room.type}">
                            <img src="${room.image}" alt="${room.name}">
                            <div class="card-body">
                                <span class="badge">${room.type}</span>
                                <h3>${room.name}</h3>
                                <p>Capacité: ${room.capacity} personnes</p>
                                <div class="card-footer">
                                    <strong>${room.price_per_night} TND/nuit</strong>
                                    <a href="booking.php?room_id=${room.id}" class="btn-primary">Réserver</a>
                                </div>
                            </div>
                        </article>
                    `;
                    container.innerHTML += html;
                });
            });
    });
}

// Check email with AJAX (during registration)
const regEmail = document.getElementById('reg-email');
if (regEmail) {
    regEmail.addEventListener('blur', () => {
        const email = regEmail.value.trim();
        if (!email) return;
        
        fetch('back/check_email.php?email=' + encodeURIComponent(email))
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    regEmail.style.borderColor = 'red';
                    regEmail.title = 'Email déjà utilisé';
                } else {
                    regEmail.style.borderColor = 'green';
                    regEmail.title = 'Email disponible';
                }
            });
    });
}

// Calculate total price for booking
function calculateTotal() {
    const checkInEl = document.getElementById('check-in');
    const checkOutEl = document.getElementById('check-out');
    const priceEl = document.getElementById('price-per-night');
    const totalEl = document.getElementById('total-price');
    
    if (!checkInEl || !checkOutEl || !priceEl || !totalEl) return;
    
    if (checkInEl.value && checkOutEl.value) {
        const start = new Date(checkInEl.value);
        const end = new Date(checkOutEl.value);
        
        if (end > start) {
            const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            const price = parseFloat(priceEl.textContent);
            totalEl.textContent = (nights * price).toFixed(2);
        }
    }
}

const checkIn = document.getElementById('check-in');
if (checkIn) {
    checkIn.addEventListener('change', calculateTotal);
}

const checkOut = document.getElementById('check-out');
if (checkOut) {
    checkOut.addEventListener('change', calculateTotal);
}

// Admin AJAX actions (dashboard)
document.querySelectorAll('.btn-confirm').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const id = btn.dataset.id;
        
        fetch('back/update_booking.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                id: id,
                status: 'confirmed'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('status-' + id).textContent = 'confirmed';
                document.getElementById('status-' + id).className = 'status-badge confirmed';
            }
        });
    });
});

document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!confirm('Supprimer cette réservation?')) return;
        
        const id = btn.dataset.id;
        
        fetch('back/delete_booking.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('row-' + id).remove();
            }
        });
    });
});


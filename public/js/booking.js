document.addEventListener("DOMContentLoaded", () => {
  if (!document.getElementById("step-1")) return;

  const ROOMS = window.ROOMS || {};

  const state = {
    checkin: null,
    checkout: null,
    guests: parseInt(document.getElementById("guests")?.value) || 2,
    room_id: window.SELECTED_ROOM_ID || "",
    nights: 0,
    price_per_night: 0,
    room_qty: 1,
    selected_rooms: {}, // map of roomId => { id, name, price, capacity, qty }
  };

  // Inisialisasi kamar jika ada dari URL / pilihan awal
  if (state.room_id && ROOMS[state.room_id]) {
    state.selected_rooms[state.room_id] = {
      id: state.room_id,
      name: ROOMS[state.room_id].name,
      price: ROOMS[state.room_id].price,
      capacity: ROOMS[state.room_id].capacity,
      qty: 1
    };
    state.price_per_night = ROOMS[state.room_id].price;
  }

  const stepEls = document.querySelectorAll(".step");
  const panels = {
    1: document.getElementById("step-1"),
    2: document.getElementById("step-2"),
  };

  const checkinEl = document.getElementById("checkin");
  const checkoutEl = document.getElementById("checkout");
  const guestsEl = document.getElementById("guests");
  const roomsGrid = document.getElementById("roomsGrid");

  const form_room_input = document.getElementById("form_room_id");
  const form_checkin = document.getElementById("form_checkin");
  const form_checkout = document.getElementById("form_checkout");
  const form_guests = document.getElementById("form_guests");
  const form_qty = document.getElementById("form_qty");
  const form_total = document.getElementById("form_total");
  const form_nights = document.getElementById("form_nights");
  const form_room_details = document.getElementById("form_room_details");

  const sumCheckin = document.getElementById("sum-checkin");
  const sumCheckout = document.getElementById("sum-checkout");
  const sumGuests = document.getElementById("sum-guests");
  const sumRoom = document.getElementById("sum-room");
  const sumPrice = document.getElementById("sum-price");
  const sumNights = document.getElementById("sum-nights");
  const sumQty = document.getElementById("sum-qty");
  const sumTotal = document.getElementById("sum-total");

  function idr(n) {
    if (isNaN(n) || !n) return "—";
    return "Rp " + Number(n).toLocaleString("id-ID", { maximumFractionDigits: 0 });
  }

  function computeNights(ci, co) {
    if (!ci || !co) return 0;
    const d1 = new Date(ci);
    const d2 = new Date(co);
    const diff = (d2 - d1) / (1000 * 60 * 60 * 24);
    return diff > 0 ? diff : 0;
  }

  function computeTotal() {
    if (!state.nights) return 0;
    const selectedKeys = Object.keys(state.selected_rooms);
    if (selectedKeys.length > 0) {
      let totalRatePerNight = 0;
      selectedKeys.forEach(k => {
        const item = state.selected_rooms[k];
        totalRatePerNight += (item.price * item.qty);
      });
      let baseTotal = totalRatePerNight * state.nights;
      if (state.discount_amount) baseTotal -= state.discount_amount;
      return baseTotal > 0 ? baseTotal : 0;
    }
    let baseTotal = state.nights * state.price_per_night * state.room_qty;
    if (state.discount_amount) baseTotal -= state.discount_amount;
    return baseTotal > 0 ? baseTotal : 0;
  }

  function formatDate(dateStr) {
    if (!dateStr) return "—";
    const d = new Date(dateStr);
    return d.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" });
  }

  function updateSummary() {
    if (sumCheckin) sumCheckin.textContent = state.checkin ? formatDate(state.checkin) : "—";
    if (sumCheckout) sumCheckout.textContent = state.checkout ? formatDate(state.checkout) : "—";
    if (sumGuests) sumGuests.textContent = state.guests + " Tamu";
    if (sumNights) sumNights.textContent = state.nights ? state.nights + " Malam" : "—";

    const selectedKeys = Object.keys(state.selected_rooms);
    let totalQty = 0;
    let totalRatePerNight = 0;

    if (selectedKeys.length > 0) {
      const roomNames = [];
      const qtyDetails = [];
      selectedKeys.forEach(k => {
        const item = state.selected_rooms[k];
        totalQty += item.qty;
        totalRatePerNight += (item.price * item.qty);
        roomNames.push(`<div style="font-weight:600;color:#0F172A;line-height:1.4;margin-bottom:4px;">• ${item.name}</div>`);
        qtyDetails.push(`${item.qty}x ${item.name}`);
      });

      if (sumRoom) sumRoom.innerHTML = roomNames.join("");
      if (sumQty) sumQty.innerHTML = `<strong style="color:#0F172A;font-size:0.95rem;">${totalQty} Kamar</strong> <span style="font-size:0.82rem;color:#9E7D3B;display:block;margin-top:2px;font-weight:600;">(${qtyDetails.join(", ")})</span>`;
      if (sumPrice) sumPrice.textContent = idr(totalRatePerNight) + " / mlm";
    } else {
      if (sumRoom) sumRoom.textContent = state.room_id ? (ROOMS[state.room_id]?.name || "Kamar Terpilih") : "—";
      if (sumQty) sumQty.textContent = state.room_qty + " Kamar";
      if (sumPrice) sumPrice.textContent = state.price_per_night ? idr(state.price_per_night) + " / mlm" : "—";
      totalQty = state.room_qty;
    }

    const total = computeTotal();
    
    if (sumTotal) {
      if (state.discount_amount && state.discount_amount > 0) {
        sumTotal.innerHTML = `<span style="font-size:0.85rem; color:#b91c1c; display:block; text-decoration:line-through; font-weight:normal;">${idr(total + state.discount_amount)}</span> ${idr(total)}`;
      } else {
        sumTotal.textContent = total ? idr(total) : "—";
      }
    }

    if (form_checkin) form_checkin.value = state.checkin || "";
    if (form_checkout) form_checkout.value = state.checkout || "";
    if (form_guests) form_guests.value = state.guests || "";
    if (form_room_input) form_room_input.value = selectedKeys[0] || state.room_id || "";
    if (form_qty) form_qty.value = totalQty || 1;
    if (form_total) form_total.value = total || 0;
    if (form_nights) form_nights.value = state.nights || 0;
    if (form_room_details) form_room_details.value = JSON.stringify(Object.values(state.selected_rooms));
  }

  // ✅ Perbaikan Navigasi Step (Biar ga loncat ke paling atas)
  window.goToStep = function(n) {
    Object.values(panels).forEach((p) => p && (p.style.display = "none"));
    if (panels[n]) {
      panels[n].style.display = "block";
      // Scroll smoothly tepat ke kontainer form reservasi, bukan ke window top 0
      const wrapper = document.querySelector(".booking-container") || panels[n];
      wrapper.scrollIntoView({ behavior: "smooth", block: "start" });
    }
    stepEls.forEach((s) => {
      s.classList.toggle("active", parseInt(s.getAttribute("data-step")) === n);
    });
  };

  // ✅ Fungsi global untuk +/- jumlah kamar di setiap kartu
  window.changeRoomQty = function(roomId, delta) {
    const dispEl = document.getElementById("qty-disp-" + roomId);
    if (!dispEl) return;
    let curr = parseInt(dispEl.textContent) || 1;
    
    // Cek batas stok real-time saat ini
    if (delta > 0 && state.availability && state.availability[roomId]) {
      const avail = state.availability[roomId].available;
      if (curr + delta > avail) {
        alert(`Maaf, tipe kamar ini hanya tersisa ${avail} unit untuk tanggal yang dipilih (${state.checkin} s/d ${state.checkout}).`);
        return;
      }
    }

    curr += delta;
    if (curr < 1) curr = 1;
    if (curr > 10) curr = 10;
    dispEl.textContent = curr;
    
    // Jika kamar sudah terpilih, langsung update kuantitas di state multi-room
    if (state.selected_rooms[roomId]) {
      state.selected_rooms[roomId].qty = curr;
      updateVisualCards();
      updateSummary();
    } else {
      window.selectRoomCard(roomId, curr);
    }
  };

  // ✅ Fungsi global untuk memilih / membatalkan kartu kamar (Multi-room support)
  window.selectRoomCard = function(roomId, forceQty) {
    if (!roomsGrid) return;
    const card = roomsGrid.querySelector(`.room-card[data-room="${roomId}"]`);
    if (!card) return;

    if (!state.selected_rooms[roomId] && state.availability && state.availability[roomId]) {
      const avail = state.availability[roomId].available;
      if (avail <= 0) {
        alert("Maaf, tipe kamar ini sudah penuh (Fully Booked) untuk tanggal yang dipilih.");
        return;
      }
    }

    const price = parseFloat(card.getAttribute("data-price")) || 0;
    const capacity = parseInt(card.getAttribute("data-capacity")) || 2;
    const roomName = card.querySelector("h4")?.textContent?.trim() || (ROOMS[roomId]?.name || "Kamar Hotel");
    const dispEl = document.getElementById("qty-disp-" + roomId);
    const qty = forceQty || (dispEl ? parseInt(dispEl.textContent) || 1 : 1);

    // Toggle multi-room: Jika diklik pada kamar yang sudah terpilih & bukan dari tombol +/-, bisa dibatalkan jika ada kamar lain
    if (state.selected_rooms[roomId] && !forceQty && Object.keys(state.selected_rooms).length > 1) {
      delete state.selected_rooms[roomId];
    } else {
      state.selected_rooms[roomId] = {
        id: roomId,
        name: roomName,
        price: price,
        capacity: capacity,
        qty: qty
      };
      state.room_id = roomId;
      state.price_per_night = price;
      state.room_qty = qty;
    }

    updateVisualCards();
    updateSummary();
  };

  function updateVisualCards() {
    if (!roomsGrid) return;
    roomsGrid.querySelectorAll(".room-card").forEach((card) => {
      const cId = card.getAttribute("data-room");
      const isSel = !!state.selected_rooms[cId];
      card.classList.toggle("selected", isSel);

      const badge = card.querySelector(".badge-selected");
      const btn = card.querySelector(".btn-select-room");

      if (isSel) {
        const itemQty = state.selected_rooms[cId].qty;
        if (badge) {
          badge.style.display = "inline-flex";
          badge.innerHTML = `<i class="bi bi-patch-check-fill"></i> TERPILIH (${itemQty} KAMAR)`;
        }
        if (btn) {
          btn.innerHTML = `<i class="bi bi-check-circle-fill"></i> Terpilih (${itemQty} Kamar)`;
          btn.style.background = "#0F172A";
          btn.style.color = "#FFFFFF";
        }
      } else {
        if (badge) badge.style.display = "none";
        if (btn) {
          btn.innerHTML = `<i class="bi bi-check2-circle"></i> Pilih Kamar Ini`;
          btn.style.background = "";
          btn.style.color = "";
        }
      }
    });
  }

  async function fetchRoomAvailability() {
    const checkinVal = state.checkin || checkinEl?.value;
    const checkoutVal = state.checkout || checkoutEl?.value;
    if (!checkinVal || !checkoutVal) return;

    try {
      const res = await fetch(`/booking/check-availability?checkin=${checkinVal}&checkout=${checkoutVal}`);
      const data = await res.json();
      state.availability = data;

      Object.keys(data).forEach((roomId) => {
        const stock = data[roomId];
        const badgeEl = document.getElementById(`stock-badge-${roomId}`);
        const cardEl = document.querySelector(`.room-card[data-room="${roomId}"]`);
        if (!badgeEl || !cardEl) return;

        const btnSelect = cardEl.querySelector(".btn-select-room");

        if (stock.is_full || stock.available <= 0) {
          badgeEl.innerHTML = `<span style="background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:12px;border:1px solid #fca5a5;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-x-circle-fill"></i> FULLY BOOKED (0 Tersedia)</span>`;
          cardEl.style.opacity = "0.7";
          if (btnSelect) {
            btnSelect.disabled = true;
            btnSelect.innerHTML = `<i class="bi bi-ban"></i> Habis Terpesan`;
            btnSelect.style.background = "#cbd5e1";
            btnSelect.style.color = "#475569";
            btnSelect.style.cursor = "not-allowed";
          }
          if (state.selected_rooms[roomId]) {
            delete state.selected_rooms[roomId];
            updateSummary();
            updateVisualCards();
          }
        } else if (stock.available <= 3) {
          badgeEl.innerHTML = `<span style="background:#fef9c3;color:#854d0e;padding:4px 10px;border-radius:12px;border:1px solid #fde047;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-exclamation-triangle-fill"></i> Sisa ${stock.available} Kamar!</span>`;
          cardEl.style.opacity = "1";
          enableSelectBtn(btnSelect);
        } else {
          badgeEl.innerHTML = `<span style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:12px;border:1px solid #86efac;display:inline-flex;align-items:center;gap:4px;"><i class="bi bi-check-circle-fill"></i> Tersedia (Sisa ${stock.available} Kamar)</span>`;
          cardEl.style.opacity = "1";
          enableSelectBtn(btnSelect);
        }
      });
    } catch (err) {
      console.error("Gagal mengecek ketersediaan kamar:", err);
    }
  }

  function enableSelectBtn(btnSelect) {
    if (btnSelect && btnSelect.disabled) {
      btnSelect.disabled = false;
      btnSelect.innerHTML = `<i class="bi bi-check2-circle"></i> Pilih Kamar Ini`;
      btnSelect.style.background = "";
      btnSelect.style.color = "";
      btnSelect.style.cursor = "pointer";
    }
  }

  window.changeGuestQty = function(delta) {
    let current = parseInt(guestsEl.value) || 1;
    let next = current + delta;
    if (next < 1) next = 1;
    if (next > 20) next = 20;
    guestsEl.value = next;
    document.getElementById("guest-disp").innerText = next + " Tamu" + (next === 20 ? " / Lebih" : "");
    state.guests = next;
    updateSummary();
    filterRooms();
  };

  // Filter tamu & kapasitas (Untuk multi room kita tetap tampilkan semua atau filter by cap)
  function filterRooms() {
    if (!roomsGrid) return;
    const cards = roomsGrid.querySelectorAll(".room-card");
    cards.forEach((card) => {
      const roomCap = parseInt(card.getAttribute("data-capacity")) || 0;
      if (roomCap >= state.guests || Object.keys(state.selected_rooms).length > 0) {
        card.style.display = "";
      } else {
        card.style.display = "none";
      }
    });
  }

  // Init min date hari ini
  const today = new Date().toISOString().split("T")[0];
  if (checkinEl) checkinEl.setAttribute("min", today);
  if (checkoutEl) checkoutEl.setAttribute("min", today);

  // Set default checkin hari ini & checkout besok jika belum ada
  if (checkinEl && !checkinEl.value) {
    checkinEl.value = today;
    state.checkin = today;
    const tmr = new Date();
    tmr.setDate(tmr.getDate() + 1);
    const tmrStr = tmr.toISOString().split("T")[0];
    if (checkoutEl && !checkoutEl.value) {
      checkoutEl.value = tmrStr;
      state.checkout = tmrStr;
    }
    state.nights = computeNights(state.checkin, state.checkout);
  }

  // Events
  if (checkinEl) {
    checkinEl.addEventListener("change", (e) => {
      state.checkin = e.target.value;
      if (state.checkin) {
        const minOut = new Date(state.checkin);
        minOut.setDate(minOut.getDate() + 1);
        const minOutStr = minOut.toISOString().split("T")[0];
        if (checkoutEl) checkoutEl.setAttribute("min", minOutStr);

        if (checkoutEl && checkoutEl.value && new Date(checkoutEl.value) <= new Date(state.checkin)) {
          checkoutEl.value = minOutStr;
          state.checkout = minOutStr;
        }
      }
      state.nights = computeNights(state.checkin, state.checkout);
      updateSummary();
      fetchRoomAvailability();
    });
  }

  if (checkoutEl) {
    checkoutEl.addEventListener("change", (e) => {
      state.checkout = e.target.value;
      state.nights = computeNights(state.checkin, state.checkout);
      updateSummary();
      fetchRoomAvailability();
    });
  }

  if (guestsEl) {
    guestsEl.addEventListener("change", (e) => {
      state.guests = parseInt(e.target.value) || 2;
      filterRooms();
      updateSummary();
    });
  }

  // Step Navigation
  document.getElementById("to-step-2")?.addEventListener("click", () => {
    if (!state.checkin || !state.checkout) {
      alert("Silakan pilih tanggal Check-in dan Check-out terlebih dahulu.");
      if (checkinEl) checkinEl.focus();
      return;
    }
    if (state.nights <= 0) {
      alert("Tanggal Check-out harus minimal 1 hari setelah Check-in.");
      if (checkoutEl) checkoutEl.focus();
      return;
    }
    if (Object.keys(state.selected_rooms).length === 0 && !state.room_id) {
      alert("Silakan pilih minimal 1 tipe kamar favorit Anda dari katalog kamar di bawah terlebih dahulu dengan mengklik tombol Pilih Kamar Ini.");
      if (roomsGrid) roomsGrid.scrollIntoView({ behavior: "smooth" });
      return;
    }

    // Cek kesesuaian jumlah tamu vs kamar yang dipesan
    const selectedKeys = Object.keys(state.selected_rooms);
    let totalQty = 0;
    let totalCap = 0;
    if (selectedKeys.length > 0) {
      selectedKeys.forEach(k => {
        const item = state.selected_rooms[k];
        totalQty += item.qty;
        totalCap += (item.capacity * item.qty);
      });
    } else {
      totalQty = state.room_qty || 1;
      const rCap = ROOMS[state.room_id]?.capacity || 2;
      totalCap = rCap * totalQty;
    }

    if (state.guests < totalQty) {
      alert(`Perhatian: Anda memesan ${totalQty} kamar, namun jumlah tamu diisi hanya ${state.guests} orang. Minimal harus ada 1 tamu per kamar. Sistem akan menyesuaikan jumlah tamu Anda menjadi ${totalQty} orang.`);
      state.guests = totalQty;
      if (guestsEl) guestsEl.value = totalQty;
      updateSummary();
    } else if (state.guests > totalCap) {
      alert(`Maaf, total kapasitas maksimal untuk ${totalQty} kamar yang Anda pilih adalah ${totalCap} tamu, sedangkan Anda membawa ${state.guests} tamu. Silakan tambah jumlah kamar atau pilih tipe kamar dengan kapasitas lebih besar agar kenyamanan menginap terjaga.`);
      return;
    }

    goToStep(2);
  });

  document.getElementById("back-to-1")?.addEventListener("click", () => {
    goToStep(1);
  });

  document.getElementById("bookingForm")?.addEventListener("submit", (e) => {
    const name = document.getElementById("full_name");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");
    if (!name?.value.trim() || !email?.value.trim() || !phone?.value.trim()) {
      e.preventDefault();
      alert("Silakan lengkapi Nama Lengkap, Email, dan Nomor Handphone Anda sebelum melakukan konfirmasi.");
      return false;
    }
    return true;
  });

  const btnCheckPromo = document.getElementById("btn-check-promo");
  if (btnCheckPromo) {
    btnCheckPromo.addEventListener("click", async () => {
      const code = document.getElementById("promo_code").value.trim();
      const msg = document.getElementById("promo-message");
      if (!code) return;
      
      msg.style.color = "#475569";
      msg.textContent = "Memeriksa kode promo...";
      
      try {
        // Base total without discount
        const originalTotal = (computeTotal() + (state.discount_amount || 0));
        const res = await fetch(`/api/check-promo?code=${code}&total=${originalTotal}`);
        const data = await res.json();
        
        if (data.valid) {
          state.discount_amount = data.discount;
          msg.style.color = "#15803d";
          msg.innerHTML = `<i class="bi bi-check-circle-fill"></i> Promo berhasil diterapkan! Diskon: ${idr(data.discount)}`;
          updateSummary();
        } else {
          state.discount_amount = 0;
          msg.style.color = "#b91c1c";
          msg.innerHTML = `<i class="bi bi-x-circle-fill"></i> ${data.message || "Kode promo tidak valid."}`;
          updateSummary();
        }
      } catch (err) {
        msg.style.color = "#b91c1c";
        msg.textContent = "Terjadi kesalahan saat memeriksa promo.";
      }
    });
  }

  // Init jika ada room terpilih
  if (state.room_id && roomsGrid) {
    window.selectRoomCard(state.room_id);
  } else {
    updateVisualCards();
  }

  updateSummary();
  filterRooms();
  fetchRoomAvailability();
  goToStep(1);
});
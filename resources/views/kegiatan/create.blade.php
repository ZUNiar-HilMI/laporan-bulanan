@extends('layouts.app')

@section('title', 'Lapor Kegiatan')

@section('content')

<h2 class="mb-4">Melaporkan Kegiatan</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="POST" action="/kegiatan" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Sebelum <small class="text-muted">(Max 3MB)</small></label>
                    <input type="file" name="foto_sebelum" class="form-control" accept="image/*">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto Sesudah <small class="text-muted">(Max 3MB)</small></label>
                    <input type="file" name="foto_sesudah" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Anggaran Digunakan</label>
                <input type="number" name="anggaran" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi Kegiatan</label>
                <button type="button" id="getLocationBtn" class="btn btn-success w-100 mb-3" onclick="getCurrentLocation()">
                    <span id="locationBtnIcon">📍</span>
                    <span id="locationBtnText">Gunakan Lokasi Saya (GPS)</span>
                </button>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" readonly>
                </div>
            </div>
            <div id="map" style="height: 350px; border-radius: 8px;"></div>
            <small class="text-muted d-block mt-2">💡 Klik tombol GPS di atas atau klik langsung pada peta untuk menentukan lokasi</small>

            <button class="btn btn-primary mt-3">
                Simpan Laporan
            </button>

        </form>

    </div>
</div>

<script>
let map, marker;

document.addEventListener("DOMContentLoaded", function () {

    if (!document.getElementById('map')) return;

    let defaultLat = -6.200000;
    let defaultLng = 106.816666;

    map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    function setLatLng(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    }

    setLatLng(defaultLat, defaultLng);

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        setLatLng(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function () {
        let pos = marker.getLatLng();
        setLatLng(pos.lat, pos.lng);
    });

    // SEARCH BOX
    L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        let center = e.geocode.center;
        map.setView(center, 16);
        marker.setLatLng(center);
        setLatLng(center.lat, center.lng);
    })
    .addTo(map);

    // Make setLatLng available globally
    window.setLatLng = setLatLng;

});

function getCurrentLocation() {
    const btn = document.getElementById('getLocationBtn');
    const btnIcon = document.getElementById('locationBtnIcon');
    const btnText = document.getElementById('locationBtnText');
    
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung GPS/Geolocation');
        return;
    }

    // Show loading state
    btn.disabled = true;
    btnIcon.textContent = '⏳';
    btnText.textContent = 'Mencari lokasi...';
    btn.classList.remove('btn-success');
    btn.classList.add('btn-secondary');

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Update map and marker
            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
            window.setLatLng(lat, lng);
            
            // Success state
            btnIcon.textContent = '✅';
            btnText.textContent = 'Lokasi ditemukan!';
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-success');
            
            // Reset button after 2 seconds
            setTimeout(function() {
                btn.disabled = false;
                btnIcon.textContent = '📍';
                btnText.textContent = 'Gunakan Lokasi Saya (GPS)';
            }, 2000);
        },
        function(error) {
            // Error handling
            btn.disabled = false;
            btnIcon.textContent = '❌';
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-danger');
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    btnText.textContent = 'Akses lokasi ditolak. Izinkan di browser.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    btnText.textContent = 'Lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    btnText.textContent = 'Waktu habis. Coba lagi.';
                    break;
                default:
                    btnText.textContent = 'Gagal mendapatkan lokasi.';
            }
            
            // Reset button after 3 seconds
            setTimeout(function() {
                btnIcon.textContent = '📍';
                btnText.textContent = 'Gunakan Lokasi Saya (GPS)';
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-success');
            }, 3000);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}
</script>

@endsection

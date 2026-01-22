@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<!-- Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<style>
    .profile-card {
        max-width: 500px;
        margin: 0 auto;
    }
    
    .profile-photo-section {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 4rem;
        color: white;
        border: 4px solid rgba(255,255,255,0.3);
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .profile-photo:hover {
        transform: scale(1.05);
    }
    
    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-photo-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.6);
        color: white;
        padding: 8px;
        font-size: 0.75rem;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .profile-photo:hover .profile-photo-overlay {
        opacity: 1;
    }
    
    .profile-name-display {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .profile-role-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 8px;
    }
    
    .profile-role-badge.admin {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: white;
    }
    
    .profile-role-badge.anggota {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
    }
    
    .profile-details {
        padding: 30px;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: var(--toggle-bg);
        border-radius: 12px;
        margin-bottom: 12px;
    }
    
    .detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .detail-content {
        flex: 1;
    }
    
    .detail-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 2px;
    }
    
    .detail-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Cropper Modal */
    .cropper-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .cropper-modal {
        background: var(--bg-card);
        border-radius: 20px;
        max-width: 500px;
        width: 95%;
        overflow: hidden;
    }

    .cropper-header {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        padding: 16px 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cropper-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .cropper-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
    }

    .cropper-body {
        padding: 20px;
    }

    .cropper-container-wrapper {
        width: 100%;
        height: 300px;
        background: #1a1a2e;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .cropper-container-wrapper img {
        max-width: 100%;
        display: block;
    }

    .cropper-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px;
        background: var(--toggle-bg);
        border-radius: 12px;
    }

    .zoom-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        min-width: 50px;
    }

    .zoom-slider {
        flex: 1;
        height: 6px;
        -webkit-appearance: none;
        background: var(--border-color);
        border-radius: 3px;
        outline: none;
    }

    .zoom-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        border-radius: 50%;
        cursor: pointer;
    }

    .zoom-buttons {
        display: flex;
        gap: 8px;
    }

    .zoom-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: var(--bg-secondary);
        color: var(--text-primary);
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .zoom-btn:hover {
        background: #7c3aed;
        color: white;
    }

    .cropper-actions {
        display: flex;
        gap: 12px;
    }

    .cropper-tip {
        text-align: center;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    /* Circle crop overlay */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 500px; margin: 0 auto 20px;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="max-width: 500px; margin: 0 auto 20px;">
    @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card profile-card">
    <div class="profile-photo-section">
        <div class="profile-photo" onclick="document.getElementById('avatarInput').click()">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil">
            @else
                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            @endif
            <div class="profile-photo-overlay">📷 Ubah Foto</div>
        </div>
        <input type="file" id="avatarInput" accept="image/*" style="display: none;" onchange="openCropper(this)">
        
        <h2 class="profile-name-display">{{ $user->name }}</h2>
        <span class="profile-role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
        <p style="color: rgba(255,255,255,0.6); font-size: 0.75rem; margin-top: 8px;">Klik foto untuk mengubah (Max: 3MB)</p>
    </div>
    
    <div class="profile-details">
        <div class="detail-item">
            <div class="detail-icon">👤</div>
            <div class="detail-content">
                <div class="detail-label">Nama Lengkap</div>
                <div class="detail-value">{{ $user->name }}</div>
            </div>
            <button class="btn btn-sm btn-outline-primary" onclick="openNameModal()">✏️ Edit</button>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">📧</div>
            <div class="detail-content">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $user->email }}</div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">🎭</div>
            <div class="detail-content">
                <div class="detail-label">Role</div>
                <div class="detail-value">{{ ucfirst($user->role) }}</div>
            </div>
        </div>
        
        <div class="detail-item">
            <div class="detail-icon">📅</div>
            <div class="detail-content">
                <div class="detail-label">Bergabung Sejak</div>
                <div class="detail-value">{{ $user->created_at->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Name Edit Modal -->
<div id="nameModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: 16px; max-width: 400px; width: 90%; padding: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h5 style="margin: 0;">✏️ Edit Nama</h5>
            <button onclick="closeNameModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="/profile/update-name" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required maxlength="255">
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" onclick="closeNameModal()">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNameModal() {
    document.getElementById('nameModal').style.display = 'flex';
}

function closeNameModal() {
    document.getElementById('nameModal').style.display = 'none';
}

document.getElementById('nameModal').addEventListener('click', function(e) {
    if (e.target === this) closeNameModal();
});
</script>

<!-- Cropper Modal -->
<div id="cropperModal" class="cropper-modal-overlay">
    <div class="cropper-modal">
        <div class="cropper-header">
            <h5>✂️ Atur Foto Profil</h5>
            <button class="cropper-close" onclick="closeCropper()">&times;</button>
        </div>
        <div class="cropper-body">
            <div class="cropper-container-wrapper">
                <img id="cropperImage" src="">
            </div>
            
            <div class="cropper-tip">
                💡 Geser foto untuk memposisikan, gunakan slider/tombol untuk zoom
            </div>
            
            <div class="cropper-controls">
                <span class="zoom-label">🔍 Zoom</span>
                <input type="range" id="zoomSlider" class="zoom-slider" min="0.1" max="3" step="0.1" value="1" oninput="setZoom(this.value)">
                <div class="zoom-buttons">
                    <button type="button" class="zoom-btn" onclick="zoomOut()">−</button>
                    <button type="button" class="zoom-btn" onclick="zoomIn()">+</button>
                    <button type="button" class="zoom-btn" onclick="rotateImage()" title="Putar">↻</button>
                </div>
            </div>
            
            <div class="cropper-actions">
                <button type="button" class="btn btn-secondary" onclick="closeCropper()">
                    ❌ Batal
                </button>
                <button type="button" class="btn btn-primary" onclick="cropAndUpload()">
                    ✅ Gunakan Foto Ini
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for upload -->
<form id="avatarForm" action="/profile/update-avatar" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="file" id="croppedImage" name="avatar">
</form>

<script>
let cropper = null;

function openCropper(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 3MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const cropperImage = document.getElementById('cropperImage');
            cropperImage.src = e.target.result;
            
            document.getElementById('cropperModal').style.display = 'flex';
            
            cropperImage.onload = function() {
                if (cropper) {
                    cropper.destroy();
                }
                
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 0,
                    dragMode: 'move',
                    autoCropArea: 1,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                    guides: false,
                    center: true,
                    highlight: false,
                    background: true,
                    modal: true,
                    ready: function() {
                        // Make crop area circular
                        const cropBox = document.querySelector('.cropper-view-box');
                        const face = document.querySelector('.cropper-face');
                        const cropBoxElem = document.querySelector('.cropper-crop-box');
                        
                        if (cropBox) cropBox.style.borderRadius = '50%';
                        if (face) face.style.borderRadius = '50%';
                        if (cropBoxElem) cropBoxElem.style.borderRadius = '50%';
                    }
                });
                
                document.getElementById('zoomSlider').value = 1;
            };
        };
        reader.readAsDataURL(file);
    }
}

function closeCropper() {
    document.getElementById('cropperModal').style.display = 'none';
    document.getElementById('avatarInput').value = '';
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function setZoom(value) {
    if (cropper) {
        cropper.zoomTo(parseFloat(value));
    }
}

function zoomIn() {
    if (cropper) {
        cropper.zoom(0.1);
    }
}

function zoomOut() {
    if (cropper) {
        cropper.zoom(-0.1);
    }
}

function rotateImage() {
    if (cropper) {
        cropper.rotate(90);
    }
}

function cropAndUpload() {
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
    });
    
    canvas.toBlob(function(blob) {
        const file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('croppedImage').files = dataTransfer.files;
        document.getElementById('avatarForm').submit();
    }, 'image/jpeg', 0.9);
}

document.getElementById('cropperModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCropper();
    }
});
</script>

@endsection

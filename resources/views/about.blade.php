@extends('layouts.header')

@section('css')
<style>
    .client-about-page {
        margin-top: 90px !important;
    }
    .about-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 2rem;
    }
    
    .image-wrapper {
        position: relative;
        max-width: 100%;
        cursor: pointer;
    }
    
    .about-image {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .about-image:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    
    .zoom-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        backdrop-filter: blur(5px);
    }
    
    .zoom-modal.active {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }
    
    .zoom-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .zoom-image {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
        cursor: grab;
    }
    
    .zoom-image:active {
        cursor: grabbing;
    }
    
    .close-btn {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 1001;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    
    .close-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .zoom-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 1001;
    }
    
    .zoom-btn {
        background: rgba(0,0,0,0.7);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 18px;
        transition: background 0.3s ease;
    }
    
    .zoom-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .zoom-hint {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .image-wrapper:hover .zoom-hint {
        opacity: 1;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @media (max-width: 768px) {
        .about-container {
            padding: 1rem;
            min-height: 50vh;
        }
        
        .zoom-content {
            max-width: 95%;
            max-height: 80%;
        }
        
        .close-btn {
            top: 10px;
            right: 15px;
            font-size: 30px;
            width: 40px;
            height: 40px;
        }
        
        .zoom-controls {
            bottom: 10px;
        }
        
        .zoom-btn {
            padding: 8px 12px;
            font-size: 16px;
        }
        
        .zoom-hint {
            display: block;
            opacity: 1;
            animation: pulse 2s infinite;
        }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 1; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid @if(auth()->user()->role === 'Client') client-about-page @endif">
    <div class="about-container">
        <div class="image-wrapper" onclick="openZoom()">
            <img src="{{ asset('images/ABOUT.png') }}" 
                 alt="About Us" 
                 class="about-image"
                 id="aboutImage"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            
            <div class="zoom-hint">
                <iconify-icon icon="solar:magnifer-zoom-in-outline"></iconify-icon>
                Tap to zoom
            </div>
            
            <div style="display:none; text-center; color: #6c757d;">
                <iconify-icon icon="solar:image-broken-line-duotone" style="font-size: 4rem; margin-bottom: 1rem;"></iconify-icon>
                <p>About image not found</p>
            </div>
        </div>
    </div>
</div>

<div id="zoomModal" class="zoom-modal" onclick="closeZoom(event)">
    <span class="close-btn" onclick="closeZoom(event)">&times;</span>
    
    <div class="zoom-content" onclick="event.stopPropagation()">
        <img src="{{ asset('images/ABOUT.png') }}" 
             alt="About Us" 
             class="zoom-image" 
             id="zoomedImage">
    </div>
    
    <div class="zoom-controls">
        <button class="zoom-btn" onclick="zoomIn()">
            <iconify-icon icon="solar:magnifer-zoom-in-outline"></iconify-icon>
        </button>
        <button class="zoom-btn" onclick="resetZoom()">
            <iconify-icon icon="solar:restart-outline"></iconify-icon>
        </button>
        <button class="zoom-btn" onclick="zoomOut()">
            <iconify-icon icon="solar:magnifer-zoom-out-outline"></iconify-icon>
        </button>
    </div>
</div>

<script>
let currentZoom = 1;
let isDragging = false;
let startX, startY, translateX = 0, translateY = 0;

function openZoom() {
    const modal = document.getElementById('zoomModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    resetZoom();
}

function closeZoom(event) {
    if (event) event.stopPropagation();
    const modal = document.getElementById('zoomModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function zoomIn() {
    currentZoom = Math.min(currentZoom * 1.3, 5);
    updateZoom();
}

function zoomOut() {
    currentZoom = Math.max(currentZoom / 1.3, 0.5);
    updateZoom();
}

function resetZoom() {
    currentZoom = 1;
    translateX = 0;
    translateY = 0;
    updateZoom();
}

function updateZoom() {
    const zoomedImage = document.getElementById('zoomedImage');
    zoomedImage.style.transform = `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
}

const zoomedImage = document.getElementById('zoomedImage');

zoomedImage.addEventListener('mousedown', startDrag);
document.addEventListener('mousemove', drag);
document.addEventListener('mouseup', endDrag);

zoomedImage.addEventListener('touchstart', handleTouchStart, { passive: false });
document.addEventListener('touchmove', handleTouchMove, { passive: false });
document.addEventListener('touchend', endDrag);

function startDrag(e) {
    if (currentZoom <= 1) return;
    isDragging = true;
    startX = e.clientX - translateX;
    startY = e.clientY - translateY;
    zoomedImage.style.cursor = 'grabbing';
}

function handleTouchStart(e) {
    e.preventDefault();
    if (currentZoom <= 1) return;
    if (e.touches.length === 1) {
        isDragging = true;
        startX = e.touches[0].clientX - translateX;
        startY = e.touches[0].clientY - translateY;
    }
}

function drag(e) {
    if (!isDragging || currentZoom <= 1) return;
    e.preventDefault();
    translateX = e.clientX - startX;
    translateY = e.clientY - startY;
    updateZoom();
}

function handleTouchMove(e) {
    if (!isDragging || currentZoom <= 1) return;
    e.preventDefault();
    if (e.touches.length === 1) {
        translateX = e.touches[0].clientX - startX;
        translateY = e.touches[0].clientY - startY;
        updateZoom();
    }
}

function endDrag() {
    isDragging = false;
    zoomedImage.style.cursor = 'grab';
}

document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('zoomModal');
    if (!modal.classList.contains('active')) return;
    
    switch(e.key) {
        case 'Escape':
            closeZoom();
            break;
        case '+':
        case '=':
            zoomIn();
            break;
        case '-':
            zoomOut();
            break;
        case '0':
            resetZoom();
            break;
    }
});

let initialDistance = 0;
let initialZoom = 1;

zoomedImage.addEventListener('touchstart', function(e) {
    if (e.touches.length === 2) {
        e.preventDefault();
        initialDistance = getDistance(e.touches[0], e.touches[1]);
        initialZoom = currentZoom;
    }
});

document.addEventListener('touchmove', function(e) {
    if (e.touches.length === 2) {
        e.preventDefault();
        const currentDistance = getDistance(e.touches[0], e.touches[1]);
        const scale = currentDistance / initialDistance;
        currentZoom = Math.min(Math.max(initialZoom * scale, 0.5), 5);
        updateZoom();
    }
});

function getDistance(touch1, touch2) {
    const dx = touch1.clientX - touch2.clientX;
    const dy = touch1.clientY - touch2.clientY;
    return Math.sqrt(dx * dx + dy * dy);
}
</script>
@endsection
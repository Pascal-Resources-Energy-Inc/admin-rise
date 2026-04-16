@extends('layouts.header')

@section('css')
<style>
        .client-store-page{
            margin-top: 90px !important;
        }
        .main-layout .store-locations-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        gap: 20px;
        height: 600px;
        position: relative;
        z-index: 1;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .main-layout .store-locations-wrapper .sidebar {
        width: 350px !important;
        background: white !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        display: flex !important;
        flex-direction: column !important;
        position: relative !important;
        top: auto !important;
        left: 0 !important;
        right: auto !important;
        height: auto !important;
        z-index: auto !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        flex-shrink: 0;
        transform: none !important;
    }

    .main-layout .main-content {
        margin-left: 0 !important;
    }

    .main-layout .content-area {
        padding: 32px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    @media (max-width: 768px) {
        .main-layout .store-locations-wrapper {
            flex-direction: column;
            height: auto;
            padding: 16px;
            gap: 16px;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .store-locations-wrapper .sidebar {
            width: 100% !important;
            height: auto !important;
            max-height: none !important;
            margin-bottom: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            min-height: 350px;
            flex-shrink: 0;
            left: 0 !important;
            right: 0 !important;
            transform: none !important;
        }
        
        .main-layout .content-area {
            padding: 16px;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }
    
    /* Override any conflicting styles from the main layout */
    .store-locations-wrapper .sidebar-header,
    .store-locations-wrapper .sidebar-nav,
    .store-locations-wrapper .sidebar-footer {
        /* Reset main sidebar styles */
        all: unset;
    }
    
    /* Restore store location specific styles */
    .store-locations-wrapper .search-section {
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        flex-shrink: 0;
    }
    
    .store-locations-wrapper .search-label {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .store-locations-wrapper .filter-dropdown {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .store-locations-wrapper .search-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        color: #666;
        box-sizing: border-box;
    }
    
    .store-locations-wrapper .stores-list {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }
    
    /* Rest of your existing store location styles... */
    .page-title {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        color: #333;
        margin-bottom: 30px;
    }
    
    .legend {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.2s;
        user-select: none;
    }

    .legend-item:hover {
        background-color: #f0f0f0;
    }

    .legend-item.inactive {
        opacity: 0.5;
        background-color: #f5f5f5;
    }

    .legend-item.inactive .legend-color {
        opacity: 0.3;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }
    
    .legend-dealer { background-color: #5DADE2; }
    .legend-customer { background-color: #E74C3C; }
    
    .store-item {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .store-item:hover {
        background-color: #f8f9fa;
    }
    
    .store-item.active {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
    }
    
    .store-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    
    .store-type-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        color: white;
    }
    
    .store-type-badge.dealer {
        background-color: #5DADE2;
    }
    
    .store-type-badge.customer {
        background-color: #E74C3C;
    }
    
    .store-name {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        flex: 1;
        word-wrap: break-word;
    }
    
    .store-details {
        margin-bottom: 8px;
    }
    
    .store-business-name {
        font-size: 13px;
        color: #555;
        font-weight: 500;
        margin-bottom: 4px;
        word-wrap: break-word;
    }
    
    .store-address {
        font-size: 12px;
        color: #666;
        line-height: 1.4;
        margin-bottom: 6px;
        word-wrap: break-word;
    }
    
    .store-phone {
        font-size: 12px;
        color: #E74C3C;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .map-container {
        flex: 1;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .map-frame {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .map-controls {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1000;
        background: white;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .map-toggle {
        padding: 8px 12px;
        border: none;
        background: white;
        font-size: 12px;
        cursor: pointer;
        border-right: 1px solid #eee;
    }
    
    .map-toggle:first-child {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    
    .map-toggle:last-child {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        border-right: none;
    }
    
    .map-toggle.active {
        background: #2196f3;
        color: white;
    }
    
    .map-toggle:hover:not(.active) {
        background: #f5f5f5;
    }
    
    .fullscreen-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        background: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        font-size: 14px;
    }
    
    .fullscreen-btn:hover {
        background: #f5f5f5;
    }
    
    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .store-locations-wrapper {
            flex-direction: column;
            height: auto;
        }

        .map-container {
            width: 100%;
            min-height: 300px;
            height: auto;
            aspect-ratio: 16 / 9;
        }

        .map-frame {
            width: 100%;
            height: 100%;
            min-height: 300px;
        }
        
        .footer .company-info {
            margin-left: -70px;
        }
    }

    
</style>
@endsection

@section('content')
<div class="page-header @if(auth()->user()->role === 'Client') client-store-page @endif">
    <h1 class="page-title">Store Locations</h1>
    <div class="legend">
        <div class="legend-item" data-type="dealer" id="dealerLegend">
            <div class="legend-color legend-dealer"></div>
            <span>Dealer</span>
        </div>
        <div class="legend-item" data-type="customer" id="customerLegend">
            <div class="legend-color legend-customer"></div>
            <span>Customer</span>
        </div>
    </div>
</div>

<div class="store-locations-wrapper">
    <div class="sidebar">
        <div class="search-section">
            <div class="search-label">Search Location</div>
            <select id="storeTypeFilter" class="filter-dropdown">
                <option value="">All Types</option>
                <option value="dealer">Dealer</option>
                <option value="customer">Customer</option>
            </select>
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="Search by name, address or number">
        </div>
        <div class="stores-list">
            @forelse($locations as $location)
            <div class="store-item" 
                data-store-id="{{ $location->id }}" 
                data-store-type="{{ $location->location_type }}">
                
                <div class="store-header">
                    <span class="store-type-badge {{ $location->location_type }}">
                        {{ ucfirst($location->location_type) }}
                    </span>
                </div>
                
                <div class="store-details">
                    @if($location->store_name && $location->store_name !== $location->name)
                        <div class="store-name">{{ $location->store_name }}</div>
                        <div class="store-business-name">{{ $location->name }}</div>
                    @else
                        <div class="store-name">{{ $location->name }}</div>
                    @endif
                    
                    <div class="store-address">{{ $location->address }}</div>
                    
                    @if($location->number)
                    <div class="store-phone">
                        <span>📞</span>
                        <span>{{ $location->number }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="store-item">
                <div class="store-name">No locations found</div>
                <div class="store-address">Please check back later for updated locations.</div>
            </div>
            @endforelse
        </div>
    </div>
    <div class="map-container">
        <div id="map" class="map-frame"></div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZw51f1ZyJIjCbkNH2rU0Ze5nOiOBsIuE&callback=delayedInitMap"></script>

<script>
let map;
let markers = [];
let geocoder;
let infoWindow;
const storeLocations = @json($locations);

// Track visibility state for each type
let visibilityState = {
    dealer: true,
    customer: true
};

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const storeTypeFilter = document.getElementById('storeTypeFilter');
    const storeItems = document.querySelectorAll('.store-item');
    
    // Initialize legend functionality
    initLegendControls();
    
    function filterStores() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = storeTypeFilter.value;
        
        storeItems.forEach(function(item) {
            const text = item.textContent.toLowerCase();
            const storeType = item.dataset.storeType;
            const storeId = item.dataset.storeId;
            
            const matchesSearch = !searchTerm || text.includes(searchTerm);
            const matchesType = !selectedType || storeType === selectedType;
            const isVisible = visibilityState[storeType]; // Check legend visibility
            
            if (matchesSearch && matchesType && isVisible) {
                item.style.display = 'block';
                showMarker(storeId, storeType);
            } else {
                item.style.display = 'none';
                hideMarker(storeId, storeType);
            }
        });
    }
    
    // Update the existing event listeners to use the new filterStores function
    storeItems.forEach(function(item) {
        item.addEventListener('click', function() {
            storeItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            const storeId = this.dataset.storeId;
            const storeType = this.dataset.storeType;
            centerMapOnStore(storeId, storeType);
        });
    });
    
    searchInput.addEventListener('input', filterStores);
    storeTypeFilter.addEventListener('change', filterStores);
    
    // Expose filterStores globally so legend controls can use it
    window.filterStores = filterStores;
});

function initLegendControls() {
    const dealerLegend = document.getElementById('dealerLegend');
    const customerLegend = document.getElementById('customerLegend');
    
    dealerLegend.addEventListener('click', function() {
        toggleTypeVisibility('dealer');
    });
    
    customerLegend.addEventListener('click', function() {
        toggleTypeVisibility('customer');
    });
}

function toggleTypeVisibility(type) {
    // Toggle the visibility state
    visibilityState[type] = !visibilityState[type];
    
    // Update legend appearance
    const legendElement = document.getElementById(type + 'Legend');
    if (visibilityState[type]) {
        legendElement.classList.remove('inactive');
    } else {
        legendElement.classList.add('inactive');
    }
    
    // Refilter all stores and markers
    if (window.filterStores) {
        window.filterStores();
    }
}

function initMap() {
    const center = { lat: 12.8797, lng: 121.7740 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });
    
    geocoder = new google.maps.Geocoder();
    infoWindow = new google.maps.InfoWindow();
    addMarkersForStores();
}

function addMarkersForStores() {
    storeLocations.forEach(function(location) {
        if (location.address) {
            geocodeAndAddMarker(location);
        }
    });
}

function geocodeAndAddMarker(location) {
    const address = location.address + ', Philippines';
    
    geocoder.geocode({ address: address }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const position = results[0].geometry.location;
            const isDealer = location.location_type === 'dealer';
            
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: location.store_name || location.name,
                icon: {
                    url: '{{ asset("images/location.png") }}',
                    scaledSize: new google.maps.Size(32, 32),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(16, 32)
                },
                storeId: location.id,
                storeType: location.location_type,
                storeData: location
            });
            
            const borderColor = isDealer ? '#5DADE2' : '#E74C3C';
            const circle = new google.maps.Circle({
                strokeColor: borderColor,
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillOpacity: 0,
                map: map,
                center: position,
                radius: 20
            });
            
            marker.borderCircle = circle;
            
            marker.addListener('click', function() {
                showStoreInfo(marker);
                highlightStoreInList(location.id, location.location_type);
            });
            
            markers.push(marker);
        } else {
            console.log('Geocoding failed for: ' + (location.store_name || location.name) + ' - ' + status);
        }
    });
}

function showStoreInfo(marker) {
    const location = marker.storeData;
    const locationType = location.location_type === 'dealer' ? 'Dealer' : 'Customer';
    const phoneInfo = location.number ? `<p><strong>📞 ${location.number}</strong></p>` : '';
    const displayName = location.store_name || location.name;
    const businessName = (location.store_name && location.store_name !== location.name) ? location.name : '';
    
    const content = `
        <div style="max-width: 300px;">
            <h3 style="margin: 0 0 10px 0; color: #333;">${displayName}</h3>
            ${businessName ? `<p style="margin: 0 0 5px 0; color: #666; font-weight: 500;">${businessName}</p>` : ''}
            <p style="margin: 0 0 10px 0; color: #666;">${location.address}</p>
            ${phoneInfo}
            <p style="margin: 0; padding: 5px 10px; background: ${location.location_type === 'dealer' ? '#5DADE2' : '#E74C3C'}; color: white; border-radius: 15px; display: inline-block; font-size: 12px;">
                ${locationType}
            </p>
        </div>
    `;
    
    infoWindow.setContent(content);
    infoWindow.open(map, marker);
}

function centerMapOnStore(storeId, storeType) {
    const marker = markers.find(m => m.storeId == storeId && m.storeType === storeType);
    if (marker) {
        map.setCenter(marker.getPosition());
        map.setZoom(15);
        showStoreInfo(marker);
    }
}

function highlightStoreInList(storeId, storeType) {
    const storeItems = document.querySelectorAll('.store-item');
    storeItems.forEach(item => {
        item.classList.remove('active');
        if (item.dataset.storeId == storeId && item.dataset.storeType === storeType) {
            item.classList.add('active');
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
}

function showMarker(storeId, storeType) {
    const marker = markers.find(m => m.storeId == storeId && m.storeType === storeType);
    if (marker && visibilityState[storeType]) { // Check visibility state
        marker.setVisible(true);
        if (marker.borderCircle) {
            marker.borderCircle.setVisible(true);
        }
    }
}

function hideMarker(storeId, storeType) {
    const marker = markers.find(m => m.storeId == storeId && m.storeType === storeType);
    if (marker) {
        marker.setVisible(false);
        if (marker.borderCircle) {
            marker.borderCircle.setVisible(false);
        }
    }
}

function toggleFullscreen() {
    const mapContainer = document.querySelector('.map-container');
    if (!document.fullscreenElement) {
        mapContainer.requestFullscreen().catch(err => {
            console.log('Error attempting to enable fullscreen:', err.message);
        });
    } else {
        document.exitFullscreen();
    }
}

document.addEventListener('fullscreenchange', function() {
    const mapContainer = document.querySelector('.map-container');
    if (document.fullscreenElement) {
        mapContainer.style.marginLeft = '0';
    } else {
        mapContainer.style.marginLeft = '0';
    }
    setTimeout(() => {
        google.maps.event.trigger(map, 'resize');
    }, 100);
});

function delayedInitMap() {
    setTimeout(initMap, 100);
}
document.addEventListener('fullscreenchange', function() {
    const mapContainer = document.querySelector('.map-container');
    if (document.fullscreenElement) {
        mapContainer.style.marginLeft = '0';
    } else {
        mapContainer.style.marginLeft = '0';
    }
    setTimeout(() => {
        google.maps.event.trigger(map, 'resize');
    }, 100);
});

function delayedInitMap() {
    setTimeout(initMap, 100);
}
</script>
@endsection
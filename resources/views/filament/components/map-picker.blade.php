<div class="space-y-4"
     x-data="{
        map: null,
        marker: null,
        circle: null,
        coordinate: @entangle('data.coordinate'),
        radius: @entangle('data.radius'),
        searchQuery: '',
        loadLeaflet() {
            return new Promise((resolve, reject) => {
                if (window.L) {
                    resolve(window.L);
                    return;
                }
                
                // Load Leaflet CSS dynamically
                if (!document.getElementById('leaflet-css')) {
                    let link = document.createElement('link');
                    link.id = 'leaflet-css';
                    link.rel = 'stylesheet';
                    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    link.setAttribute('crossorigin', '');
                    document.head.appendChild(link);
                }
                
                // Load Leaflet JS dynamically
                let script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.setAttribute('crossorigin', '');
                script.onload = () => {
                    if (window.L) {
                        resolve(window.L);
                    } else {
                        reject(new Error('Leaflet global object not found'));
                    }
                };
                script.onerror = () => reject(new Error('Failed to load Leaflet JS'));
                document.head.appendChild(script);
            });
        },
        async initMap() {
            try {
                // Wait until Leaflet is fully loaded
                await this.loadLeaflet();
                
                // Wait a moment for modal to render fully
                setTimeout(() => {
                    let defaultLat = -6.200000;
                    let defaultLng = 106.816666;
                    
                    let currentCoord = this.coordinate;
                    if (currentCoord) {
                        let parts = currentCoord.split(',');
                        if (parts.length === 2) {
                            defaultLat = parseFloat(parts[0]) || defaultLat;
                            defaultLng = parseFloat(parts[1]) || defaultLng;
                        }
                    }
                    
                    let container = L.DomUtil.get('leaflet-map-picker');
                    if (container) {
                        container._leaflet_id = null;
                    }
                    
                    // Initialize map
                    this.map = L.map('leaflet-map-picker').setView([defaultLat, defaultLng], 15);
                    
                    // OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.map);
                    
                    // Draggable marker
                    this.marker = L.marker([defaultLat, defaultLng], {
                        draggable: true
                    }).addTo(this.map);
                    
                    // Bounding circle for radius
                    let radiusVal = parseFloat(this.radius) || 100;
                    this.circle = L.circle([defaultLat, defaultLng], {
                        color: '#E8192C', // Pertamina red
                        fillColor: '#E8192C',
                        fillOpacity: 0.1,
                        radius: radiusVal
                    }).addTo(this.map);
                    
                    // Drag events
                    this.marker.on('drag', (e) => {
                        let position = this.marker.getLatLng();
                        let lat = position.lat.toFixed(6);
                        let lng = position.lng.toFixed(6);
                        this.coordinate = `${lat},${lng}`;
                        if (this.circle) {
                            this.circle.setLatLng(position);
                        }
                    });
                    
                    // Map click events
                    this.map.on('click', (e) => {
                        let position = e.latlng;
                        this.marker.setLatLng(position);
                        let lat = position.lat.toFixed(6);
                        let lng = position.lng.toFixed(6);
                        this.coordinate = `${lat},${lng}`;
                        if (this.circle) {
                            this.circle.setLatLng(position);
                        }
                    });
                    
                    // Recenter map on container resize/show
                    this.map.invalidateSize();
                }, 300);
            } catch (e) {
                console.error(e);
                alert('Gagal memuat sistem peta. Coba muat ulang halaman.');
            }
        },
        async searchAddress() {
            if (!this.searchQuery) return;
            try {
                let res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}`);
                let data = await res.json();
                if (data && data.length > 0) {
                    let lat = parseFloat(data[0].lat);
                    let lng = parseFloat(data[0].lon);
                    let position = [lat, lng];
                    
                    this.map.setView(position, 16);
                    this.marker.setLatLng(position);
                    this.coordinate = `${lat.toFixed(6)},${lng.toFixed(6)}`;
                    if (this.circle) {
                        this.circle.setLatLng(position);
                    }
                } else {
                    alert('Lokasi tidak ditemukan.');
                }
            } catch (e) {
                alert('Terjadi kesalahan saat mencari alamat.');
            }
        }
     }"
     x-init="initMap()"
     x-effect="if (circle && radius) { circle.setRadius(parseFloat(radius) || 100); }"
>
    <!-- Search container -->
    <div style="display: flex; gap: 8px; margin-bottom: 12px;">
        <input type="text" 
               x-model="searchQuery" 
               @keydown.enter.prevent="searchAddress()"
               placeholder="Cari lokasi (contoh: Pertamina Cilacap)..." 
               style="flex: 1; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; background-color: #ffffff; color: #0f172a; font-size: 13px;" />
        <button type="button" 
                @click="searchAddress()"
                style="padding: 8px 16px; border-radius: 8px; background-color: #E8192C; color: #ffffff; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='#cf1525'"
                onmouseout="this.style.backgroundColor='#E8192C'">
            Cari
        </button>
    </div>

    <!-- Leaflet map -->
    <div id="leaflet-map-picker" style="height: 380px; width: 100%; border: 1px solid #cbd5e1; border-radius: 12px; z-index: 1;"></div>

    <!-- Preview data panel -->
    <div style="padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 12px; display: flex; flex-direction: column; gap: 4px;">
        <div style="display: flex; justify-content: space-between;">
            <span style="color: #64748b;">Selected Coordinate:</span>
            <span style="font-weight: 700; color: #0f172a;" x-text="coordinate || 'Belum dipilih'"></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: #64748b;">Absence Radius:</span>
            <span style="font-weight: 700; color: #0f172a;" x-text="(radius || 100) + ' meter'"></span>
        </div>
        <div style="font-size: 10px; color: #94a3b8; margin-top: 6px;">
            💡 Geser pin merah atau klik pada peta untuk menyesuaikan posisi. Perubahan koordinat langsung diperbarui di form.
        </div>
    </div>
</div>

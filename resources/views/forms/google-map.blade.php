<div x-data="{
        state: $wire.entangle('{{ $getStatePath() }}'),
        latState: $wire.entangle('data.latitude'),
        lngState: $wire.entangle('data.longitude'),
        init() {
            const map = new google.maps.Map(this.$refs.map, {
                center: { lat: -6.2441, lng: 106.7992 },
                zoom: 12,
            });
            let marker = new google.maps.Marker({
                map: map,
                position: { lat: -6.2441, lng: 106.7992 },
                draggable: true
            });

            map.addListener('click', (e) => {
                const lat = e.latLng.lat();
                const lng = e.latLng.lng();
                marker.setPosition(e.latLng);
                this.latState = lat;
                this.lngState = lng;
            });

            marker.addListener('dragend', (e) => {
                this.latState = e.latLng.lat();
                this.lngState = e.latLng.lng();
            });
        }
    }"
    style="width: 100%; height: 400px; border-radius: 8px; overflow: hidden;"
    x-ref="map"
    wire:ignore
>
</div>
const $ = require('jquery')

require('leaflet')
require('leaflet/dist/leaflet.css')

require('leaflet.markercluster')
require('leaflet.markercluster/dist/MarkerCluster.css')
require('leaflet.markercluster/dist/MarkerCluster.Default.css')

require('leaflet-fullscreen')
require('leaflet-fullscreen/dist/leaflet.fullscreen.css')

require('../css/map.css')

let map

function initmap(lat, lon) {
    const osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
    const osmAttrib = 'Map data (C) <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    const osm = new L.TileLayer(osmUrl, {attribution: osmAttrib})

    map = new L.Map('map', {fullscreenControl: true})

    map.addLayer(osm)

    map.setView(new L.LatLng(lat, lon), 7)

    let legend = L.control({position: 'topleft'})
    legend.onAdd = function (map) {
        // let groups = $('#jsData').data('mapgroups')
        let div = L.DomUtil.create('div', 'info legend')
        div.innerHTML =
            '<a class="btn btn-sm btn-outline-secondary" href="/">Home</a>'//<br>'
            // + '<select id="groupSelect" class="selectpicker" data-style="btn-success" data-width="fit">'
            // + '<option>' + groups.join('</option><option>') + '</option>'
            // + '</select>'
        div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation
        L.DomEvent.disableClickPropagation(div)
        return div
    }
    legend.addTo(map)

    $('#groupSelect').on('change', function (e) {
        loadMarkers($(this).val())
    })

    L.Control.Watermark = L.Control.extend({
        onAdd: function (map) {
            let img = L.DomUtil.create('img')
            img.src = '../../build/img/4E Global black RGB.png'
            img.style.width = '100px'

            return img
        },

        onRemove: function (map) {
            // Nothing to do here
        }
    })

    L.control.watermark = function (opts) {
        return new L.Control.Watermark(opts)
    }

    L.control.watermark({position: 'bottomleft'}).addTo(map)
}

const markers = L.markerClusterGroup({disableClusteringAtZoom: 16})

function loadMarkers(group) {
    markers.clearLayers()
    const myIcon = L.icon({
        iconUrl: '/build/img/map-icon-green.png',
        iconSize: [22, 36],
        iconAnchor: [11, 36],
        popupAnchor: [0, -18],
    })

    $.get('/map/waypoints', {'group': group}, function (data) {
        $(data).each(function () {
            let marker =
                new L.Marker(
                    new L.LatLng(this.lat, this.lng),
                    {
                        wp_id: this.id, icon: myIcon
                    }
                )

            marker.bindPopup('Loading...')

            marker.on('click', function (e) {
                let popup = e.target.getPopup()
                $.get('/map/waypoint-info/' + e.target.options.wp_id).done(function (data) {
                    popup.setContent(data)
                    popup.update()
                })
            })

            markers.addLayer(marker)
            map.addLayer(markers)
        })
    }, 'json')
}

window.updateWaypointKeyCount = function (wpId, userId, count) {
    console.log(count)
    $.post(
        '/map/update-waypoint-key-count/'+wpId,
        {
            userId: userId,
            count: count
        }
    )
        .done(function (data) {
            // alert('Data Loaded: ' + data)
            map.closePopup()
        })
        .fail(function (error) {
            alert('error')
            console.log(error)
            map.closePopup()
        })
    // alert ('hey '+userId+' '+value)
}
let lat = -1.262326
let lon = -79.09357

initmap(lat, lon)

loadMarkers($('#groupSelect').val())


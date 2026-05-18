<x-backoffice.layout.app-layout title="Dashboard Polling">
  @push('css')
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
      <style>
          #map {
              width: 100%;
              height: 90vh;
          }
      </style>
  @endpush
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
  </script>

  <div id="map"></div>

  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Detail Suara</h5>
                  {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
              </div>
              <div class="modal-body">
                  ...
              </div>
              <div class="modal-footer">
                  {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
              </div>
          </div>
      </div>
  </div>
  @push('js')
      <script>
          $(document).ready(function() {
              var map = L.map('map').setView([-0.789275, 113.921327], 5.3);
              var popupData = {!! json_encode($dataPerhitungan) !!};
              // var popupData = [
              //    {
              //         id : 11,
              //         title: 'Aceh',
              //         description: 'Testing Aceh <a target="_blank" href="https://google.com">Ini Klik Link</a>',
              //         fillColor: 'red',
              //         color: 'blue',
              //         kadal: 'red',
              //     },
              //     {
              //         id : 13,
              //         title: 'Sumatera Barat',
              //         description: 'Testing Sumbar <a target="_blank" href="https://google.com">Ini Klik Link</a>',
              //         fillColor: 'yellow',
              //         color: 'black',
              //     },
              //     {
              //         id : 61,
              //         title: 'Kalimantan Barat',
              //         description: 'Testing Sumbar <a target="_blank" href="https://google.com">Ini Klik Link</a>'
              //     }
              // ];
              L.tileLayer('https://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}', {
                  maxZoom: 20,
                  subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
              }).addTo(map);
              $.ajax({
                  url: `{{ asset('js/indonesiageo.json') }}`,
                  method: 'get',
                  dataType: 'json',
                  success: function(res) {
                      var provinces = L.geoJSON(res, {
                          style: function(feature) {
                              var color = {
                                  fillColor: 'transparent',
                                  color: 'transparent'
                              };

                              var data = feature.properties;
                              popupData.forEach(function(p) {
                                  if (p.id == data.cartodb_id) {
                                      color = {
                                          fillColor: p.fillColor ?? 'black',
                                          color: p.color ?? 'black'
                                      }
                                  }
                              })

                              return color


                          }
                      }).addTo(map);

                      provinces.off('click').on('click', function(e) {
                          var data = e.layer.feature.properties;
                          popupData.forEach(function(p) {
                              if (p.id == data.cartodb_id) {
                                  $('#exampleModalCenter .modal-title').html(p.title);
                                  $('#exampleModalCenter .modal-body').html(p
                                      .description + '<br>' + p.namaPaslon1 + ' ' + p
                                      .suaraPaslons1 + '<br>' + p.namaPaslon2 + ' ' +
                                      p.suaraPaslons2 + '<br>' + p.namaPaslon3 + ' ' +
                                      p.suaraPaslons3);
                                  $('#exampleModalCenter').modal('show');
                              }
                          })

                      });
                  }
              });
          });
      </script>
  @endpush
</x-backoffice.layout.app-layout>

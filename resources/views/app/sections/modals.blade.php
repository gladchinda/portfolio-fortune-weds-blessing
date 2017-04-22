<section id="app-modals-container" class="container-fluid">
    <div id="map-view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="View Map">
        <div class="modal-dialog modal-cover" role="document">
            <div class="modal-content">
                <div class="loading-spinner">
                    <div class="loading-spinner-container">
                        <span class="loading-text" data-loading-text="Loading Map"></span>
                        <span class="fa fa-circle-o-notch fa-spin fa-fw fa-3x"></span>
                    </div>
                </div>
                <div class="modal-header">
                    <button type="button" class="btn close modal-close-x" data-dismiss="modal" aria-label="Close" title="Close Map"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <iframe class="goog-map-embed-frame" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

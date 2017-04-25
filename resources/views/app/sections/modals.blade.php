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
                    <button type="button" class="btn close modal-close-x" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <iframe class="goog-map-embed-frame" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    <div id="photo-gallery-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Photo Gallery">
        <div class="modal-dialog modal-cover" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn close modal-close-x" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-start-slideshow="true">
                        <div class="slides"></div>
                        <h3 class="title"></h3>
                        <a class="prev"><i class="fa fa-angle-left"></i></a>
                        <a class="next"><i class="fa fa-angle-right"></i></a>
                        <a class="close">&times;</a>
                        <a class="play-pause"></a>
                        <ol class="indicator"></ol>
                    </div>
                    
                    <div class="gallery-title">Photo Gallery</div>
                    <div id="blueimp-photo-links"></div>
                </div>
            </div>
        </div>
    </div>
</section>

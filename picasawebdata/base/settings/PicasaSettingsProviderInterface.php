<?php namespace Inetis\GooglePhotos\PicasaWebData\Base\Settings;

interface PicasaSettingsProviderInterface
{
    public function getHttpReferrer();
    public function getVisibility(); // A string being the visibility (see API Reference) of the albums to show
    public function getAlbumThumbSize(); // A string being the thumbsize (see API Reference) of the albums to show
    public function getImagesCropMode(); // A char being the crop mode to use (h, w, s)
    public function getImagesShouldCrop(); // A boolean, true if images should be cropped
    public function getMaxResults(); // An integer being the "max-result" parameter for pagination (see API Reference)
    public function getStartIndex(); // An integer being the "start-index" parameter for pagination (see API Reference)
    public function getHiddenAlbums();
}
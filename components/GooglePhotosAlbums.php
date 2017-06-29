<?php namespace Inetis\GooglePhotos\Components;

use Cache;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Inetis\GooglePhotos\Models\Settings;
use Inetis\GooglePhotos\PicasaWebData\OctoberCms\ComponentSettingsProvider;
use Inetis\GooglePhotos\PicasaWebData\PicasaClient;

class GooglePhotosAlbums extends ComponentBase
{
    /**
     * @var PicasaClient
     */
    private $picasaClient;

    public function componentDetails()
    {
        return [
            'name' => 'inetis.googlephotos::lang.component.albums.name',
            'description' => 'inetis.googlephotos::lang.component.albums.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'albumPage' => [
                'title' => 'inetis.googlephotos::lang.component.fields.albumPageTitle',
                'description' => 'inetis.googlephotos::lang.component.fields.albumPageDescription',
                'type' => 'dropdown',
            ],
            'visibility' => [
                'title' => 'inetis.googlephotos::lang.component.fields.visibilityTitle',
                'description' => 'inetis.googlephotos::lang.component.fields.visibilityDescription',
                'default' => 'all',
                'type' => 'dropdown',
                'options' => [
                    'all' => 'inetis.googlephotos::lang.component.fields.optionAll',
                    'public' => 'inetis.googlephotos::lang.component.fields.optionPublic',
                    'private' => 'inetis.googlephotos::lang.component.fields.optionPrivate',
                    'visible' => 'inetis.googlephotos::lang.component.fields.optionVisible'
                ]
            ],
            'thumbSize' => [
                'title' => 'inetis.googlephotos::lang.component.fields.thumbSizeTitle',
                'description' => 'inetis.googlephotos::lang.component.fields.thumbSizeDescription',
                'default' => '160',
                'type' => 'string'
            ],
            'cropMode' => [
                'title' => 'inetis.googlephotos::lang.component.fields.cropModeTitle',
                'description' => 'inetis.googlephotos::lang.component.fields.cropModeDescription',
                'default' => 's',
                'type' => 'dropdown',
                'options' => [
                    'h' => 'inetis.googlephotos::lang.component.fields.optionHeight',
                    'w' => 'inetis.googlephotos::lang.component.fields.optionWidth',
                    's' => 'inetis.googlephotos::lang.component.fields.optionSmallest',
                    'l' => 'inetis.googlephotos::lang.component.fields.optionLargest'
                ]
            ],
            'shouldCrop' => [
                'title' => 'inetis.googlephotos::lang.component.fields.shouldCropTitle',
                'description' => 'inetis.googlephotos::lang.component.fields.shouldCropDescription',
                'default' => 1,
                'type' => 'dropdown',
                'options' => [
                    0 => 'inetis.googlephotos::lang.component.fields.optionNo',
                    1 => 'inetis.googlephotos::lang.component.fields.optionYes'
                ]
            ],
        ];
    }

    public function getAlbumPageOptions()
    {
        return Page::withComponent('googlephotosAlbum')->sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $componentSettings = new ComponentSettingsProvider($this->properties);
        $token = $componentSettings->getOAuthToken();
        $this->picasaClient = new PicasaClient($componentSettings, $token);
    }

    public function albums()
    {
        return Cache::remember(
            'picasaAlbums',
            Settings::get('cacheDuration'),
            function() {
                return collect($this->picasaClient->getAlbumsList());
            }
        );
    }
}
